<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommonModel extends Model
{
    use HasFactory;

    public static function findRow($table, $col, $val, $limit=null, $col1=null, $val1=null, $order_by=null, $asc_desc='asc'){
        $order_by_query = "";
        if($order_by){
            $order_by_query = " ORDER BY ".$order_by." ".$asc_desc;
        }
        $query = "SELECT * FROM `" . $table . "` WHERE `" . $col . "`='" . $val ."'". $order_by_query;
        // dd($query);
        return DB::select($query);
    }

    public static function insertRow($table, $data=[]){
        return DB::table($table)->insert($data);
    }
}
