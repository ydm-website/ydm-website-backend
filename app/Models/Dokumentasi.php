<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Dokumentasi extends Model
{
    use HasFactory, HasUuids;

    protected $table = "dokumentasi";

    protected $fillable = [
        'title',
        'image',
        'image_caption',
        'album_id',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }
}
