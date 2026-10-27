<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prediksi extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'prediksi';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tanggal_prediksi',
        'rentang_minggu',
        'periode_mulai',
        'periode_selesai',
        'total_prediksi_cup',
        'metode',
        'catatan',
        'user_id',
    ];

    /**
     * Tipe data casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_prediksi' => 'date',
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'rentang_minggu' => 'integer',
        'total_prediksi_cup' => 'integer',
    ];

    /**
     * Relasi ke rincian prediksi (historis dan masa depan).
     */
    public function detail(): HasMany
    {
        return $this->hasMany(DetailPrediksi::class, 'prediksi_id')->orderBy('urutan');
    }

    /**
     * Relasi ke user pencatat/penghitung.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
