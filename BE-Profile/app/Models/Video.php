<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Video extends Model
{
    use HasFactory, HasUuids;

    protected $table = "video";

    protected $fillable = [
        'title',
        'url'
    ];

}
