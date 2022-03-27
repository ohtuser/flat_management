<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommonModel extends Model
{
    use HasFactory;

    public static function findRow($table, $col, $val, $limit=null, $col1=null, $val1=null, $order_by=null, $asc_desc='asc'){
        $limit_query = "";
        // if($limit){
        //     $limit_query = " LIMIT ".$limit;
        // }

        $query = "SELECT * FROM `" . $table . "` WHERE `" . $col . "`='" . $val ."'". $limit_query;
        // dd($query);
        return DB::select($query);
    }

    public static function insertRow($table, $data=[]){
        return DB::table($table)->insert($data);
    }
}
