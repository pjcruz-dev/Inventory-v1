<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return an empty array with one example row
        return [
            [
                'John Doe', // name
                'john.doe@example.com', // email
                '+1234567890', // phone
                'IT Department', // department
                'Software Developer', // position
                'EMP001', // employee_id
                'active', // status
            ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name *',
            'Email *',
            'Phone',
            'Department',
            'Position',
            'Employee ID',
            'Status (active/inactive)',
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 15,
            'G' => 25,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // Email as text
            'C' => NumberFormat::FORMAT_TEXT, // Phone as text
            'F' => NumberFormat::FORMAT_TEXT, // Employee ID as text
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28a745'],
            ],
        ]);

        // Add instructions in row 3
        $sheet->setCellValue('A3', 'Instructions:');
        $sheet->setCellValue('A4', '• Name and Email are required fields');
        $sheet->setCellValue('A5', '• Status should be either "active" or "inactive"');
        $sheet->setCellValue('A6', '• If user exists (same email), data will be updated');
        $sheet->setCellValue('A7', '• New users will get default password: password123');
        $sheet->setCellValue('A8', '• Delete this instruction section before importing');
        
        // Style instructions
        $sheet->getStyle('A3:A8')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '666666'],
            ],
        ]);
    }
}