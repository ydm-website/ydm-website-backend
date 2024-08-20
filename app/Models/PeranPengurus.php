<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PeranPengurus extends Model
{
    use HasFactory, HasUuids;

    protected $table = "peran_pengurus";

    protected $fillable = [
        'name'
    ];

}
