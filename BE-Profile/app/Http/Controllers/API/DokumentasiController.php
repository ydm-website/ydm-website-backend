<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokumentasi;
use App\Http\Requests\DokumentasiRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DokumentasiController extends Controller
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
        $dokumentasi = Dokumentasi::all();

        return response()->json([
            "message" => "View all dokumentasi",
            "data" => $dokumentasi
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DokumentasiRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Dokumentasi::create($data);

        return response()->json([
            "message" => "Dokumentasi successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dokumentasi = Dokumentasi::with('album')->find($id);

        if(!$dokumentasi){
            return response()->json([
                "message" => "Dokumentasi with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $dokumentasi
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DokumentasiRequest $request, string $id)
    {
        $data = $request->validated();

        $dokumentasi = Dokumentasi::find($id);
        if(!$dokumentasi){
            return response()->json([
                "message" => "Dokumentasi with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($dokumentasi->image) {
                $publicId = pathinfo($dokumentasi->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $dokumentasi->update($data);

        return response()->json([
            "message" => "Dokumentasi with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokumentasi = Dokumentasi::find($id);

        if(!$dokumentasi){
            return response()->json([
                "message" => "Dokumentasi with ID $id is not found"
            ], 404);
        }

        if($dokumentasi->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($dokumentasi->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $dokumentasi->delete();

        return response()->json([
            "message" => "Dokumentasi successfully deleted"
        ], 200);
    }
}
