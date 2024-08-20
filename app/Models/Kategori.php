<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kategori extends Model
{
    use HasFactory, HasUuids;

    protected $table = "kategori_berita";

    protected $fillable = [
        'name'
    ];

    public function list_berita()
    {
        return $this->hasMany(Berita::class, 'kategori_id');
    }
}
