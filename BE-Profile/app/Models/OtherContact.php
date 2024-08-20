<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OtherContact extends Model
{
    use HasFactory, HasUuids;

    protected $table = "other_contact";

    protected $fillable = [
        'instansi',
        'address',
        'call',
        'email',
        'website',
    ];
}
