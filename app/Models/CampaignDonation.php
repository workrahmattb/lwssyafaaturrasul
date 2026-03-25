<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trx_id',
        'campaign_id',
        'donatur_name',
        'phone',
        'amount',
        'payment_method_id',
        'proof_of_transfer',
        'status',
        'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($donation) {
            if ($donation->proof_of_transfer) {
                \Storage::disk('public')->delete($donation->proof_of_transfer);
            }
        });
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
