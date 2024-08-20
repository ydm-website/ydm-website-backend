<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kontak;
use App\Http\Requests\KontakRequest;

class KontakController extends Controller
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
        $kontak = Kontak::all();

        return response()->json([
            "message" => "View all kontak",
            "data" => $kontak
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KontakRequest $request)
    {
        Kontak::create($request->all());

        return response()->json([
            "message" => "kontak successfully added"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kontak = Kontak::find($id);

        if(!$kontak){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data with ID : $id",
            "data" => $kontak
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KontakRequest $request, string $id)
    {
        $kontak = Kontak::find($id);

        if(!$kontak){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $validatedData = $request->validated();

        $kontak->update($validatedData);

        $kontak->save();

        return response()->json([
            "message" => "Successfully updated kontak with ID : $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kontak = Kontak::find($id);

        if(!$kontak) {
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $kontak->delete();
        return response()->json([
            "message" => "Kontak with ID $id successfully deleted"
        ]);
    }
}
