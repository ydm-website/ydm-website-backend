<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Http\Requests\KategoriRequest;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only('store', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();

        return response()->json([
            "message" => "View all kategori",
            "data" => $kategori
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KategoriRequest $request)
    {
        Kategori::create($request->all());

        return response()->json([
            "message" => "Kategori successfully added"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kategori = Kategori::with('list_berita')->find($id);

        if(!$kategori){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data with ID : $id",
            "data" => $kategori
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KategoriRequest $request, string $id)
    {
        $kategori = Kategori::find($id);

        if(!$kategori){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $validatedData = $request->validated();

        $kategori->update($validatedData);

        $kategori->save();

        return response()->json([
            "message" => "Successfully updated Kategori with ID : $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::find($id);

        if(!$kategori) {
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $kategori->delete();
        return response()->json([
            "message" => "Kategori with ID $id successfully deleted"
        ]);
    }
}
