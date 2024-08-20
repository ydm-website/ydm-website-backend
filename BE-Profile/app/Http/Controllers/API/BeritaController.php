<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Http\Requests\BeritaRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BeritaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only('store', 'update', 'destroy');
    }

    public function dashboardberita()
    {
        $limitBerita = Berita::with('kategori')->orderBy('created_at', 'desc')->take(10)->get();
        return response ()->json([
            "message" => "View Limited Berita",
            "data" => $limitBerita
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $berita = Berita::with('kategori')->get();

        return response()->json([
            "message" => "View all berita",
            "data" => $berita
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BeritaRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Berita::create($data);

        return response()->json([
            "message" => "Berita successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $berita = Berita::with('kategori')->find($id);

        if(!$berita){
            return response()->json([
                "message" => "Berita with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $berita
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BeritaRequest $request, string $id)
    {
        $data = $request->validated();

        $berita = Berita::find($id);
        if(!$berita){
            return response()->json([
                "message" => "Berita with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($berita->image) {
                $publicId = pathinfo($berita->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $berita->update($data);

        return response()->json([
            "message" => "Berita with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $berita = Berita::find($id);

        if(!$berita){
            return response()->json([
                "message" => "Berita with ID $id is not found"
            ], 404);
        }

        if($berita->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($berita->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $berita->delete();

        return response()->json([
            "message" => "Berita successfully deleted"
        ], 200);
    }
}
