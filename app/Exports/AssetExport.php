<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Asset::with(['assetType', 'assignedTo', 'createdBy']);
    }

    /**
     * @param Asset $asset
     * @return array
     */
    public function map($asset): array
    {
        return [
            $asset->asset_tag,
            $asset->serial_no,
            $asset->assetType ? $asset->assetType->name : '',
            $asset->model,
            $asset->manufacturer,
            $asset->purchase_date,
            $asset->warranty_until,
            $asset->cost,
            $asset->status,
            $asset->location,
            $asset->assignedTo ? $asset->assignedTo->email : '',
            $asset->assignedTo ? $asset->assignedTo->name : '',
            $asset->created_at->format('Y-m-d H:i:s'),
            $asset->createdBy ? $asset->createdBy->name : '',
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Asset Tag',
            'Serial Number',
            'Asset Type',
            'Model',
            'Manufacturer',
            'Purchase Date',
            'Warranty Until',
            'Cost',
            'Status',
            'Location',
            'Assigned To (Email)',
            'Assigned To (Name)',
            'Created At',
            'Created By',
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
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 20,
            'K' => 30,
            'L' => 25,
            'M' => 20,
            'N' => 20,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ]);
    }
}