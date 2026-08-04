<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaKegiatan;
use App\Models\LaporanKegiatan;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->role->role_name === 'admin';

        // === Widget Counts ===
        if ($isAdmin) {
            $totalRencana = RencanaKegiatan::count();
            $totalDiajukan = RencanaKegiatan::where('status', RencanaKegiatan::STATUS_DIAJUKAN)->count();
            $totalDisetujui = RencanaKegiatan::where('status', RencanaKegiatan::STATUS_DISETUJUI)->count();
            $totalLaporan = LaporanKegiatan::count();
            $totalUsers = User::count();
        } else {
            $totalRencana = RencanaKegiatan::where('user_id', $user->id)->count();
            $totalDiajukan = RencanaKegiatan::where('user_id', $user->id)->where('status', RencanaKegiatan::STATUS_DIAJUKAN)->count();
            $totalDisetujui = RencanaKegiatan::where('user_id', $user->id)->where('status', RencanaKegiatan::STATUS_DISETUJUI)->count();
            $totalLaporan = LaporanKegiatan::where('user_id', $user->id)->count();
            $totalUsers = 0;
        }

        // === Chart Data: Kegiatan per bulan (12 bulan terakhir) ===
        $chartLabels = [];
        $chartValues = [];
        $now = Carbon::now();

        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M Y');

            $query = RencanaKegiatan::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month);

            if (!$isAdmin) {
                $query->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereNotIn('status', [\App\Models\RencanaKegiatan::STATUS_DRAFT, \App\Models\RencanaKegiatan::STATUS_REVISI]);
                });
            }

            $chartValues[] = $query->count();
        }

        // === Tabel: 5 Rencana Terbaru ===
        $rencanaQuery = RencanaKegiatan::with('user')->latest();
        if (!$isAdmin) {
            $rencanaQuery->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNotIn('status', [\App\Models\RencanaKegiatan::STATUS_DRAFT, \App\Models\RencanaKegiatan::STATUS_REVISI]);
            });
        }
        $rencanaTerbaru = $rencanaQuery->take(5)->get();

        // === Map Data (Hanya kegiatan yang punya lat/lng dan berstatus disetujui) ===
        $mapQuery = RencanaKegiatan::with('user')->select('id', 'user_id', 'uuid', 'nama_kegiatan', 'lat', 'lng', 'status', 'desa', 'tanggal_mulai', 'penanggung_jawab')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->where('status', RencanaKegiatan::STATUS_DISETUJUI);
        $mapData = $mapQuery->get()->map(function($item) {
            $formattedDate = $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') : '-';
            $person = $item->penanggung_jawab ?? ($item->user ? $item->user->name : 'Unknown');
            return [
                'uuid' => $item->uuid,
                'nama' => $item->nama_kegiatan,
                'lat' => $item->lat,
                'lng' => $item->lng,
                'status' => $item->status,
                'desa' => $item->desa ?? '-',
                'tanggal' => $formattedDate,
                'person' => $person,
                'url' => route('rencana_kegiatan.show', $item->uuid)
            ];
        });

        return view('home', compact(
            'isAdmin',
            'totalRencana',
            'totalDiajukan',
            'totalDisetujui',
            'totalLaporan',
            'totalUsers',
            'chartLabels',
            'chartValues',
            'rencanaTerbaru',
            'mapData'
        ));
    }

    /**
     * Get calendar events for FullCalendar
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function events()
    {
        $user = auth()->user();
        
        // Filter data berdasarkan role
        if ($user->role->role_name === 'admin') {
            // Supervisor melihat semua kegiatan kecuali draft
            $rencanaKegiatan = RencanaKegiatan::where('status', '!=', RencanaKegiatan::STATUS_DRAFT)->get();
        } else {
            // Anggota melihat kegiatannya sendiri (termasuk draft) ATAU data anggota lain (kecuali draft & revisi)
            $rencanaKegiatan = RencanaKegiatan::with('user')->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereNotIn('status', [\App\Models\RencanaKegiatan::STATUS_DRAFT, \App\Models\RencanaKegiatan::STATUS_REVISI]);
                })
                ->get();
        }

        $events = [];
        
        foreach ($rencanaKegiatan as $rencana) {
            // Tentukan warna berdasarkan status
            $backgroundColor = $this->getStatusColor($rencana->status);
            $textColor = $this->getStatusTextColor($rencana->status);
            $borderColor = $textColor; // Border color matches text color
            
            // Gunakan Carbon untuk parsing yang benar
            $startDate = \Carbon\Carbon::parse($rencana->tanggal_mulai);
            $endDate = \Carbon\Carbon::parse($rencana->tanggal_selesai);
            
            // Jika ada waktu_mulai dan waktu_selesai, buat event per hari
            if ($rencana->waktu_mulai && $rencana->waktu_selesai) {
                $startTime = \Carbon\Carbon::parse($rencana->waktu_mulai)->format('H:i');
                $endTime = \Carbon\Carbon::parse($rencana->waktu_selesai)->format('H:i');
                
                // Buat event untuk setiap hari dalam rentang tanggal
                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $dateStr = $currentDate->format('Y-m-d');
                    $startDateTime = $dateStr . 'T' . $startTime . ':00';
                    $endDateTime = $dateStr . 'T' . $endTime . ':00';
                    
                    $events[] = [
                        'id' => $rencana->uuid . '_' . $dateStr, // Tambahkan suffix unik per hari
                        'title' => ucwords(str_replace('_', ' ', $rencana->status)),
                        'start' => $startDateTime,
                        'end' => $endDateTime,
                        'url' => route('rencana_kegiatan.show', $rencana->uuid),
                        'backgroundColor' => $backgroundColor,
                        'borderColor' => $borderColor,
                        'textColor' => $textColor,
                        'allDay' => false,
                        'extendedProps' => [
                            'nama_kegiatan' => $rencana->nama_kegiatan,
                            'description' => $rencana->deskripsi ?? ''
                        ]
                    ];
                    
                    $currentDate->addDay();
                }
            } else {
                // Jika tidak ada waktu, buat all-day event untuk rentang tanggal
                $events[] = [
                    'id' => $rencana->uuid,
                    'title' => ucwords(str_replace('_', ' ', $rencana->status)),
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                    'url' => route('rencana_kegiatan.show', $rencana->uuid),
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'textColor' => $textColor,
                    'allDay' => true,
                    'extendedProps' => [
                        'nama_kegiatan' => $rencana->nama_kegiatan,
                        'description' => $rencana->deskripsi ?? ''
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    /**
     * Get color based on status
     *
     * @param string $status
     * @return string
     */
    private function getStatusColor($status)
    {
        switch ($status) {
            case RencanaKegiatan::STATUS_DIAJUKAN:
                return '#e8f0fe';
            case RencanaKegiatan::STATUS_DISETUJUI:
                return '#def7ec';
            case RencanaKegiatan::STATUS_REVISI:
                return '#fff3cd';
            case RencanaKegiatan::STATUS_DITOLAK:
                return '#fde8e8';
            case RencanaKegiatan::STATUS_SELESAI:
                return '#e2e8f0';
            default:
                return '#f1f3f5';
        }
    }

    /**
     * Get text color based on status
     *
     * @param string $status
     * @return string
     */
    private function getStatusTextColor($status)
    {
        switch ($status) {
            case RencanaKegiatan::STATUS_DIAJUKAN:
                return '#1a56db';
            case RencanaKegiatan::STATUS_DISETUJUI:
                return '#03543f';
            case RencanaKegiatan::STATUS_REVISI:
                return '#856404';
            case RencanaKegiatan::STATUS_DITOLAK:
                return '#c81e1e';
            case RencanaKegiatan::STATUS_SELESAI:
                return '#334155';
            default:
                return '#495057';
        }
    }
}
