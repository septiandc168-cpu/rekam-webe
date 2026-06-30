<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RencanaKegiatan extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id', 'uuid'];

    protected $table = 'rencana_kegiatans';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nama_kegiatan',
        'jenis_kegiatan',
        'jenis_kegiatan_lainnya',
        'deskripsi',
        'tujuan',
        'lat',
        'lng',
        'desa',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'penanggung_jawab',
        'kelompok',
        'estimasi_peserta',
        'rincian_kebutuhan',
        'foto',
        'dokumen',
        'anggaran_kegiatan',
        'status',
        'keterangan_status',
    ];

    protected $casts = [
        'foto' => 'array',
        'dokumen' => 'array',
        'anggaran_kegiatan' => 'array',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

    // Status constants
    const STATUS_DIAJUKAN = 'diajukan';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_REVISI = 'revisi';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_SELESAI = 'selesai';
    const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    // Jenis Kegiatan constants
    const JENIS_KONSERVASI = 'konservasi';
    const JENIS_USAHA_MASYARAKAT = 'usaha masyarakat';
    const JENIS_EDUKASI = 'edukasi';
    const JENIS_LAINNYA = 'lainnya';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DIAJUKAN => 'Diajukan',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_REVISI => 'Revisi',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_SELESAI => 'Selesai',
        ];
    }

    public static function getJenisKegiatanOptions(): array
    {
        return [
            self::JENIS_KONSERVASI => 'Konservasi',
            self::JENIS_USAHA_MASYARAKAT => 'Usaha Masyarakat',
            self::JENIS_EDUKASI => 'Edukasi',
            self::JENIS_LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Get formatted jenis kegiatan label.
     */
    public function getJenisKegiatanLabel(): string
    {
        if ($this->jenis_kegiatan === self::JENIS_LAINNYA && $this->jenis_kegiatan_lainnya) {
            return $this->jenis_kegiatan_lainnya;
        }

        return self::getJenisKegiatanOptions()[$this->jenis_kegiatan] ?? 'Unknown';
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the laporan kegiatan associated with this rencana.
     */
    public function laporanKegiatan()
    {
        return $this->hasOne(LaporanKegiatan::class, 'rencana_kegiatan_id', 'uuid');
    }

    /**
     * Get the user that created this rencana.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this rencana has a laporan.
     */
    public function hasLaporan(): bool
    {
        return $this->laporanKegiatan()->exists();
    }

    /**
     * Check if laporan can be created for this rencana.
     */
    public function canCreateLaporan(): bool
    {
        return LaporanKegiatan::canCreateFor($this);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
