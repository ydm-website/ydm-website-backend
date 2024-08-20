<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pengurus extends Model
{
    use HasFactory, HasUuids;

    protected $table = "pengurus";

    protected $fillable = [
        'name',
        'image',
        'jabatan',
        'peran_id'
    ];

    public function peran()
    {
        return $this->belongsTo(PeranPengurus::class, 'peran_id');
    }
}
