<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RencanaKegiatan extends Model
{
    use HasFactory, HasUuids, LogsActivity;

    protected $guarded = ['id', 'uuid'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'keterangan_status', 'nama_kegiatan', 'jenis_kegiatan'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Rencana Kegiatan {$eventName}");
    }

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
    const STATUS_DRAFT = 'draft';
    const STATUS_DIAJUKAN = 'diajukan';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_REVISI = 'revisi';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_SELESAI = 'selesai';

    // Jenis Kegiatan constants
    const JENIS_KONSERVASI = 'konservasi';
    const JENIS_USAHA_MASYARAKAT = 'usaha masyarakat';
    const JENIS_EDUKASI = 'edukasi';
    const JENIS_LAINNYA = 'lainnya';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
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

    /**
     * Get HTML badge based on status for clean Blade views.
     */
    public function getStatusBadgeAttribute(): string
    {
        $label = ucwords(str_replace('_', ' ', $this->status));

        return match ($this->status) {
            self::STATUS_DRAFT     => '<span style="background:#f1f3f5; color:#495057; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">' . $label . '</span>',
            self::STATUS_DIAJUKAN  => '<span style="background:#e8f0fe; color:#1a56db; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">' . $label . '</span>',
            self::STATUS_REVISI    => '<span style="background:#fff3cd; color:#856404; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">' . $label . '</span>',
            self::STATUS_DITOLAK   => '<span style="background:#fde8e8; color:#c81e1e; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">' . $label . '</span>',
            self::STATUS_DISETUJUI => '<span style="background:#def7ec; color:#03543f; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">' . $label . '</span>',
            self::STATUS_SELESAI   => '<span style="background:#e2e8f0; color:#334155; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">' . $label . '</span>',
            default                => '<span style="background:#f1f3f5; color:#495057; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500;">' . $label . '</span>',
        };
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

    /**
     * Decode JSON rincian_kebutuhan or return null if legacy string
     */
    public function getRincianKebutuhanItemsAttribute(): ?array
    {
        if (empty($this->rincian_kebutuhan)) {
            return [];
        }
        $decoded = json_decode($this->rincian_kebutuhan, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        return null;
    }

    /**
     * Get calculated grand total of rincian items if JSON, or 0
     */
    public function getGrandTotalRincianAttribute(): float
    {
        $items = $this->rincian_kebutuhan_items;
        if (!is_array($items)) {
            return 0;
        }
        $total = 0;
        foreach ($items as $item) {
            $total += (float) ($item['subtotal'] ?? 0);
        }
        return $total;
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
