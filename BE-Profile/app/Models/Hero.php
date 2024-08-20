<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Hero extends Model
{
    use HasFactory, HasUuids;

    protected $table = "hero";

    protected $fillable = [
        'image',
        'title',
        'deskripsi',
    ];

}
