<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetTypeTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return an empty array with example rows
        return [
            [
                'Laptop', // name
                'Portable computers for office work', // description
            ],
            [
                'Desktop', // name
                'Desktop computers for workstations', // description
            ],
            [
                'Monitor', // name
                'Display screens and monitors', // description
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
            'Description',
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 50,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'fd7e14'],
            ],
        ]);

        // Add instructions in row 5
        $sheet->setCellValue('A5', 'Instructions:');
        $sheet->setCellValue('A6', '• Name is required and must be unique');
        $sheet->setCellValue('A7', '• Description is optional but recommended');
        $sheet->setCellValue('A8', '• If asset type exists (same name), description will be updated');
        $sheet->setCellValue('A9', '• Delete this instruction section before importing');
        
        // Style instructions
        $sheet->getStyle('A5:A9')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '666666'],
            ],
        ]);
    }
}