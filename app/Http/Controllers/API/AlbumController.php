<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use App\Http\Requests\AlbumRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AlbumController extends Controller
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
        $album = Album::all();

        return response()->json([
            "message" => "View all album",
            "data" => $album
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlbumRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Album::create($data);

        return response()->json([
            "message" => "Album successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $album = Album::with('list_dokumentasi')->find($id);

        if(!$album){
            return response()->json([
                "message" => "Album with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $album
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlbumRequest $request, string $id)
    {
        $data = $request->validated();

        $album = Album::find($id);
        if(!$album){
            return response()->json([
                "message" => "Album with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($album->image) {
                $publicId = pathinfo($album->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $album->update($data);

        return response()->json([
            "message" => "Album with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $album = Album::find($id);

        if(!$album){
            return response()->json([
                "message" => "Album with ID $id is not found"
            ], 404);
        }

        if($album->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($album->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $album->delete();

        return response()->json([
            "message" => "Album successfully deleted"
        ], 200);
    }
}
