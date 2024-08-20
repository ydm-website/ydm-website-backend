<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeranPengurus;
use App\Http\Requests\PeranPengurusRequest;

class PeranPengurusController extends Controller
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
        $peran = PeranPengurus::all();

        return response()->json([
            "message" => "View all peran",
            "data" => $peran
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeranPengurusRequest $request)
    {
        PeranPengurus::create($request->all());

        return response()->json([
            "message" => "PeranPengurus successfully added"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peran = PeranPengurus::find($id);

        if(!$peran){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data with ID : $id",
            "data" => $peran
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeranPengurusRequest $request, string $id)
    {
        $peran = PeranPengurus::find($id);

        if(!$peran){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $validatedData = $request->validated();
    
        $peran->update($validatedData);

        $peran->save();

        return response()->json([
            "message" => "PeranPengurus with ID $id successfully updated",
            "data" => $peran
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $peran = PeranPengurus::find($id);

        if(!$peran){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $peran->delete();
        return response()->json([
            "message" => "PeranPengurus with ID $id successfully deleted"
        ]);
    }
}
