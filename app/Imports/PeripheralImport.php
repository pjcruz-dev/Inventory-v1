<?php

namespace App\Imports;

use App\Models\Peripheral;
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

class PeripheralImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
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

                // Check if peripheral already exists by name and serial number
                $existingPeripheral = null;
                if (!empty($row['serial_number'])) {
                    $existingPeripheral = Peripheral::where('serial_number', $row['serial_number'])->first();
                } else {
                    $existingPeripheral = Peripheral::where('name', $row['name'])
                        ->where('brand', $row['brand'] ?? '')
                        ->where('model', $row['model'] ?? '')
                        ->first();
                }

                if ($existingPeripheral) {
                    // Update existing peripheral
                    $existingPeripheral->update([
                        'name' => $row['name'],
                        'type' => $row['type'] ?? $existingPeripheral->type,
                        'brand' => $row['brand'] ?? $existingPeripheral->brand,
                        'model' => $row['model'] ?? $existingPeripheral->model,
                        'serial_number' => $row['serial_number'] ?? $existingPeripheral->serial_number,
                        'status' => $row['status'] ?? 'available',
                        'condition' => $row['condition'] ?? 'good',
                        'purchase_date' => !empty($row['purchase_date']) ? date('Y-m-d', strtotime($row['purchase_date'])) : $existingPeripheral->purchase_date,
                        'warranty_until' => !empty($row['warranty_until']) ? date('Y-m-d', strtotime($row['warranty_until'])) : $existingPeripheral->warranty_until,
                        'cost' => $row['cost'] ?? $existingPeripheral->cost,
                        'location' => $row['location'] ?? $existingPeripheral->location,
                        'assigned_to_user_id' => $assignedUserId,
                        'notes' => $row['notes'] ?? $existingPeripheral->notes,
                    ]);

                    $this->auditService->logUpdated($existingPeripheral);
                    $this->importResults['success']++;
                } else {
                    // Create new peripheral
                    $peripheral = Peripheral::create([
                        'name' => $row['name'],
                        'type' => $row['type'] ?? 'other',
                        'brand' => $row['brand'] ?? null,
                        'model' => $row['model'] ?? null,
                        'serial_number' => $row['serial_number'] ?? null,
                        'status' => $row['status'] ?? 'available',
                        'condition' => $row['condition'] ?? 'good',
                        'purchase_date' => !empty($row['purchase_date']) ? date('Y-m-d', strtotime($row['purchase_date'])) : null,
                        'warranty_until' => !empty($row['warranty_until']) ? date('Y-m-d', strtotime($row['warranty_until'])) : null,
                        'cost' => $row['cost'] ?? null,
                        'location' => $row['location'] ?? null,
                        'assigned_to_user_id' => $assignedUserId,
                        'notes' => $row['notes'] ?? null,
                        'created_by' => Auth::id(),
                    ]);

                    $this->auditService->logCreated($peripheral);
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
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|in:mouse,keyboard,monitor,printer,scanner,speaker,headset,webcam,other',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'status' => 'required|string|in:available,assigned,in_repair,disposed',
            'condition' => 'nullable|string|in:excellent,good,fair,poor',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'cost' => 'nullable|numeric',
            'location' => 'nullable|string|max:200',
            'assigned_to_email' => 'nullable|string|email',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Peripheral name is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: available, assigned, in_repair, disposed.',
            'type.in' => 'Type must be one of: mouse, keyboard, monitor, printer, scanner, speaker, headset, webcam, other.',
            'condition.in' => 'Condition must be one of: excellent, good, fair, poor.',
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