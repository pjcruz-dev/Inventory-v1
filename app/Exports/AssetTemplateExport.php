<?php

namespace App\Exports;

use App\Models\AssetType;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithColumnFormatting
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return an empty array with one example row
        return [
            [
                'ASSET-001', // asset_tag
                'SN123456789', // serial_no
                'Laptop', // asset_type (name, not ID)
                'ThinkPad X1 Carbon', // model
                'Lenovo', // manufacturer
                '2023-01-15', // purchase_date
                '2026-01-15', // warranty_until
                '1500.00', // cost
                'available', // status
                'IT Department', // location
                'john.doe@example.com', // assigned_to_user (email, not ID)
            ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Asset Tag *',
            'Serial Number',
            'Asset Type *',
            'Model',
            'Manufacturer',
            'Purchase Date (YYYY-MM-DD)',
            'Warranty Until (YYYY-MM-DD)',
            'Cost',
            'Status *',
            'Location',
            'Assigned To (Email)',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 15,
            'D' => 25,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 15,
            'I' => 15,
            'J' => 20,
            'K' => 30,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ]);

        // Add data validation for asset type column
        $assetTypes = AssetType::pluck('name')->toArray();
        $assetTypeList = implode(',', $assetTypes);
        
        $validation = $sheet->getCell('C2')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Value is not in list.');
        $validation->setPromptTitle('Pick from list');
        $validation->setPrompt('Please select an asset type from the drop-down list.');
        $validation->setFormula1('"'.$assetTypeList.'"');

        // Add data validation for status column
        $statuses = ['available', 'assigned', 'in_repair', 'disposed'];
        $statusList = implode(',', $statuses);
        
        $validation = $sheet->getCell('I2')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Value is not in list.');
        $validation->setPromptTitle('Pick from list');
        $validation->setPrompt('Please select a status from the drop-down list.');
        $validation->setFormula1('"'.$statusList.'"');

        // Add notes to the first row
        $sheet->getComment('A1')->getText()->createTextRun('Required. Must be unique.');
        $sheet->getComment('C1')->getText()->createTextRun('Required. Must match an existing asset type name.');
        $sheet->getComment('I1')->getText()->createTextRun('Required. Must be one of: available, assigned, in_repair, disposed');
        $sheet->getComment('K1')->getText()->createTextRun('If status is "assigned", this must be a valid user email.');

        // Example row styling
        $sheet->getStyle('A2:K2')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2EFDA'],
            ],
            'font' => [
                'italic' => true,
            ],
        ]);

        // Add a note about the example row
        $sheet->setCellValue('A3', 'Note: The green row above is an example. Please delete it before importing your data.');
        $sheet->mergeCells('A3:K3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '44546A'],
            ],
        ]);
    }
}