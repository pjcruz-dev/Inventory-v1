<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeripheralTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return an empty array with example rows
        return [
            [
                'Wireless Mouse', // name
                'mouse', // type
                'Logitech', // brand
                'MX Master 3', // model
                'SN123456', // serial_number
                'available', // status
                'good', // condition
                '2023-01-15', // purchase_date
                '2025-01-15', // warranty_until
                '99.99', // cost
                'IT Department', // location
                'john.doe@example.com', // assigned_to_email
                'Ergonomic wireless mouse for productivity', // notes
            ],
            [
                'Mechanical Keyboard', // name
                'keyboard', // type
                'Corsair', // brand
                'K95 RGB', // model
                'KB789012', // serial_number
                'assigned', // status
                'excellent', // condition
                '2023-02-20', // purchase_date
                '2026-02-20', // warranty_until
                '199.99', // cost
                'Development Team', // location
                'jane.smith@example.com', // assigned_to_email
                'RGB mechanical keyboard with Cherry MX switches', // notes
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
            'Type',
            'Brand',
            'Model',
            'Serial Number',
            'Status *',
            'Condition',
            'Purchase Date (YYYY-MM-DD)',
            'Warranty Until (YYYY-MM-DD)',
            'Cost',
            'Location',
            'Assigned To (Email)',
            'Notes',
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 20,
            'D' => 25,
            'E' => 20,
            'F' => 15,
            'G' => 15,
            'H' => 25,
            'I' => 25,
            'J' => 15,
            'K' => 20,
            'L' => 30,
            'M' => 40,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT, // Serial number as text
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD2, // Purchase date
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD2, // Warranty until
            'J' => NumberFormat::FORMAT_NUMBER_00, // Cost
            'L' => NumberFormat::FORMAT_TEXT, // Email as text
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6f42c1'],
            ],
        ]);

        // Add instructions in row 4
        $sheet->setCellValue('A4', 'Instructions:');
        $sheet->setCellValue('A5', '• Name and Status are required fields');
        $sheet->setCellValue('A6', '• Type: mouse, keyboard, monitor, printer, scanner, speaker, headset, webcam, other');
        $sheet->setCellValue('A7', '• Status: available, assigned, in_repair, disposed');
        $sheet->setCellValue('A8', '• Condition: excellent, good, fair, poor');
        $sheet->setCellValue('A9', '• Use email address for assigned user (if status is assigned)');
        $sheet->setCellValue('A10', '• Dates should be in YYYY-MM-DD format');
        $sheet->setCellValue('A11', '• If peripheral exists (same serial or name+brand+model), data will be updated');
        $sheet->setCellValue('A12', '• Delete this instruction section before importing');
        
        // Style instructions
        $sheet->getStyle('A4:A12')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '666666'],
            ],
        ]);
    }
}