<?php

namespace App\Imports;

use App\Http\Controllers\AjaxController;
use App\Models\User;
use App\Models\Customers;
use App\Models\CustomerDetails;
use App\Models\Address;
use App\Models\Attachment;
use App\Models\City;
use App\Models\Pincode;
use App\Models\UserDetails;
use App\Models\Beat;
use App\Models\BeatCustomer;
use App\Models\BeatSchedule;
use App\Models\EmployeeDetail;
use App\Models\ParentDetail;

use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomersImport implements ToCollection, WithValidation, WithHeadingRow, WithBatchInserts, WithChunkReading
{
  use Importable, SkipsFailures;

  public function model(array $row)
  {
    return new Customers([
      //
    ]);
  }

  public function collection(Collection $rows)
  {
    $customerdetails = collect([]);
    $addressdetails = collect([]);
    $attachments = collect([]);

    foreach ($rows as $ky => $row) {
      if (isset($row['contact_no_1']) && strlen(preg_replace('/\s+/', '', $row['contact_no_1'])) == 10) {
        $row['contact_no_1'] = '91' . preg_replace('/\s+/', '', $row['contact_no_1']);
      }

      if (isset($row['financial_year'])) {

        list($start, $end) = explode('-', str_replace('FY ', '', $row['financial_year']));

        // build full year (assuming 20xx century)
        $year = 2000 + (int) $start;

        // first date of financial year
        $creationDate = \Carbon\Carbon::createFromFormat('Y-m-d', $year . '-04-01');

        $row['creation_date'] = $creationDate;
      }
      if (isset($row['application_date']) && is_numeric($row['application_date'])) {
        $excelDate = $row['application_date'] - 25569; // Adjust for Excel's epoch
        $unixTimestamp = strtotime('+' . $excelDate . ' days', strtotime('1970-01-01'));
        $row['application_date'] = !empty($row['application_date']) ? Carbon::createFromTimestamp($unixTimestamp)->toDateString() : '';
      } elseif (isset($row['application_date']) && $row['application_date'] != null) {
        $row['application_date'] = date('Y-m-d', strtotime($row['application_date']));
      }
      if (isset($row['commissioning_date']) && is_numeric($row['commissioning_date'])) {
        $excelDate = $row['commissioning_date'] - 25569; // Adjust for Excel's epoch
        $unixTimestamp = strtotime('+' . $excelDate . ' days', strtotime('1970-01-01'));
        $row['commissioning_date'] = !empty($row['commissioning_date']) ? Carbon::createFromTimestamp($unixTimestamp)->toDateString() : '';
      } elseif (isset($row['commissioning_date']) && $row['commissioning_date'] != null) {
        $row['commissioning_date'] = date('Y-m-d', strtotime($row['commissioning_date']));
      }
      if (isset($row['invoice_date']) && is_numeric($row['invoice_date'])) {
        $excelDate = $row['invoice_date'] - 25569; // Adjust for Excel's epoch
        $unixTimestamp = strtotime('+' . $excelDate . ' days', strtotime('1970-01-01'));
        $row['invoice_date'] = !empty($row['invoice_date']) ? Carbon::createFromTimestamp($unixTimestamp)->toDateString() : '';
      } elseif (isset($row['invoice_date']) && $row['invoice_date'] != null) {
        $row['invoice_date'] = date('Y-m-d', strtotime($row['invoice_date']));
      }
      if (isset($row['warranty_end_date']) && is_numeric($row['warranty_end_date'])) {
        $excelDate = $row['warranty_end_date'] - 25569; // Adjust for Excel's epoch
        $unixTimestamp = strtotime('+' . $excelDate . ' days', strtotime('1970-01-01'));
        $row['warranty_end_date'] = !empty($row['warranty_end_date']) ? Carbon::createFromTimestamp($unixTimestamp)->toDateString() : '';
      } elseif (isset($row['warranty_end_date']) && $row['warranty_end_date'] != null) {
        $row['warranty_end_date'] = date('Y-m-d', strtotime($row['warranty_end_date']));
      }
      $nameParts = !empty($row['customer_name']) ? preg_split('/\s+/', trim($row['customer_name'])) : [];

      if (!empty($row['old_customer_id'])) {

        Customers::where('id', '=', $row['customer_id'])->update([
          'name' => $row['firm_name'],
          'active' => $row['status'] ?? 'Y',
          'first_name' => !empty($row['first_name']) ? $row['first_name'] : '',
          'last_name' => !empty($row['last_name']) ? $row['last_name'] : '',
          'contact_number' => !empty($row['contact_number2']) ? $row['contact_number2'] : null,
          'customer_code' => !empty($row['customer_code']) ? $row['customer_code'] : null,
          'email' => !empty($row['email']) ? $row['email'] : null,
          'working_status' => !empty($row['working_status']) ? $row['working_status'] : null,
          'creation_date' => !empty($row['creation_date']) ? $row['creation_date'] : null,
          'sap_code' => !empty($row['sap_code']) ? $row['sap_code'] : null,
          'customertype' => !empty($row['customer_type_id']) ? $row['customer_type_id'] : null,


        ]);



        CustomerDetails::where('customer_id', '=', $row['customer_id'])->update([
          'gstin_no' => !empty($row['gstin_no']) ? $row['gstin_no'] : null,
          'pan_no' => !empty($row['pan_no']) ? $row['pan_no'] : null,
          'aadhar_no' => !empty($row['aadhar_no']) ? $row['aadhar_no'] : null,
          'otherid_no' => !empty($row['other_no']) ? $row['other_no'] : null,
          'grade' => !empty($row['grade']) ? $row['grade'] : null,
          'visit_status' => !empty($row['visit_status']) ? $row['visit_status'] : null,

        ]);


        Address::where('customer_id', '=', $row['customer_id'])->update([
          'pincode_id' => !empty($row['pincode_id']) ? $row['pincode_id'] : null,
          'city_id' => !empty($row['city_id']) ? $row['city_id'] : null,
          'district_id' => !empty($row['district_id']) ? $row['district_id'] : null,
          'state_id' => !empty($row['state_id']) ? $row['state_id'] : null,
          'address1' => !empty($row['address']) ? $row['address'] : null,
          'landmark' => !empty($row['market_place']) ? $row['market_place'] : null,

        ]);



        //employee start

        if (!empty($row['employee_id'])) {

          EmployeeDetail::where('customer_id', $row['customer_id'])->delete();
          //$row['employee_id'] = str_replace('[','',$row['employee_id']);
          //$row['employee_id'] = str_replace(']','',$row['employee_id']);
          $employee_data = explode(",", $row['employee_id']);

          foreach ($employee_data as $keys => $row_employee) {
            $employeeDetail = EmployeeDetail::updateOrCreate(
              [
                'customer_id' => $row['customer_id'],
                'user_id' => $row_employee,
                'created_by' => Auth::user()->id,
              ]

            );
          }
        }

        // employee end

        //parent start

        if (!empty($row['parent_id'])) {
          ParentDetail::where('customer_id', $row['customer_id'])->delete();
          //$row['parent_id'] = str_replace('[','',$row['parent_id']);
          //$row['parent_id'] = str_replace(']','',$row['parent_id']);

          $parent_data = explode(",", $row['parent_id']);

          foreach ($parent_data as $key => $row_parent) {
            $parentDetail = ParentDetail::updateOrCreate(
              [
                'customer_id' => $row['customer_id'],
                'parent_id' => $row_parent,
                'created_by' => Auth::user()->id,
              ]
            );
          }
        }
        // parent end  

      } else {
        $coords = !empty($row['coordinates']) ? explode(',', $row['coordinates']) : [];
        if ($customer = Customers::updateOrCreate(['mobile' =>  !empty($row['contact_no_1']) ? (string)$row['contact_no_1'] : '',], [
          'active' => 'Y',
          'name' => !empty($row['customer_name']) ? ucfirst($row['customer_name']) : '',
          'first_name'       => $nameParts[0] ?? '',
          'last_name'        => isset($nameParts[1]) ? ucfirst(implode(' ', array_slice($nameParts, 1))) : '',
          'email' => !empty($row['mail_id']) ? $row['mail_id'] : null,
          'working_status' => !empty($row['discom']) ? $row['discom'] : null,
          'creation_date' => !empty($row['creation_date']) ? $row['creation_date'] : null,
          'sap_code' => !empty($row['capacity_in_kw']) ? $row['capacity_in_kw'] : null,
          'password' => !empty($row['password']) ? Hash::make($row['password']) : '',
          'latitude' => isset($coords[0]) ? $coords[0] : '',
          'longitude' => isset($coords[1]) ? $coords[1] : '',
          'customer_code' => !empty($row['customer_id']) ? $row['customer_id'] : null,
          'contact_number' => (string)!empty($row['contact_no_2']) ? $row['contact_no_2'] : null,
          'application_date' => !empty($row['application_date']) ? $row['application_date'] : null,
          'commissioning_date' => !empty($row['commissioning_date']) ? $row['commissioning_date'] : null,
          'invoice_date' => !empty($row['invoice_date']) ? $row['invoice_date'] : null,
          'inverter' => !empty($row['inverter']) ? $row['inverter'] : null,
          'inv_model_no' => !empty($row['inv_model_no']) ? $row['inv_model_no'] : null,
          'inverter_sr_no' => !empty($row['inverter_sr_no']) ? $row['inverter_sr_no'] : null,
          'new_id' => !empty($row['id']) ? $row['id'] : null,
          'ivrs_no' => !empty($row['ivrs_no']) ? $row['ivrs_no'] : null,
          'password_string' => !empty($row['password']) ? $row['password'] : null,
          'amc_category' => !empty($row['amc_category']) ? $row['amc_category'] : null,
          'warranty_end_date' => !empty($row['warranty_end_date']) ? $row['warranty_end_date'] : null,

          'created_by' => Auth::user()->id,
          'created_at' => getcurentDateTime(),
          'updated_at' => getcurentDateTime()
        ])) {
          if (!empty($row['city'])) {
            $city = City::where('city_name', $row['city'])->first();
            Address::updateOrCreate(
              [
                'customer_id' => $customer['id'],
              ],
              [
                'address1' => !empty($row['address']) ? $row['address'] : null,
                'landmark' => !empty($row['area']) ? $row['area'] : null,
                'country_id' => 1,
                'city_id' => $city->id,
                'district_id' => $city->district_id,
                'state_id' => $city->state_id,
              ]
            );
          }
          //employee start
          if (!empty($row['sales_person'])) {
            $employee_data = explode(",", $row['sales_person']);

            foreach ($employee_data as $keys => $row_employee) {
              $user = User::where('active', 'Y')->where('name', $row_employee)->first();
              if ($user) {
                EmployeeDetail::updateOrCreate(
                  [
                    'customer_id' => $customer['id'],
                    'user_id' => $user->id,
                    'created_by' => Auth::user()->id,
                  ]

                );
              }
            }
          }

          //employee end



          //parent start

          if (!empty($row['parent_id'])) {
            //$row['parent_id'] = str_replace('[','',$row['parent_id']);
            //$row['parent_id'] = str_replace(']','',$row['parent_id']);

            $parent_data = explode(",", $row['parent_id']);

            foreach ($parent_data as $key => $row_parent) {
              $parentDetail = ParentDetail::updateOrCreate(
                [
                  'customer_id' => $customer['id'],
                  'parent_id' => $row_parent,
                  'created_by' => Auth::user()->id,
                ]
              );
            }
          }


          // if ($customerdetails->isNotEmpty()) {
          CustomerDetails::updateOrCreate(['customer_id' => $customer['id'],], [
            'active' => 'Y',
            'gstin_no' => !empty($row['ca_no']) ? $row['ca_no'] : null,
            'visit_status' => !empty($row['type_of_project']) ? $row['type_of_project'] : null,
            'pan_no' => !empty($row['application_no']) ? $row['application_no'] : null,
            'aadhar_no' => !empty($row['meter_no']) ? $row['meter_no'] : null,
            'account_holder' => !empty($row['invoice_no']) ? $row['invoice_no'] : null,
            'grade' => !empty($row['monopoly']) ? $row['monopoly'] : null,
            'account_number' => !empty($row['modules']) ? $row['modules'] : null,
            'bank_name' => !empty($row['each_module_capacity']) ? $row['each_module_capacity'] : null,
            'ifsc_code' => !empty($row['no_of_panels']) ? $row['no_of_panels'] : null,
            'otherid_no' => !empty($row['pv_model_no']) ? $row['pv_model_no'] : null,
            'created_at' => getcurentDateTime(),
            'updated_at' => getcurentDateTime()
          ]);
          // }

        }
      }
    }
  }

  public function rules(): array
  {
    return [
      //'name' => 'required|string|regex:/[a-zA-Z0-9\s]+/',
    ];
  }

  public function batchSize(): int
  {
    return 1000;
  }

  public function chunkSize(): int
  {
    return 1000;
  }

  public function onFailure(Failure ...$failures)
  {
    Log::stack(['import-failure-logs'])->info(json_encode($failures));
  }
}
