<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'bid_session_id',
        'consumer_id',
        'amount',
        'placed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'placed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<BidSession, $this>
     */
    public function bidSession(): BelongsTo
    {
        return $this->belongsTo(BidSession::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consumer_id');
    }
}
