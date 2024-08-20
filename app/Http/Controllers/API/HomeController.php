<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Home;
use App\Http\Requests\HomeRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class HomeController extends Controller
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
        $home = Home::all();

        return response()->json([
            "message" => "View all home",
            "data" => $home
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HomeRequest $request)
    {
        $data = $request->validated();

        // Jika file gambar diinput
        if ($request->hasFile('image')) {
            // Unggah gambar ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        Home::create($data);

        return response()->json([
            "message" => "home successfully added"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $home = Home::find($id);

        if(!$home){
            return response()->json([
                "message" => "home with ID $id is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Detail data with ID : $id",
            "data" => $home
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HomeRequest $request, string $id)
    {
        $data = $request->validated();

        $home = Home::find($id);
        if(!$home){
            return response()->json([
                "message" => "home with ID $id is not found"
            ], 404);
        }

        if ($request->hasFile('image')) {
            // Jika ada gambar lama, hapus dari Cloudinary
            if ($home->image) {
                $publicId = pathinfo($home->image, PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru ke Cloudinary
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            // Mengganti nilai request pada image menjadi URL gambar yang diunggah
            $data['image'] = $uploadedFileUrl;
        }

        $home->update($data);

        return response()->json([
            "message" => "home with ID $id successfully updated",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $home = Home::find($id);

        if(!$home){
            return response()->json([
                "message" => "home with ID $id is not found"
            ], 404);
        }

        if($home->image){
            // Hapus gambar dari Cloudinary
            $publicId = pathinfo($home->image, PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $home->delete();

        return response()->json([
            "message" => "home successfully deleted"
        ], 200);
    }
}
