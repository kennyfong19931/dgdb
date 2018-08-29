<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Translate;

class TranslateController extends Controller
{
    public function quest(Request $request, $id){
        $detailcn = $request->detailcn;
        $obj = Translate::where([
            ['type','=','1'],
            ['id','=',$id],
        ])->first();
        if($obj === null){
            $obj = new Translate;
            $obj->id = $id;
            $obj->type = 1;
        }
        $obj->text = $detailcn;
        $obj->ip = $request->ip();
        if(strlen($obj->text) > 0){
            if($obj->save()){
                \Cache::forget('blade_quest'.$id);
                return response()->json(['success' => true, 'obj' => $obj]);
            }else
                return response()->json(['success' => false]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function unit(Request $request, $id){
        $detailcn = $request->detailcn;
        $obj = Translate::where([
            ['type','=','2'],
            ['id','=',$id],
        ])->first();
        if($obj === null){
            $obj = new Translate;
            $obj->id = $id;
            $obj->type = 2;
        }
        $obj->text = $detailcn;
        $obj->ip = $request->ip();
        if(strlen($obj->text) > 0){
            if($obj->save()){
                \Cache::forget('blade_unit'.$id);
                return response()->json(['success' => true]);
            }else
                return response()->json(['success' => false]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /*$detailcn = preg_replace( "/\r|\n/", "", $request->detailcn );
    $detailcn = preg_replace( "/[,]/", "，", $detailcn );
    $detailcn = preg_replace( "/[?]/", "？", $detailcn );
    $detailcn = preg_replace( "/[!]/", "！", $detailcn );
    $detailcn = preg_replace( "/[:]/", "：", $detailcn );
    $detailcn = preg_replace( "/[;]/", "；", $detailcn );
    $detailcn = preg_replace( "/[・]/", "・", $detailcn );*/
}
?>
