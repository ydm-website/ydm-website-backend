<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DetailBidang extends Model
{
    use HasFactory, HasUuids;
    
    protected $table = "detail_bidang";

    protected $fillable = [
        'name',
        'deskripsi',
        'image',
        'bidang_id'
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
}
