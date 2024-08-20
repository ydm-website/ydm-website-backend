<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailBidang;
use App\Http\Requests\DetailBidangRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DetailBidangController extends Controller
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
        $detailBidang = DetailBidang::all();

        return response()->json([
            "message" => "View all Detail Bidang",
            "data" => $detailBidang
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DetailBidangRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        DetailBidang::create($data);

        return response()->json([
            "message" => "Detail Bidang successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detailBidang = DetailBidang::with('bidang')->find($id);

        if(!$detailBidang){
            return response()->json([
                "message" => "Detail Bidang with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $detailBidang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DetailBidangRequest $request, string $id)
    {
        $data = $request->validated();

        $detailBidang = DetailBidang::find($id);
        if(!$detailBidang){
            return response()->json([
                "message" => "Detail Bidang with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($detailBidang->image) {
                $publicId = pathinfo($detailBidang->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $detailBidang->update($data);

        return response()->json([
            "message" => "Detail Bidang with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detailBidang = DetailBidang::find($id);

        if(!$detailBidang){
            return response()->json([
                "message" => "Detail Bidang with ID $id is not found"
            ], 404);
        }

        if($detailBidang->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($detailBidang->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $detailBidang->delete();

        return response()->json([
            "message" => "Detail Bidang successfully deleted"
        ], 200);
    }
}
