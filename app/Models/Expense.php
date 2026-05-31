<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Expense — Pengeluaran dalam trip.
 *
 * Mata uang: IDR (Rupiah).
 *
 * @property int $id
 * @property int $trip_id
 * @property int $paid_by
 * @property string $title
 * @property float $amount
 * @property string $category
 * @property string $split_type
 * @property string|null $receipt_image
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $expense_date
 */
class Expense extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'trip_id',
        'paid_by',
        'title',
        'amount',
        'category',
        'split_type',
        'receipt_image',
        'notes',
        'expense_date',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Trip terkait.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Pengguna yang membayar pengeluaran ini.
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Pembagian pengeluaran.
     */
    public function splits(): HasMany
    {
        return $this->hasMany(ExpenseSplit::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Accessors                                                          */
    /* ------------------------------------------------------------------ */

    /**
     * Format jumlah sebagai Rupiah (contoh: "Rp 1.000.000").
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->amount, 0, ',', '.');
    }

    /**
     * Ikon emoji berdasarkan kategori pengeluaran.
     */
    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'transportasi' => '🚗',
            'penginapan'   => '🏨',
            'makanan'      => '🍽️',
            'belanja'      => '🛍️',
            'hiburan'      => '🎭',
            'tiket'        => '🎫',
            'kesehatan'    => '🏥',
            'komunikasi'   => '📱',
            default        => '💰',
        };
    }
}
