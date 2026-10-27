<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPrediksi extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'detail_prediksi';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'prediksi_id',
        'label_periode',
        'urutan',
        'tipe',
        'jumlah_cup',
    ];

    /**
     * Tipe data casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prediksi_id' => 'integer',
        'urutan' => 'integer',
        'jumlah_cup' => 'integer',
    ];

    /**
     * Relasi ke parent prediksi.
     */
    public function prediksi(): BelongsTo
    {
        return $this->belongsTo(Prediksi::class, 'prediksi_id');
    }
}
