<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BidSession extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'start_price',
        'min_increment',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_price' => 'decimal:2',
            'min_increment' => 'decimal:2',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return HasMany<Bid, $this>
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function highestBid(): ?Bid
    {
        return $this->bids()->orderByDesc('amount')->first();
    }

    public function currentPrice(): float
    {
        $highest = $this->highestBid();

        return $highest ? (float) $highest->amount : (float) $this->start_price;
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_time->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->end_time->isPast();
    }

    public function isEnded(): bool
    {
        return $this->status === 'completed' || $this->status === 'cancelled' || $this->isExpired();
    }

    public function closeSession(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $this->status = 'completed';
        $this->save();

        $highestBid = $this->highestBid();
        if ($highestBid) {
            $product = $this->product;
            
            Order::create([
                'product_id' => $this->product_id,
                'consumer_id' => $highestBid->consumer_id,
                'quantity' => $product->quantity,
                'total_price' => $highestBid->amount,
                'delivery_address' => $highestBid->consumer->location ?? 'To be updated by winner',
                'contact_phone' => $highestBid->consumer->phone ?? '0000000000',
                'payment_method' => 'cod',
                'status' => 'pending',
            ]);

            // Update product status to sold
            $product->update(['status' => 'sold']);
        } else {
            // No bids, set product status back to active
            $this->product->update(['status' => 'active']);
        }

        return true;
    }

    public function cancelSession(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $this->status = 'cancelled';
        $this->save();

        // Revert product back to active
        $this->product->update(['status' => 'active']);

        return true;
    }

    public static function checkAndCloseExpired(): void
    {
        $expiredSessions = self::where('status', 'active')
            ->where('end_time', '<=', now())
            ->get();

        foreach ($expiredSessions as $session) {
            $session->closeSession();
        }
    }
}
