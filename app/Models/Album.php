<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Album extends Model
{
    use HasFactory, HasUuids;

    protected $table = "album_kegiatan";

    protected $fillable = [
        'name',
        'image',
    ];

    public function list_dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'album_id');
    }
}
