<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Bidang extends Model
{
    use HasFactory, HasUuids;

    protected $table = "bidang";

    protected $fillable = [
        'name',
        'image'
    ];

    public function list_detail()
    {
        return $this->hasMany(DetailBidang::class, 'bidang_id');
    }
}
