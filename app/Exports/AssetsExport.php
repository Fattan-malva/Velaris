<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsExport implements FromCollection, WithHeadings
{
    protected $assets;

    public function __construct($assets)
    {
        $this->assets = $assets;
    }

    public function collection()
    {
        return $this->assets->map(function ($asset) {
            return [
                'Asset Code' => $asset->code,
                'Name' => $asset->category,
                'Merk Name' => $asset->merk->name ?? 'N/A', // Accessing the name from the merk relationship
                'Specification' => $asset->spesification,
                'Condition' => $asset->condition,
                'Status' => $asset->status,
                'Entry Date' => $asset->entry_date,
                'Holder Name' => $asset->customer->name ?? 'N/A', // Accessing the name from the customer relationship
                'Handover Date' => $asset->handover_date,
                'Location' => $asset->location,
                'Scheduling Maintenance' => $asset->scheduling_maintenance,
                'Last Maintenance' => $asset->last_maintenance,
                'Next Maintenance' => $asset->next_maintenance,
                'Note Maintenance' => $asset->note_maintenance,
                // Add other fields you want to export
            ];
        });
    }

    public function headings(): array
    {
        return [
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
            // Match these with the collection fields above
        ];
    }
}
