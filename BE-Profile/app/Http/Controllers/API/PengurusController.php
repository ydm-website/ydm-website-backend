<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengurus;
use App\Http\Requests\PengurusRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PengurusController extends Controller
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
        $pengurus = Pengurus::with('peran')->get();

        return response()->json([
            "message" => "View all pengurus",
            "data" => $pengurus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengurusRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Pengurus::create($data);

        return response()->json([
            "message" => "Pengurus successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengurus = Pengurus::with('peran')->find($id);

        if(!$pengurus){
            return response()->json([
                "message" => "Pengurus with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $pengurus
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PengurusRequest $request, string $id)
    {
        $data = $request->validated();

        $pengurus = Pengurus::find($id);
        if(!$pengurus){
            return response()->json([
                "message" => "Pengurus with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($pengurus->image) {
                $publicId = pathinfo($pengurus->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $pengurus->update($data);

        return response()->json([
            "message" => "Pengurus with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengurus = Pengurus::find($id);

        if(!$pengurus){
            return response()->json([
                "message" => "Pengurus with ID $id is not found"
            ], 404);
        }

        if($pengurus->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($pengurus->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $pengurus->delete();

        return response()->json([
            "message" => "Pengurus successfully deleted"
        ], 200);
    }
}
