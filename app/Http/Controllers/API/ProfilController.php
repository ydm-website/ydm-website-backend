<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profil;
use App\Http\Requests\ProfilRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfilController extends Controller
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
        $profil = Profil::all();

        return response()->json([
            "message" => "View all profil",
            "data" => $profil
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfilRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Profil::create($data);

        return response()->json([
            "message" => "profil successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $profil = Profil::find($id);

        if(!$profil){
            return response()->json([
                "message" => "profil with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $profil
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfilRequest $request, string $id)
    {
        $data = $request->validated();

        $profil = Profil::find($id);
        if(!$profil){
            return response()->json([
                "message" => "profil with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($profil->image) {
                $publicId = pathinfo($profil->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $profil->update($data);

        return response()->json([
            "message" => "profil with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $profil = Profil::find($id);

        if(!$profil){
            return response()->json([
                "message" => "profil with ID $id is not found"
            ], 404);
        }

        if($profil->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($profil->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $profil->delete();

        return response()->json([
            "message" => "profil successfully deleted"
        ], 200);
    }
}
