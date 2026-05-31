<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ExpenseSplit — Pembagian pengeluaran antar anggota trip.
 *
 * @property int $id
 * @property int $expense_id
 * @property int $user_id
 * @property float $amount
 * @property bool $is_settled
 */
class ExpenseSplit extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'expense_id',
        'user_id',
        'amount',
        'is_settled',
    ];

    /**
     * Casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount'     => 'decimal:2',
            'is_settled' => 'boolean',
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    /**
     * Pengeluaran induk.
     */
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    /**
     * Pengguna yang menanggung bagian ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
