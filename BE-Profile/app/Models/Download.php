<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Download extends Model
{
    use HasFactory, HasUuids;

    protected $table = "download";

    protected $fillable = [
        'title',
        'url'
    ];

}
