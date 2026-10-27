<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_menu',
        'harga',
        'kategori',
        'deskripsi',
        'is_aktif',
    ];

    /**
     * Tipe data casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'integer',
        'is_aktif' => 'boolean',
    ];

    /**
     * Relasi ke transaksi penjualan menu ini.
     */
    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'menu_id');
    }
}
