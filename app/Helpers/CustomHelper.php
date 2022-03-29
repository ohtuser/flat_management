<?php

use App\Models\CommonModel;

function requestSuccess($message='',$description='',$redirectTo='closeAndModalHide',$timer=null,$call='',$buttonShow=false){
    return (object)[
        'buttonShow' => $buttonShow,
        'timer' => $timer,
        'message' => $message,
        'description' => $description,
        'redirectTo' => $redirectTo,
        'call' => $call
    ];
}

function usersBuildings(){
    $userInfo = CommonModel::findRow('users', 'id', active_user())[0];
    $buildings = CommonModel::findRow('buildings', 'company_id', $userInfo->company_id);
    return $buildings;
}

function active_user(){
    $user_id=auth()->guard('auth')->user()->id??null;
    return $user_id;
}

function get_renter_info($id){
    $info = CommonModel::findRow('tenant','id',$id);
    if(count($info)> 0){
        return $info[0];
    }else{
        return null;
    }
}
