<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = 'SELECT JSON_OBJECT("id", item.id, "nama", item.nama, "pajak", JSON_ARRAY(GROUP_CONCAT(JSON_OBJECT("id", pajak.id, "nama", pajak.nama, "rate", CONCAT(pajak.rate, "%"))))) as result_json
        FROM item
        INNER JOIN item_pajak ON item.id = item_pajak.item_id
        INNER JOIN pajak ON item_pajak.pajak_id = pajak.id
        GROUP BY item.id';

        // $query = 'SELECT JSON_OBJECT("id", item.id, "nama", item.nama, "pajak", CONCAT("[", GROUP_CONCAT("{\"id\":", pajak.id, ",\"nama\":\"", pajak.nama, "\",\"rate\":\"", CONCAT(pajak.rate, "%\"}") SEPARATOR ","), "]")) as result_json
        // FROM item
        // INNER JOIN item_pajak ON item.id = item_pajak.item_id
        // INNER JOIN pajak ON item_pajak.pajak_id = pajak.id
        // GROUP BY item.id';

        $items = \DB::select(\DB::raw($query));

        return $items;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required|max:255',
            'pajak_id' => 'required|min:2', //array of pajak id, from multiple select
        ];
        $message = [
            'nama.required' => 'Silahkan masukkan nama.',
            'nama.max' => 'Nama terlalu panjang.',
            'pajak_id.required' => 'Silahkan pilih minimal 2 tipe pajak.',
            'pajak_id.min' => 'Silahkan pilih minimal 2 tipe pajak.',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        $item = Item::create([
            'nama' => $request->nama,
        ]);
        if($item){
            $pajaks = \App\Models\Pajak::whereIn('id', $request->pajak_id)->get();
            foreach($pajaks as $pajak){
                $item->pajak()->attach($pajak->id);
            }
            return response()->json([
                'message' => 'Item berhasil ditambahkan.',
            ]);
        }
        return response()->json([
            'message' => 'Tambah item gagal.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'item' => Item::find($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json([
            'item' => Item::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'nama' => 'required|max:255',
        ];
        $message = [
            'nama.required' => 'Silahkan masukkan nama.',
            'nama.max' => 'Nama terlalu panjang.',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        $item = Item::where('id', $id)
            ->update(['nama' => $request->nama]);
        if($item){
            return response()->json([
                'messsage' => 'Item berhasil diubah.',
            ]);
        }
        return response()->json([
            'message' => 'Ubah item gagal.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if($item){
            if($item->delete()){
                return response()->json([
                    'message' => 'Item berhasil dihapus.',
                ]);
            }
            return response()->json([
                'error' => 'Hapus item gagal.',
            ]);
        }
        return response()->json([
            'error' => 'Item tidak ditemukan.',
        ]);
    }

}
