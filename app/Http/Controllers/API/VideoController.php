<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Http\Requests\VideoRequest;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only('store', 'update', 'destroy');
    }

    public function dashboardvideo()
    {
        $limitVideo = Video::orderBy('created_at', 'desc')->take(3)->get();
        return response ()->json([
            "message" => "View Limited Video",
            "data" => $limitVideo
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $video = Video::all();

        return response()->json([
            "message" => "View all Video",
            "data" => $video
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VideoRequest $request)
    {
        Video::create($request->all());

        return response()->json([
            "message" => "Video successfully added"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $video = Video::find($id);

        if(!$video){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data with ID : $id",
            "data" => $video
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VideoRequest $request, string $id)
    {
        $video = Video::find($id);

        if(!$video){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $validatedData = $request->validated();

        $video->update($validatedData);

        $video->save();

        return response()->json([
            "message" => "Successfully updated Video with ID : $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = Video::find($id);

        if(!$video) {
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $video->delete();
        return response()->json([
            "message" => "Video with ID $id successfully deleted"
        ]);
    }
}
