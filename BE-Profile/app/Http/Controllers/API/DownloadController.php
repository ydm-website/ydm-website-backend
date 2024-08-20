<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Download;
use App\Http\Requests\DownloadRequest;

class DownloadController extends Controller
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
        $download = Download::all();

        return response()->json([
            "message" => "View all download",
            "data" => $download
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DownloadRequest $request)
    {
        Download::create($request->all());

        return response()->json([
            "message" => "Download successfully added"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $download = Download::find($id);

        if(!$download){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data with ID : $id",
            "data" => $download
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DownloadRequest $request, string $id)
    {
        $download = Download::find($id);

        if(!$download){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $validatedData = $request->validated();

        $download->update($validatedData);

        $download->save();

        return response()->json([
            "message" => "Successfully updated download with ID : $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $download = Download::find($id);

        if(!$download) {
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $download->delete();
        return response()->json([
            "message" => "Download with ID $id successfully deleted"
        ]);
    }
}
