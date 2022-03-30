<?php

namespace App\Http\Controllers;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    public function buildingAdd()
    {
        return view('building.add');
    }

    public function buildingStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'building_name' => 'required',
            'number_of_floors' => 'required'
        ]);
        $userInfo = CommonModel::findRow('users', 'id', 1);
        $building_data = [
            'name' => $request->building_name,
            'floors' => $request->number_of_floors,
            'address' => $request->address,
            'company_id' => $userInfo[0]->company_id
        ];
        $flatData = [];
        CommonModel::insertRow('buildings', $building_data);
        $last_added_building = CommonModel::findRow('buildings', 'company_id', $userInfo[0]->company_id, '', '', '', 'id', 'desc')[0]->id;
        foreach ($request->flat_no as $key => $flat) {
            foreach ($flat as $inkey => $f) {
                if ($f != null) {
                    array_push(
                        $flatData,
                        [
                            'floor' => $request->floor[$key],
                            'building_id' => $last_added_building,
                            'flat_no' => $f,
                            'rent' => $request->rent[$key][$inkey] ?? 0
                        ]
                    );
                }
            }
        }
        CommonModel::insertRow('flats', $flatData);
        return response()->json(requestSuccess('Building Added Successfully', '', '/', 500), 200);
    }

    public function buildingInfo(Request $request)
    {
        // $buildingInfos = "SELECT * from `buildings` as b JOIN `flats` f on b.id=f.building_id  WHERE b.id ="."1";
        $buildingInfos = "SELECT * from `flats` WHERE building_id =" . $request->id;
        $buildingInfos = DB::select($buildingInfos);
        return view('building.info', compact('buildingInfos'));
    }


    public function renter()
    {
        $query = "SELECT * FROM tenant";
        $renters = DB::select($query);
        return view('building.renter.index', compact('renters'));
    }

    public function renterStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number_of_family_member' => 'required',
            'adv_amount' => 'required',
            'date' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'no_of_family_members' => $request->number_of_family_member,
            'advance_amount' => $request->adv_amount,
            'start_month_year' => date('Y-m-d', strtotime($request->date)),
            'nid' => '22'
        ];

        CommonModel::insertRow('tenant', $data);
        return response()->json(requestSuccess('Renter Added Successfully', '', '/renter', 500), 200);
    }

    public function buildingTransactions(Request $request)
    {
        $buildingInfos = "SELECT * from `flats` WHERE building_id =" . $request->id;
        $buildingInfos = DB::select($buildingInfos);
        $transactionInfo = "SELECT * from `transactions` WHERE building_id =" . $request->id . " AND month=" . $request->month . " AND year = " . $request->year;
        $transactionInfo = DB::select($transactionInfo);
        $renters = "SELECT * from `tenant`";
        $renters = DB::select($renters);
        // dd($transactionInfo);
        if($request->report){
            // dd($buildingInfos,$transactionInfo);
            return view('building.transactions_report', compact('buildingInfos', 'transactionInfo', 'renters'));
        }
        return view('building.transactions', compact('buildingInfos', 'transactionInfo', 'renters'));
    }

    public function flatRent(Request $request)
    {
        $request->validate([
            'rent' => 'required',
            'renter' => 'required'
        ]);
        $data = [
            'tenant_id' => $request->renter,
            'building_id' => $request->building_id,
            'flat_id' => $request->flat_id,
            'month' => $request->month,
            'year' => $request->year,
            'rent' => $request->rent,
            'pay' => $request->pay ?? 0,
            'pay_date' => $request->pay > 0 ? date('Y-m-d') : null
        ];
        CommonModel::insertRow('transactions', $data);
        return response()->json(requestSuccess('Flat Rented Successfully', '', 'building-transactions?id=' . $request->building_id . '&month=' . $request->month . '&year=' . $request->year, 500), 200);
    }

    public function makePayment(Request $request)
    {
        $pre_payment = CommonModel::findRow('transactions', 'id', $request->transaction_id)[0]->pay;
        $query = "UPDATE `transactions` SET `pay` = " . ($request->pay + $pre_payment) . " WHERE id=" . $request->transaction_id;
        DB::update($query);
        return response()->json(requestSuccess('Payment Successfull', '', url()->previous(), 500), 200);
    }

    public function buildingTransactionsImport(Request $request){
        $import_info = DB::select("SELECT * FROM `transactions` WHERE `building_id`=".$request->id." AND `month`=".$request->from_month." AND `year`=".$request->from_year);
        foreach($import_info as $ii){
            $is_exist = DB::select("SELECT * FROM `transactions` WHERE `building_id`=".$request->id." AND `month`=".$request->month." AND `year`=".$request->year." AND `flat_id`=".$ii->flat_id);
            if(count($is_exist) <= 0){
                $data = [
                    'tenant_id' => $ii->tenant_id,
                    'building_id' => $ii->building_id,
                    'flat_id' => $ii->flat_id,
                    'month' => $request->month,
                    'year' => $request->year,
                    'rent' => $ii->rent,
                    'pay' => 0,
                    'pay_date' =>  null
                ];
                CommonModel::insertRow('transactions',$data);
            }
        }
        return response()->json(requestSuccess('Imported Data Successfully', '', 'building-transactions?id=' . $ii->building_id . '&month=' . $request->month . '&year=' . $request->year, 500), 200);
    }

    public function update_flat_rent(Request $request)
    {
        $request->validate([
            'rent' => 'required',
            'renter' => 'required'
        ]);

        // dd("UPDATE `transactions` SET `tenant_id`=".$request->renter.", `rent`=".$request->rent.", `pay`=".($request->pay ?? 0).", `rent`=".$request->rent." WHERE `id`=".$request->transaction_id);
        DB::update("UPDATE `transactions` SET `tenant_id`=".$request->renter.", `rent`=".$request->rent.", `pay`=".($request->pay ?? 0).", `rent`=".$request->rent." WHERE `id`=".$request->transaction_id);

        return response()->json(requestSuccess('Flat Rent Updated Successfully', '', url()->previous(), 500), 200);
    }
}
