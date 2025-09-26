<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundSourceTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_fund_source_id',
        'to_fund_source_id',
        'amount',
        'fee',
        'description',
        'transfer_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fromFundSource(): BelongsTo
    {
        return $this->belongsTo(FundSource::class, 'from_fund_source_id');
    }

    public function toFundSource(): BelongsTo
    {
        return $this->belongsTo(FundSource::class, 'to_fund_source_id');
    }
}
