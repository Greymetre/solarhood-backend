<?php

namespace App\Exports;

use App\Models\Customers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class CustomersTemplate implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Customers::limit(0)->get();
    }

    public function headings(): array
    {
        return ['Financial year', 'Customer ID',    'Discom', 'Customer Name', 'Capacity (in KW)',    'Address',    'City', 'CA No', 'Contact No. 1', 'Contact No. 2', 'Mail ID', 'Sales Person',	'Area',	'Coordinates', 'Type of Project', 'Application No', 'Application Date', 'Commissioning Date', 'Meter No', 'Invoice No', 'Invoice Date', 'Mono/Poly',	'Modules', 'Each Module Capacity', 'No of Panels', 'PV Model No',	'Inverter', 'INV Model No', 'Inverter Sr No',	'ID', 'IVRS No',	'Password', 'AMC Category', 'Warranty End Date'];
    }
}
