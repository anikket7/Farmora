<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'farmer_id',
        'category_id',
        'title',
        'description',
        'quantity',
        'unit',
        'price',
        'listing_type',
        'status',
        'harvest_date',
        'origin_location',
        'is_available',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'decimal:2',
            'harvest_date' => 'date',
            'is_available' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * @return HasOne<ProductImage, $this>
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * @return HasOne<BidSession, $this>
     */
    public function bidSession(): HasOne
    {
        return $this->hasOne(BidSession::class);
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isBiddable(): bool
    {
        return $this->listing_type === 'bid';
    }

    public function isBuyable(): bool
    {
        return $this->listing_type === 'buy';
    }
}
