<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Item;

class ItemPajakController extends Controller
{
    public function addPajakToItem(Request $request){
        $rules = [
            'item_id' => 'required',
            'pajak_id' => 'required',
        ];
        $message = [
            'item_id' => 'Silahkan pilih item.',
            'pajak_id' => 'Silahkan pilih pajak.',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        $item = Item::find($request->item_id);
        if($item){

            if($item->pajak()->attach($request->pajak_id)){
                return response()->json([
                    'message' => $item,
                ]);
            }
            return response()->json([
                'message' => 'Gagal menambahkan pajak.',
            ]);
        }
        return response()->json([
            'message' => 'Item tidak ditemukan.',
        ]);

    }
}
