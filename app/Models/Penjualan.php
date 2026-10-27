<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'penjualan';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nomor_transaksi',
        'tanggal',
        'menu_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'status',
        'user_id',
    ];

    /**
     * Tipe data casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'integer',
        'harga_satuan' => 'integer',
        'total_harga' => 'integer',
    ];

    /**
     * Relasi ke menu yang terjual.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Relasi ke user pencatat penjualan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
