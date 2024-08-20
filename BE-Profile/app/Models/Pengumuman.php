<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pengumuman extends Model
{
    use HasFactory, HasUuids;

    protected $table = "pengumuman";

    protected $fillable = [
        'title',
        'content',
        'file',
        'status_id'
    ];

}
