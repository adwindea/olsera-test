<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pajak;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'pajaks' => Pajak::all(),
        ]);
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
            'rate' => 'required|numeric|max:100|min:0',
        ];
        $message = [
            'nama.required' => 'Silahkan masukkan nama.',
            'nama.max' => 'Nama terlalu panjang.',
            'rate.required' => 'Silahkan masukkan rate.',
            'rate.max' => 'Rate tidak dapat lebih dari 100%.',
            'rate.min' => 'Rate tidak dapat kurang dari 0.',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        $pajak = Pajak::create([
            'nama' => $request->nama,
            'rate' => $request->rate,
        ]);
        if($pajak){
            return response()->json([
                'message' => 'Pajak berhasil ditambahkan.',
            ]);
        }
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
            'pajak' => Pajak::find($id),
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
            'pajak' => Pajak::find($id),
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
            'rate' => 'required|numeric|max:100|min:0',
        ];
        $message = [
            'nama.required' => 'Silahkan masukkan nama.',
            'nama.max' => 'Nama terlalu panjang.',
            'rate.required' => 'Silahkan masukkan rate.',
            'rate.max' => 'Rate tidak dapat lebih dari 100%.',
            'rate.min' => 'Rate tidak dapat kurang dari 0.',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        $pajak = Pajak::where('id', $id)
            ->update([
                'nama' => $request->nama,
                'rate' => $request->rate,
            ]);
        if($pajak){
            return response()->json([
                'messsage' => 'Pajak berhasil diubah.',
            ]);
        }
        return response()->json([
            'message' => 'Ubah pajak gagal.',
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
        $pajak = Pajak::find($id);
        if($pajak){
            if($pajak->delete()){
                return response()->json([
                    'message' => 'Pajak berhasil dihapus.',
                ]);
            }
            return response()->json([
                'error' => 'Hapus pajak gagal.',
            ]);
        }
        return response()->json([
            'error' => 'Pajak tidak ditemukan.',
        ]);
    }
}
