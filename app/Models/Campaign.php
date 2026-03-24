<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'target_amount',
        'raised_amount',
        'start_date',
        'end_date',
        'status',
        'image',
        'donor_count',
        'is_featured',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = Str::slug($campaign->title);
            }
        });
    }

    public function donations(): HasMany
    {
        return $this->hasMany(CampaignDonation::class);
    }

    public function activeDonations(): HasMany
    {
        return $this->hasMany(CampaignDonation::class)->where('status', 'approved');
    }

    /**
     * Get total raised amount from approved donations only
     */
    public function getTotalApprovedAmountAttribute()
    {
        return $this->activeDonations()->sum('amount');
    }

    /**
     * Get total donor count from approved donations only
     */
    public function getTotalApprovedDonorsAttribute()
    {
        return $this->activeDonations()->count();
    }

    public function getProgressAttribute()
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        // Use total_approved_amount for progress calculation
        return min(100, ($this->total_approved_amount / $this->target_amount) * 100);
    }

    public function isActive()
    {
        return $this->status === 'active' 
            && (!$this->end_date || $this->end_date->isFuture());
    }
}
