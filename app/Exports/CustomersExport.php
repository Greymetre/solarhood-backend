<?php

namespace App\Exports;

use App\Models\Customers;
use App\Models\UserActivity;
use App\Models\Branch;
use App\Models\CustomerCustomField;
use App\Models\User;
use App\Models\Division;
use App\Models\Designation;
use App\Models\EmployeeDetail;
use App\Models\ParentDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class CustomersExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected $filters = [];
    protected $division_users = [];

    public function __construct($request)
    {
        $this->filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'customertype' => $request->input('customertype'),
            'branch_id' => $request->input('branch_id'),
            'division_id' => $request->input('division_id'),
            'state_id' => $request->input('state_id'),
            'city_id' => $request->input('city_id'),
            'active' => $request->input('active'),
            'executive_id' => $request->input('executive_id'),
        ];

        $this->userids = getUsersReportingToAuth();
        $this->custom_fields = CustomerCustomField::pluck('field_name')->toArray();

        if (!empty($this->filters['division_id'])) {
            $this->division_users = User::where('division_id', $this->filters['division_id'])->pluck('id')->toArray();
        }
    }

    public function collection()
    {
        $query = Customers::with([
            'customertypes',
            'firmtypes',
            'customerdetails',
            'createdbyname',
            'getemployeedetail',
            'getparentdetail.parent_detail',
            'customeraddress',
        ])->where(function ($query) {
            if (!empty($this->filters['executive_id'])) {
                $query->where(function ($q) {
                    $q->where('executive_id', $this->filters['executive_id'])
                        ->orWhere('created_by', $this->filters['executive_id']);
                });
            }

            if (!Auth::user()->hasRole(['superadmin', 'Admin', 'CRM_Support'])) {
                $query->where(function ($query) {
                    $query->whereIn('executive_id', $this->userids)
                        ->orWhereIn('created_by', $this->userids);
                });
            }

            if (!empty($this->division_users)) {
                $query->where(function ($query) {
                    $common = array_intersect($this->division_users, $this->userids);
                    $query->whereIn('executive_id', $common)
                        ->orWhereIn('created_by', $common);
                });
            }

            if (!empty($this->filters['active'])) {
                $query->where('active', $this->filters['active']);
            }
            if (!empty($this->filters['start_date'])) {
                $query->whereDate('created_at', '>=', $this->filters['start_date']);
            }

            if (!empty($this->filters['end_date'])) {
                $query->whereDate('created_at', '<=', $this->filters['end_date']);
            }

            if (!empty($this->filters['customertype'])) {
                $query->where('customertype', $this->filters['customertype']);
            }

            if (!empty($this->filters['branch_id'])) {
                $branch_users = User::whereIn('branch_id', $this->filters['branch_id'])->pluck('id');
                $query->whereIn('executive_id', $branch_users);
            }

            if (!empty($this->filters['state_id'])) {
                $query->whereHas('customeraddress', function ($q) {
                    $q->where('state_id', $this->filters['state_id']);
                });
            }

            if (!empty($this->filters['city_id'])) {
                $query->whereHas('customeraddress', function ($q) {
                    $q->where('city_id', $this->filters['city_id']);
                });
            }
        });

        // Limit for performance
        return $query->limit(5000)->latest()->get();
    }

    public function headings(): array
    {
        $headings = [
            'Financial year',
            'Customer ID',
            'Discom',
            'Customer Name',
            'Capacity (in KW)',
            'Address',
            'City',
            'CA No',
            'Contact No. 1',
            'Contact No. 2',
            'Mail ID',
            'Sales Person',
            'Area',
            'Coordinates',
            'Type of Project',
            'Application No',
            'Application Date',
            'Commissioning Date',
            'Meter No',
            'Invoice No',
            'Invoice Date',
            'Mono/Poly',
            'Modules',
            'Each Module Capacity',
            'No of Panels',
            'PV Model No',
            'Inverter',
            'INV Model No',
            'Inverter Sr No',
            'ID',
            'IVRS No',
            'Password',
            'AMC Category',
            'Warranty End Date'
        ];
        return $headings;
    }

    public function map($data): array
    {
        $date = $data->creation_date ?? $data->created_at;

        $year = \Carbon\Carbon::parse($date)->year;
        $month = \Carbon\Carbon::parse($date)->month;

        if ($month >= 4) {
            $fy = 'FY ' . substr($year, -2) . '-' . substr($year + 1, -2);
        } else {
            $fy = 'FY ' . substr($year - 1, -2) . '-' . substr($year, -2);
        }
        $response = [
            $fy,
            $data->customer_code,
            $data->working_status,
            $data->first_name. ' '.$data->last_name,
            $data->sap_code,
            $data->customeraddress?->address1,
            $data->customeraddress?->cityname?->city_name,
            $data->customerdetails?->gstin_no,
            $data->mobile,
            $data->contact_number,
            $data->email,
            User::whereIn('id', $data->getemployeedetail->pluck('user_id'))->pluck('name')->implode(', '),
            $data->customeraddress?->landmark,
            $data->latitude . ',' . $data->longitude,
            $data->customerdetails?->visit_status,
            $data->customerdetails?->pan_no,
            $data->application_date ? date('d-m-Y', strtotime($data->application_date)) : '',
            $data->commissioning_date ? date('d-m-Y', strtotime($data->commissioning_date)) : '',
            $data->customerdetails?->aadhar_no,
            $data->customerdetails?->account_holder,
            $data->invoice_date ? date('d-m-Y', strtotime($data->invoice_date)) : '',
            $data->customerdetails?->grade,
            $data->customerdetails?->account_number,
            $data->customerdetails?->bank_name,
            $data->customerdetails?->ifsc_code,
            $data->customerdetails?->otherid_no,
            $data->inverter,
            $data->inv_model_no,
            $data->inverter_sr_no,
            $data->new_id,
            $data->ivrs_no,
            $data->password_string,
            $data->amc_category,
            $data->warranty_end_date ? date('d-m-Y', strtotime($data->warranty_end_date)) : '',
        ];

        return $response;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestDataRow();
                $lastColumn = $sheet->getHighestDataColumn();

                $firstRowRange = 'A1:' . $lastColumn . '1';
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getStyle($firstRowRange)->getAlignment()->setWrapText(true);
                $sheet->getStyle($firstRowRange)->getFont()->setSize(14);

                $event->sheet->getStyle($firstRowRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '00aadb'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('A1:' . $lastColumn . '' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                ]);
            },
        ];
    }
}
