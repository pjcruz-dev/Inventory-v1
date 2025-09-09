<?php

namespace App\Exports;

use App\Models\Peripheral;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeripheralExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Peripheral::with(['assignedTo', 'createdBy']);
    }

    /**
     * @param Peripheral $peripheral
     * @return array
     */
    public function map($peripheral): array
    {
        return [
            $peripheral->name,
            $peripheral->type,
            $peripheral->brand,
            $peripheral->model,
            $peripheral->serial_number,
            $peripheral->status,
            $peripheral->condition,
            $peripheral->purchase_date,
            $peripheral->warranty_until,
            $peripheral->cost,
            $peripheral->location,
            $peripheral->assignedTo ? $peripheral->assignedTo->name : '',
            $peripheral->assignedTo ? $peripheral->assignedTo->email : '',
            $peripheral->notes,
            $peripheral->created_at->format('Y-m-d H:i:s'),
            $peripheral->createdBy ? $peripheral->createdBy->name : '',
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Type',
            'Brand',
            'Model',
            'Serial Number',
            'Status',
            'Condition',
            'Purchase Date',
            'Warranty Until',
            'Cost',
            'Location',
            'Assigned To (Name)',
            'Assigned To (Email)',
            'Notes',
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
            'A' => 25,
            'B' => 20,
            'C' => 20,
            'D' => 25,
            'E' => 20,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 20,
            'L' => 25,
            'M' => 30,
            'N' => 30,
            'O' => 20,
            'P' => 20,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6f42c1'],
            ],
        ]);
    }
}