<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LaporanKegiatan extends Model
{
    use HasUuid, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Laporan Kegiatan {$eventName}");
    }

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_DIAJUKAN = 'diajukan';
    const STATUS_REVISI = 'revisi';
    const STATUS_FINAL = 'final';


    protected $fillable = [
        'user_id',
        'rencana_kegiatan_id',
        'judul_kegiatan',
        'lokasi_kegiatan',
        'realisasi_tanggal_mulai',
        'realisasi_tanggal_selesai',
        'rangkaian_kegiatan',
        'target_peserta',
        'realisasi_peserta',
        'profil_peserta',
        'hasil_dicapai',
        'output_nyata',
        'dampak_awal',
        'kendala',
        'solusi',
        'evaluasi_rekomendasi',
        'foto_kegiatan',
        'daftar_hadir',
        'notulen',
        'materi',
        'berita_acara',
        'status',
        'catatan_evaluasi',
    ];

    protected $casts = [
        'foto_kegiatan' => 'array',
        'daftar_hadir' => 'array',
        'notulen' => 'array',
        'materi' => 'array',
        'berita_acara' => 'array',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the rencana kegiatan that owns the laporan.
     */
    public function rencanaKegiatan()
    {
        return $this->belongsTo(RencanaKegiatan::class, 'rencana_kegiatan_id', 'uuid');
    }

    /**
     * Get the user that created this laporan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if laporan can be created for the given rencana kegiatan.
     */
    public static function canCreateFor(RencanaKegiatan $rencanaKegiatan): bool
    {
        // Only allow laporan for disetujui rencana kegiatan
        if ($rencanaKegiatan->status !== RencanaKegiatan::STATUS_DISETUJUI) {
            return false;
        }

        // Check if laporan already exists
        return !static::where('rencana_kegiatan_id', $rencanaKegiatan->uuid)->exists();
    }

    /**
     * Get the status label for the rencana kegiatan.
     */
    public function getRencanaStatusLabel(): string
    {
        return $this->rencanaKegiatan ? 
            RencanaKegiatan::getStatusOptions()[$this->rencanaKegiatan->status] ?? 'Unknown' 
            : 'Unknown';
    }

    /**
     * Get status options for laporan kegiatan.
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_FINAL => 'Final',
        ];
    }


    /**
     * Check if laporan is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }
    
    /**
     * Check if laporan is tanggap darurat (laporan langsung).
     */
    public function isDarurat(): bool
    {
        return is_null($this->rencana_kegiatan_id);
    }

    /**
     * Check if laporan is final.
     */
    public function isFinal(): bool
    {
        return $this->status === self::STATUS_FINAL;
    }

    /**
     * Get formatted waktu mulai.
     */
    public function getFormattedWaktuMulai(): string
    {
        return $this->rencanaKegiatan && $this->rencanaKegiatan->waktu_mulai 
            ? \Carbon\Carbon::parse($this->rencanaKegiatan->waktu_mulai)->format('H:i') 
            : '-';
    }

    /**
     * Get formatted waktu selesai.
     */
    public function getFormattedWaktuSelesai(): string
    {
        return $this->rencanaKegiatan && $this->rencanaKegiatan->waktu_selesai 
            ? \Carbon\Carbon::parse($this->rencanaKegiatan->waktu_selesai)->format('H:i') 
            : '-';
    }

    /**
     * Get formatted realisasi tanggal pelaksanaan.
     */
    public function getFormattedRealisasiTanggalPelaksanaan(): string
    {
        if ($this->realisasi_tanggal_mulai && $this->realisasi_tanggal_selesai) {
            return \Carbon\Carbon::parse($this->realisasi_tanggal_mulai)->format('d F Y') . ' - ' . 
                   \Carbon\Carbon::parse($this->realisasi_tanggal_selesai)->format('d F Y');
        } elseif ($this->realisasi_tanggal_mulai) {
            return \Carbon\Carbon::parse($this->realisasi_tanggal_mulai)->format('d F Y');
        }
        return '-';
    }

    /**
     * Get formatted realisasi tanggal mulai.
     */
    public function getFormattedRealisasiTanggalMulai(): string
    {
        return $this->realisasi_tanggal_mulai 
            ? \Carbon\Carbon::parse($this->realisasi_tanggal_mulai)->format('d F Y') 
            : '-';
    }

    /**
     * Get formatted realisasi tanggal selesai.
     */
    public function getFormattedRealisasiTanggalSelesai(): string
    {
        return $this->realisasi_tanggal_selesai 
            ? \Carbon\Carbon::parse($this->realisasi_tanggal_selesai)->format('d F Y') 
            : '-';
    }

    /**
     * Get formatted waktu pelaksanaan (legacy support).
     */
    public function getFormattedWaktuPelaksanaan(): string
    {
        if ($this->rencanaKegiatan && $this->rencanaKegiatan->waktu_mulai && $this->rencanaKegiatan->waktu_selesai) {
            return \Carbon\Carbon::parse($this->rencanaKegiatan->waktu_mulai)->format('H:i') . ' - ' . 
                   \Carbon\Carbon::parse($this->rencanaKegiatan->waktu_selesai)->format('H:i');
        }
        return '-';
    }

    /**
     * Get HTML badge based on status for clean Blade views.
     */
    public function getStatusBadgeAttribute(): string
    {
        $label = ucwords(str_replace('_', ' ', $this->status));

        return match ($this->status) {
            self::STATUS_DRAFT     => '<span style="background:#f1f3f5; color:#495057; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500; display:inline-block;">' . $label . '</span>',
            self::STATUS_DIAJUKAN  => '<span style="background:#e8f0fe; color:#1a56db; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500; display:inline-block;">' . $label . '</span>',
            self::STATUS_REVISI    => '<span style="background:#fff3cd; color:#856404; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500; display:inline-block;">' . $label . '</span>',
            self::STATUS_FINAL     => '<span style="background:#def7ec; color:#03543f; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500; display:inline-block;">' . $label . '</span>',
            default                => '<span style="background:#f1f3f5; color:#495057; padding:2px 8px; border-radius:4px; font-size:0.78rem; font-weight:500; display:inline-block;">' . $label . '</span>',
        };
    }
}
