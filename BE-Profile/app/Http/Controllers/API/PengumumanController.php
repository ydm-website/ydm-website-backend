<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Http\Requests\PengumumanRequest;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PengumumanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isAdmin'])->only('store', 'update', 'destroy');
    }

    public function dashboardpengumuman()
    {
        $limitPengumuman = Pengumuman::orderBy('created_at', 'desc')->take(5)->get();
        return response ()->json([
            "message" => "View Limited Pengumuman",
            "data" => $limitPengumuman
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengumuman = Pengumuman::orderBy('created_at', 'desc')->get();

        return response()->json([
            "message" => "View all pengumuman",
            "data" => $pengumuman
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengumumanRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $upload = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'pengumuman',
                'resource_type' => 'raw' // 'raw' is used for non-image files
            ]);
            $data['file'] = $upload->getSecurePath();
        }

        Pengumuman::create($data);

        return response()->json([
            "message" => "Pengumuman successfully added"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengumuman = Pengumuman::find($id);

        if(!$pengumuman){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data with ID : $id",
            "data" => $pengumuman
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PengumumanRequest $request, string $id)
    {
        $pengumuman = Pengumuman::find($id);

        if(!$pengumuman){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $validatedData = $request->validated();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $upload = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'pengumuman',
                'resource_type' => 'raw'
            ]);
            $validatedData['file'] = $upload->getSecurePath();
        }

        $pengumuman->update($validatedData);

        return response()->json([
            "message" => "Successfully updated Pengumuman with ID : $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengumuman = Pengumuman::find($id);

        if (!$pengumuman) {
            return response()->json([
                "message" => "ID not found"
            ], 404);
        }

        if ($pengumuman->file) {
            $fileUrl = $pengumuman->file;
            
            $parsedUrl = parse_url($fileUrl, PHP_URL_PATH);
            $publicId = trim($parsedUrl, '/');
            $publicId = preg_replace('/^.*\/raw\/upload\/[^\/]+\/(.*)$/', '$1', $publicId);

            try {
                $result = Cloudinary::destroy($publicId, [
                    'folder' => 'pengumuman',
                    'resource_type' => 'raw' // Ensure this matches the type used during upload
                ]);

                if ($result['result'] === 'ok') {
                    $pengumuman->delete();
                    
                    return response()->json([
                        "message" => "Pengumuman with ID $id and file successfully deleted"
                    ]);
                } else {
                    return response()->json([
                        "message" => "Failed to delete file from Cloudinary",
                        "error" => $result
                    ], 500);
                }
            } catch (\Exception $e) {
                return response()->json([
                    "message" => "Error deleting file from Cloudinary",
                    "error" => $e->getMessage()
                ], 500);
            }
        } else {
            $pengumuman->delete();
            return response()->json([
                "message" => "Pengumuman with ID $id successfully deleted without file"
            ]);
        }
    }

}
