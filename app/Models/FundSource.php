<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundSource extends Model
{
    /** @use HasFactory<\Database\Factories\FundSourceFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
