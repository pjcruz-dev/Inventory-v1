<?php

namespace App\Imports;

use App\Models\AssetType;
use App\Services\AuditService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class AssetTypeImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
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
                if (empty($row['name'])) {
                    continue;
                }

                // Check if asset type already exists
                $existingAssetType = AssetType::where('name', $row['name'])->first();
                if ($existingAssetType) {
                    // Update existing asset type
                    $existingAssetType->update([
                        'description' => $row['description'] ?? $existingAssetType->description,
                    ]);

                    $this->auditService->logUpdated($existingAssetType);
                    $this->importResults['success']++;
                } else {
                    // Create new asset type
                    $assetType = AssetType::create([
                        'name' => $row['name'],
                        'description' => $row['description'] ?? null,
                    ]);

                    $this->auditService->logCreated($assetType);
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
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Asset type name is required.',
            'name.max' => 'Asset type name cannot exceed 100 characters.',
            'description.max' => 'Description cannot exceed 500 characters.',
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