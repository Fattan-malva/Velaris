<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AssetsExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $assets;

    public function __construct($assets)
    {
        $this->assets = $assets;
    }

    public function collection()
    {
        return $this->assets->map(function ($asset, $index) {
            return [
                'No' => $index + 1, // Row number
                'Asset Code' => $asset->code,
                'Name' => $asset->category,
                'Merk Name' => $asset->merk_name ?? 'N/A',
                'Specification' => $asset->spesification,
                'Condition' => $asset->condition,
                'Status' => $asset->status,
                'Entry Date' => $asset->entry_date,
                'Holder Name' => $asset->holder_name ?? 'Not Yet Handover',
                'Handover Date' => $asset->handover_date ?? 'Not Yet Handover',
                'Location' => $asset->location ?? 'Not Yet Handover',
                'Scheduling Maintenance' => $asset->scheduling_maintenance,
                'Last Maintenance' => $asset->last_maintenance ?? 'Not Yet Maintenace',
                'Next Maintenance' => $asset->next_maintenance,
                'Note Maintenance' => $asset->note_maintenance,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Asset Code',
            'Name',
            'Merk Name',
            'Specification',
            'Condition',
            'Status',
            'Entry Date',
            'Holder Name',
            'Handover Date',
            'Location',
            'Scheduling Maintenance',
            'Last Maintenance',
            'Next Maintenance',
            'Note Maintenance',
        ];
    }

    public function styles($sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'], // Green background for header
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style the entire table (cells with data)
        $sheet->getStyle('A2:O' . ($sheet->getHighestRow()))->applyFromArray([
            'border' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }

    public function title(): string
    {
        return 'Assets'; // The title of the sheet
    }

    public function columnWidths(): array
    {
        // Set column widths for better readability
        return [
            'A' => 5,  // No column
            'B' => 15, // Asset Code
            'C' => 20, // Name
            'D' => 20, // Merk Name
            'E' => 25, // Specification
            'F' => 15, // Condition
            'G' => 15, // Status
            'H' => 20, // Entry Date
            'I' => 20, // Holder Name
            'J' => 20, // Handover Date
            'K' => 15, // Location
            'L' => 25, // Scheduling Maintenance
            'M' => 20, // Last Maintenance
            'N' => 20, // Next Maintenance
            'O' => 50, // Note Maintenance
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Apply border to all cells in the table
                $event->sheet->getStyle('A1:O' . $event->sheet->getHighestRow())
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
