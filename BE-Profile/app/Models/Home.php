<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Home extends Model
{
    use HasFactory, HasUuids;

    protected $table = "home";

    protected $fillable = [
        'image',
        'title',
        'deskripsi',
    ];
}
