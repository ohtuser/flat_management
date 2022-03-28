<?php

namespace App\Http\Controllers;

use App\Models\CommonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    public function buildingAdd(){
        return view('building.add');
    }

    public function buildingStore(Request $request){
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
        $last_added_building = CommonModel::findRow('buildings', 'company_id', $userInfo[0]->company_id,'','','','id','desc')[0]->id;
        foreach($request->flat_no as $key=>$flat){
            foreach($flat as $inkey=>$f){
                array_push($flatData,
                [
                    'floor' => $request->floor[$key],
                    'building_id' => $last_added_building,
                    'flat_no' => $f,
                    'rent' => $request->rent[$key][$inkey] ?? 0
                ]);
            }
        }
        CommonModel::insertRow('flats', $flatData);
        return response()->json(requestSuccess('Building Added Successfully', '', '/',500),200);

    }

    public function buildingInfo(Request $request){
        // $buildingInfos = "SELECT * from `buildings` as b JOIN `flats` f on b.id=f.building_id  WHERE b.id ="."1";
        $buildingInfos = "SELECT * from `flats` WHERE building_id =".$request->id;
        $buildingInfos = DB::select($buildingInfos);
        return view('building.info', compact('buildingInfos'));
    }


    public function renter(){
        $query = "SELECT * FROM tenant";
        $renters = DB::select($query);
        return view('building.renter.index', compact('renters'));
    }

    public function renterStore(Request $request){
        $request->validate([
            'name' => 'required',
            'number_of_family_member' => 'required',
            'adv_amount' => 'required',
            'date' => 'required'
        ]);

        $data= [
            'name' => $request->name,
            'no_of_family_members' => $request->number_of_family_member,
            'advance_amount' => $request->adv_amount,
            'start_month_year' => date('Y-m-d', strtotime($request->date)),
            'nid' => '22'
        ];

        CommonModel::insertRow('tenant', $data);
        return response()->json(requestSuccess('Renter Added Successfully', '', '/renter',500),200);

    }

    public function buildingTransactions(Request $request){
        $buildingInfos = "SELECT * from `flats` WHERE building_id =".$request->id;
        $buildingInfos = DB::select($buildingInfos);
        $transactionInfo = "SELECT * from `transactions` WHERE building_id =".$request->id." AND month=".$request->month." AND year = ".$request->year;
        $transactionInfo = DB::select($transactionInfo);
        // dd($transactionInfo);
        return view('building.transactions', compact('buildingInfos','transactionInfo'));
    }
}
