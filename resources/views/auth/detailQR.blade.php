@extends('layouts.plain')
@section('title', 'Asset Tagging')

@section('content')
<div style="margin-top: 50px; display: flex; justify-content: center; padding: 10px;">
    <div style="width: 100%; max-width: 600px; border: 1px solid #ddd; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1); border-radius: 8px;">
        <div style="display: flex; align-items: center; padding: 20px;">
            @php
                // Determine the image file based on the jenis_aset
                $iconMap = [
                    'PC' => 'pc.png',
                    'Tablet' => 'tablet.png',
                    'Laptop' => 'laptop.png',
                    // Add more mappings as needed
                ];
                $iconFile = isset($iconMap[$inventory->asets]) ? $iconMap[$inventory->asets] : 'default.png'; // Fallback to default icon
            @endphp
            <div style="margin-right: 20px;">
                <img src="{{ asset('assets/img/' . $iconFile) }}" alt="Asset Icon"
                    style="width: 80px; height: 80px;">
            </div>
            <div style="flex: 1; margin-left: 20px;">
                <!-- Table to display asset details -->
                <table style="width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 8px; overflow: hidden; border: 1px solid #ddd;">
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Asset Tag:</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $inventory->tagging }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Jenis Aset:</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $inventory->asets }}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Merk:</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $inventory->merk_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Serial Number:</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $inventory->seri }}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Type:</td>
                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $inventory->type }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Inline responsive media query -->
<style>
    @media (max-width: 768px) {
        div[style*="display: flex"] {
            flex-direction: column;
            align-items: center;
        }

        div[style*="display: flex"] > div:first-child {
            margin-right: 0;
            margin-bottom: 10px;
        }

        /* Make table responsive */
        table {
            width: 100%;
            border-collapse: collapse;
        }
    }
</style>
@endsection
