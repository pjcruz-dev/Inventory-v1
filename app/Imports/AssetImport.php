<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class AssetImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $auditService;
    protected $importResults = [
        'success' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if (empty($row['asset_tag'])) {
                    continue;
                }

                // Find asset type by name
                $assetType = AssetType::where('name', $row['asset_type'])->first();
                if (!$assetType) {
                    $this->importResults['failed']++;
                    $this->importResults['errors'][] = "Row " . ($index + 2) . ": Asset type '{$row['asset_type']}' not found.";
                    continue;
                }

                // Find assigned user by email if provided
                $assignedUserId = null;
                if (!empty($row['assigned_to_email']) && $row['status'] === 'assigned') {
                    $user = User::where('email', $row['assigned_to_email'])->first();
                    if (!$user) {
                        $this->importResults['failed']++;
                        $this->importResults['errors'][] = "Row " . ($index + 2) . ": User with email '{$row['assigned_to_email']}' not found.";
                        continue;
                    }
                    $assignedUserId = $user->id;
                }

                // Check if asset tag already exists
                $existingAsset = Asset::where('asset_tag', $row['asset_tag'])->first();
                if ($existingAsset) {
                    // Update existing asset
                    $existingAsset->update([
                        'serial_no' => $row['serial_number'] ?? null,
                        'asset_type_id' => $assetType->id,
                        'model' => $row['model'] ?? null,
                        'manufacturer' => $row['manufacturer'] ?? null,
                        'purchase_date' => !empty($row['purchase_date']) ? date('Y-m-d', strtotime($row['purchase_date'])) : null,
                        'warranty_until' => !empty($row['warranty_until']) ? date('Y-m-d', strtotime($row['warranty_until'])) : null,
                        'cost' => $row['cost'] ?? null,
                        'status' => $row['status'] ?? 'available',
                        'location' => $row['location'] ?? null,
                        'assigned_to_user_id' => $assignedUserId,
                    ]);

                    $this->auditService->logUpdated($existingAsset);
                    $this->importResults['success']++;
                } else {
                    // Create new asset
                    $asset = Asset::create([
                        'asset_tag' => $row['asset_tag'],
                        'serial_no' => $row['serial_number'] ?? null,
                        'asset_type_id' => $assetType->id,
                        'model' => $row['model'] ?? null,
                        'manufacturer' => $row['manufacturer'] ?? null,
                        'purchase_date' => !empty($row['purchase_date']) ? date('Y-m-d', strtotime($row['purchase_date'])) : null,
                        'warranty_until' => !empty($row['warranty_until']) ? date('Y-m-d', strtotime($row['warranty_until'])) : null,
                        'cost' => $row['cost'] ?? null,
                        'status' => $row['status'] ?? 'available',
                        'location' => $row['location'] ?? null,
                        'assigned_to_user_id' => $assignedUserId,
                        'created_by' => Auth::id(),
                    ]);

                    $this->auditService->logCreated($asset);
                    $this->importResults['success']++;
                }
            } catch (\Exception $e) {
                $this->importResults['failed']++;
                $this->importResults['errors'][] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'asset_tag' => 'required|string|max:64',
            'asset_type' => 'required|string|max:100',
            'status' => 'required|string|in:available,assigned,in_repair,disposed',
            'serial_number' => 'nullable|string|max:128',
            'model' => 'nullable|string|max:200',
            'manufacturer' => 'nullable|string|max:200',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'cost' => 'nullable|numeric',
            'location' => 'nullable|string|max:200',
            'assigned_to_email' => 'nullable|string|email',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'asset_tag.required' => 'Asset tag is required.',
            'asset_type.required' => 'Asset type is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: available, assigned, in_repair, disposed.',
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->importResults['failed']++;
            $this->importResults['errors'][] = "Row " . ($failure->row() + 1) . ": " . implode(', ', $failure->errors());
        }
    }

    /**
     * Get the import results.
     *
     * @return array
     */
    public function getImportResults(): array
    {
        return $this->importResults;
    }
}