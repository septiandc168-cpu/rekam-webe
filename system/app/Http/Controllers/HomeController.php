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
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->role_name === 'admin';

        // === Widget Counts ===
        if ($isAdmin) {
            // Admin melihat rencana aktif (diajukan, disetujui, revisi, ditolak) & laporan aktif (diajukan, revisi)
            $totalRencana   = RencanaKegiatan::whereNotIn('status', [RencanaKegiatan::STATUS_DRAFT, RencanaKegiatan::STATUS_SELESAI])->count();
            $totalDiajukan  = RencanaKegiatan::where('status', RencanaKegiatan::STATUS_DIAJUKAN)->count();
            $totalDisetujui = RencanaKegiatan::where('status', RencanaKegiatan::STATUS_DISETUJUI)->count();
            $totalLaporan   = LaporanKegiatan::whereNotIn('status', [LaporanKegiatan::STATUS_DRAFT, LaporanKegiatan::STATUS_FINAL])->count();
            $totalUsers     = User::whereHas('role', fn($q) => $q->where('role_name', 'anggota'))->count();
        } else {
            // Anggota melihat rencana miliknya yang belum disetujui/selesai & laporan yang belum final
            $totalRencana   = RencanaKegiatan::where('user_id', $user->id)
                ->whereNotIn('status', [RencanaKegiatan::STATUS_DISETUJUI, RencanaKegiatan::STATUS_SELESAI])->count();
            $totalDiajukan  = RencanaKegiatan::where('user_id', $user->id)->where('status', RencanaKegiatan::STATUS_DIAJUKAN)->count();
            $totalDisetujui = RencanaKegiatan::where('user_id', $user->id)->where('status', RencanaKegiatan::STATUS_DISETUJUI)->count();
            $totalLaporan   = LaporanKegiatan::where('user_id', $user->id)
                ->whereNotIn('status', [LaporanKegiatan::STATUS_DRAFT, LaporanKegiatan::STATUS_FINAL])->count();
            $totalUsers     = 0;
        }

        // === Filter Tahun Grafik ===
        $currentYear = Carbon::now()->year;
        $selectedYear = (int) $request->get('year', $currentYear);
        if ($selectedYear < 2000 || $selectedYear > 2100) {
            $selectedYear = $currentYear;
        }

        // Ambil daftar tahun unik dari data kegiatan
        $yearsRencana = RencanaKegiatan::selectRaw('YEAR(tanggal_mulai) as yr')
            ->whereNotNull('tanggal_mulai')
            ->distinct()
            ->pluck('yr')
            ->toArray();

        $yearsLaporan = LaporanKegiatan::selectRaw('YEAR(realisasi_tanggal_mulai) as yr')
            ->whereNotNull('realisasi_tanggal_mulai')
            ->distinct()
            ->pluck('yr')
            ->toArray();

        $availableYears = array_unique(array_filter(array_merge(
            $yearsRencana,
            $yearsLaporan,
            [$currentYear - 1, $currentYear, $currentYear + 1]
        )));
        rsort($availableYears);

        // === Chart Data: Kegiatan Terlaksana per Bulan (Januari - Desember) ===
        $chartLabels          = [];
        $chartValues          = [];
        $chartRencanaSelesai  = [];
        $chartLaporanLangsung = [];

        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agt',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        for ($m = 1; $m <= 12; $m++) {
            $chartLabels[] = $namaBulan[$m];

            // 1. Rencana Kegiatan yang telah selesai dilaksanakan
            $querySelesai = RencanaKegiatan::whereYear('tanggal_mulai', $selectedYear)
                ->whereMonth('tanggal_mulai', $m)
                ->where('status', RencanaKegiatan::STATUS_SELESAI);

            // 2. Laporan Langsung yang telah dilaporkan (status diajukan atau final)
            $queryLaporanLangsung = LaporanKegiatan::whereNull('rencana_kegiatan_id')
                ->whereIn('status', [
                    LaporanKegiatan::STATUS_DIAJUKAN,
                    LaporanKegiatan::STATUS_FINAL
                ])
                ->where(function ($q) use ($selectedYear, $m) {
                    $q->whereYear('realisasi_tanggal_mulai', $selectedYear)
                      ->whereMonth('realisasi_tanggal_mulai', $m)
                      ->orWhere(function ($q2) use ($selectedYear, $m) {
                          $q2->whereNull('realisasi_tanggal_mulai')
                             ->whereYear('created_at', $selectedYear)
                             ->whereMonth('created_at', $m);
                      });
                });

            if (!$isAdmin) {
                $querySelesai->where('user_id', $user->id);
                $queryLaporanLangsung->where('user_id', $user->id);
            }

            $countRencanaSelesai  = $querySelesai->count();
            $countLaporanLangsung = $queryLaporanLangsung->count();
            $totalBulan           = $countRencanaSelesai + $countLaporanLangsung;

            $chartRencanaSelesai[]  = $countRencanaSelesai;
            $chartLaporanLangsung[] = $countLaporanLangsung;
            $chartValues[]          = $totalBulan;
        }

        $totalKegiatanTahunIni = array_sum($chartValues);

        // === Tabel: 5 Rencana Terbaru ===
        $rencanaQuery = RencanaKegiatan::with('user')->latest();
        if (!$isAdmin) {
            $rencanaQuery->where('user_id', $user->id);
        }
        $rencanaTerbaru = $rencanaQuery->take(5)->get();

        // === Map Data (Hanya kegiatan yang punya lat/lng dan berstatus disetujui) ===
        $mapQuery = RencanaKegiatan::with('user')->select('id', 'user_id', 'uuid', 'nama_kegiatan', 'lat', 'lng', 'status', 'desa', 'tanggal_mulai', 'penanggung_jawab')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->where('status', RencanaKegiatan::STATUS_DISETUJUI);
        
        if (!$isAdmin) {
            $mapQuery->where('user_id', $user->id);
        }

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
            'chartRencanaSelesai',
            'chartLaporanLangsung',
            'totalKegiatanTahunIni',
            'selectedYear',
            'availableYears',
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
        $isAdmin = $user && $user->role && $user->role->role_name === 'admin';

        // Kalender hanya menampilkan rencana kegiatan berstatus DISETUJUI (scoped by user for anggota)
        $query = RencanaKegiatan::with('user')
            ->where('status', RencanaKegiatan::STATUS_DISETUJUI);

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $rencanaKegiatan = $query->get();

        $dateGrouped = [];

        foreach ($rencanaKegiatan as $rencana) {
            $startDate = \Carbon\Carbon::parse($rencana->tanggal_mulai);
            $endDate   = \Carbon\Carbon::parse($rencana->tanggal_selesai);

            $formattedMulai   = $startDate->translatedFormat('d M Y');
            $formattedSelesai = $endDate->translatedFormat('d M Y');

            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $dateStr = $currentDate->format('Y-m-d');

                if (!isset($dateGrouped[$dateStr])) {
                    $dateGrouped[$dateStr] = [];
                }

                $dateGrouped[$dateStr][] = [
                    'uuid'            => $rencana->uuid,
                    'nama_kegiatan'   => $rencana->nama_kegiatan,
                    'penanggung_jawab'=> $rencana->penanggung_jawab ?? ($rencana->user ? $rencana->user->name : '-'),
                    'desa'            => $rencana->desa ?? '-',
                    'jenis'           => $rencana->getJenisKegiatanLabel(),
                    'tanggal_mulai'   => $formattedMulai,
                    'tanggal_selesai' => $formattedSelesai,
                    'estimasi_peserta'=> $rencana->estimasi_peserta ?? '-',
                    'url'             => route('rencana_kegiatan.show', $rencana->uuid),
                ];

                $currentDate->addDay();
            }
        }

        $events = [];
        foreach ($dateGrouped as $dateStr => $items) {
            $count = count($items);
            $events[] = [
                'id'              => 'date_' . $dateStr,
                'title'           => $count . ' Kegiatan',
                'start'           => $dateStr,
                'allDay'          => true,
                'backgroundColor' => '#e6f0fa',
                'borderColor'     => '#001f3f',
                'textColor'       => '#001f3f',
                'extendedProps'   => [
                    'count'          => $count,
                    'date_formatted' => \Carbon\Carbon::parse($dateStr)->translatedFormat('d F Y'),
                    'items'          => $items,
                ]
            ];
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
