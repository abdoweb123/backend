<?php

namespace App\Http\Controllers\Api;

use App\Models\Cafes;
use App\Model\CafeCategory;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CafesController extends Controller
{
    public function CafeByCategory($id){
        $Cafes=Cafes::where('category_id',$id)->get();
        return response()->json(['Cafes' => $Cafes]);
    }
    
    public function CageByid($id){
        $Cafes=Cafes::where('id',$id)->with('CafeMenu')->get();
        return response()->json(['Cafes' => $Cafes]);
    }

    public function CafeCategory(){

        $Restaurants=Restaurants::where('category_id',$id)->where('type_id',2)->get();
        if($Restaurants->count()<=0){
            return response()->json(['code' => 105, 'message' => 'Cafes Not Fount']);
        }
        
        return response()->json(['Restaurants' => $Restaurants]);
    }


}
