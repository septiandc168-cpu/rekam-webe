<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaKegiatan;

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
        return view('home');
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
        if ($user->role->role_name === 'supervisor') {
            // Supervisor melihat semua kegiatan
            $rencanaKegiatan = RencanaKegiatan::all();
        } else {
            // Admin hanya melihat kegiatannya sendiri
            $rencanaKegiatan = RencanaKegiatan::where('user_id', $user->id)->get();
        }

        $events = [];
        
        foreach ($rencanaKegiatan as $rencana) {
            // Tentukan warna berdasarkan status
            $backgroundColor = $this->getStatusColor($rencana->status);
            
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
                        'title' => $rencana->nama_kegiatan,
                        'start' => $startDateTime,
                        'end' => $endDateTime,
                        'url' => route('rencana_kegiatan.show', $rencana->uuid),
                        'backgroundColor' => $backgroundColor,
                        'borderColor' => $backgroundColor,
                        'textColor' => '#ffffff',
                        'allDay' => false,
                    ];
                    
                    $currentDate->addDay();
                }
            } else {
                // Jika tidak ada waktu, buat all-day event untuk rentang tanggal
                $events[] = [
                    'id' => $rencana->uuid,
                    'title' => $rencana->nama_kegiatan,
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                    'url' => route('rencana_kegiatan.show', $rencana->uuid),
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $backgroundColor,
                    'textColor' => '#ffffff',
                    'allDay' => true,
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
                return '#007bff'; // primary
            case RencanaKegiatan::STATUS_REVISI:
                return '#ffc107'; // warning
            case RencanaKegiatan::STATUS_DITOLAK:
                return '#dc3545'; // danger
            case RencanaKegiatan::STATUS_DISETUJUI:
                return '#28a745'; // success
            case RencanaKegiatan::STATUS_SELESAI:
                return '#6c757d'; // secondary
            default:
                return '#6c757d'; // abu-abu
        }
    }
}
