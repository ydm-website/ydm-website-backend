<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bidang;
use App\Http\Requests\BidangRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BidangController extends Controller
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
        $bidang = Bidang::with('list_detail')->get();

        return response()->json([
            "message" => "View all bidang",
            "data" => $bidang
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BidangRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Bidang::create($data);

        return response()->json([
            "message" => "Bidang successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bidang = Bidang::with('list_detail')->find($id);

        if(!$bidang){
            return response()->json([
                "message" => "Bidang with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $bidang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BidangRequest $request, string $id)
    {
        $data = $request->validated();

        $bidang = Bidang::find($id);
        if(!$bidang){
            return response()->json([
                "message" => "Bidang with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($bidang->image) {
                $publicId = pathinfo($bidang->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $bidang->update($data);

        return response()->json([
            "message" => "Bidang with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bidang = Bidang::find($id);

        if(!$bidang){
            return response()->json([
                "message" => "Bidang with ID $id is not found"
            ], 404);
        }

        if($bidang->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($bidang->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $bidang->delete();

        return response()->json([
            "message" => "Bidang successfully deleted"
        ], 200);
    }
}
