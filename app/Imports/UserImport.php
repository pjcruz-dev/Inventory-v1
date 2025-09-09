<?php

namespace App\Imports;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class UserImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
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
                if (empty($row['email'])) {
                    continue;
                }

                // Check if user already exists
                $existingUser = User::where('email', $row['email'])->first();
                if ($existingUser) {
                    // Update existing user
                    $existingUser->update([
                        'name' => $row['name'] ?? $existingUser->name,
                        'phone' => $row['phone'] ?? null,
                        'department' => $row['department'] ?? null,
                        'position' => $row['position'] ?? null,
                        'employee_id' => $row['employee_id'] ?? null,
                        'status' => $row['status'] ?? 'active',
                    ]);

                    $this->auditService->logUpdated($existingUser);
                    $this->importResults['success']++;
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'password' => Hash::make('password123'), // Default password
                        'phone' => $row['phone'] ?? null,
                        'department' => $row['department'] ?? null,
                        'position' => $row['position'] ?? null,
                        'employee_id' => $row['employee_id'] ?? null,
                        'status' => $row['status'] ?? 'active',
                    ]);

                    $this->auditService->logCreated($user);
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
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'employee_id' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:active,inactive',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'status.in' => 'Status must be either active or inactive.',
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