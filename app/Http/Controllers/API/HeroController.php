<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hero;
use App\Http\Requests\HeroRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class HeroController extends Controller
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
        $hero = Hero::all();

        return response()->json([
            "message" => "View all hero",
            "data" => $hero
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HeroRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Hero::create($data);

        return response()->json([
            "message" => "Hero successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hero = Hero::find($id);

        if(!$hero){
            return response()->json([
                "message" => "Hero with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $hero
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HeroRequest $request, string $id)
    {
        $data = $request->validated();

        $hero = Hero::find($id);
        if(!$hero){
            return response()->json([
                "message" => "Hero with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($hero->image) {
                $publicId = pathinfo($hero->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $hero->update($data);

        return response()->json([
            "message" => "Hero with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hero = Hero::find($id);

        if(!$hero){
            return response()->json([
                "message" => "Hero with ID $id is not found"
            ], 404);
        }

        if($hero->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($hero->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $hero->delete();

        return response()->json([
            "message" => "Hero successfully deleted"
        ], 200);
    }
}
