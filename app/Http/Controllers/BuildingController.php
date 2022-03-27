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
        $last_added_building = CommonModel::findRow('buildings', 'company_id', $userInfo[0]->company_id)[0]->id;
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
        $buildingInfos = "SELECT * from `flats` WHERE building_id ="."1";
        $buildingInfos = DB::select($buildingInfos);
        return view('building.info', compact('buildingInfos'));
    }
}
