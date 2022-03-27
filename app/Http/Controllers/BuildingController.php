<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function buildingAdd(){
        return view('building.add');
    }
}
