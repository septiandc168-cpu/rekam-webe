<?php

namespace App\Http\Controllers;

use App\Models\LaporanKegiatan;
use App\Models\RencanaKegiatan;
use App\Models\User;
use App\Http\Requests\LaporanKegiatanRequest;
use App\Notifications\LaporanActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LaporanKegiatanController extends Controller
{
    /**
     * Helper: kirim notifikasi ke semua user ber-role admin
     */
    private function notifyAdmins($notification)
    {
        $admins = User::whereHas('role', function($query) {
            $query->where('role_name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify($notification);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->role->role_name === 'admin';
        
        // Filter data berdasarkan peran
        if ($isAdmin) {
            // Admin melihat data laporan yang aktif berproses (diajukan, revisi), mengecualikan draft dan final (final ada di History Realisasi)
            $query = LaporanKegiatan::with('rencanaKegiatan', 'user')
                ->whereNotIn('status', [LaporanKegiatan::STATUS_DRAFT, LaporanKegiatan::STATUS_FINAL]);
                
            // Apply filters if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('bulan')) {
                $query->whereMonth('created_at', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('created_at', $request->tahun);
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            $laporans = $query->orderBy('updated_at', 'desc')->get();
            
            // Get all anggota users for filter
            $users = User::whereHas('role', function($q) {
                $q->where('role_name', 'anggota');
            })->orderBy('name')->get();
        } else {
            // Anggota hanya melihat datanya sendiri yang berstatus draft, diajukan, revisi (mengecualikan final)
            $query = LaporanKegiatan::with('rencanaKegiatan', 'user')
                ->where('user_id', $user->id)
                ->where('status', '!=', LaporanKegiatan::STATUS_FINAL);
                
            // Apply filters if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('bulan')) {
                $query->whereMonth('created_at', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('created_at', $request->tahun);
            }

            $laporans = $query->orderBy('updated_at', 'desc')->get();
            $users = null;
        }
        
        // Konfigurasi SweetAlert untuk delete dengan warna danger
        $confirm = [
            'title' => 'Hapus Laporan Kegiatan?',
            'text' => 'Apakah Anda yakin ingin menghapus laporan kegiatan ini? Data yang dihapus tidak dapat dikembalikan.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#dc3545',
            'cancelButtonColor' => '#6c757d',
            'confirmButtonText' => 'Ya, Hapus',
            'cancelButtonText' => 'Batal'
        ];

        session()->flash('alert.delete', json_encode($confirm, JSON_UNESCAPED_SLASHES));

        // === Rencana Kegiatan Disetujui ===
        // Hanya untuk anggota: rencana milik sendiri yang berstatus disetujui dan BELUM memilik laporan kegiatan
        $rencanaDisetujui = collect();
        if (!$isAdmin) {
            $rencanaDisetujui = RencanaKegiatan::with('user')
                ->where('user_id', $user->id)
                ->where('status', RencanaKegiatan::STATUS_DISETUJUI)
                ->doesntHave('laporanKegiatan')
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        return view('laporan_kegiatan.index', compact('laporans', 'users', 'rencanaDisetujui'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check authorization
        $this->authorize('create', LaporanKegiatan::class);
        
        $isLaporanLangsung = $request->get('jenis') === 'langsung';
        
        if ($isLaporanLangsung) {
            $rencanaKegiatan = null;
            return view('laporan_kegiatan.create', compact('rencanaKegiatan', 'isLaporanLangsung'));
        }

        $rencanaKegiatanId = $request->get('rencana_kegiatan_id');

        if (!$rencanaKegiatanId) {
            return redirect()->route('rencana_kegiatan.index')
                ->with('error', 'Rencana kegiatan tidak ditemukan');
        }

        $rencanaKegiatan = RencanaKegiatan::where('uuid', $rencanaKegiatanId)->firstOrFail();

        // Check if rencana kegiatan is ready for reporting (disetujui)
        if ($rencanaKegiatan->status !== RencanaKegiatan::STATUS_DISETUJUI) {
            return redirect()->route('rencana_kegiatan.show', $rencanaKegiatan)
                ->with('error', 'Laporan hanya bisa dibuat untuk rencana kegiatan dengan status "Disetujui"');
        }

        // Check if laporan already exists
        if ($rencanaKegiatan->hasLaporan()) {
            return redirect()->route('laporan_kegiatan.show', $rencanaKegiatan->laporanKegiatan)
                ->with('error', 'Laporan untuk rencana kegiatan ini sudah ada');
        }

        return view('laporan_kegiatan.create', compact('rencanaKegiatan', 'isLaporanLangsung'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LaporanKegiatanRequest $request)
    {
        // Check authorization
        $this->authorize('create', LaporanKegiatan::class);
        
        $user = auth()->user();
        $isAnggota = $user->role->role_name === 'anggota';
        $isLaporanLangsung = $request->input('is_laporan_langsung') == '1';
        $rencanaKegiatan = null;

        if (!$isLaporanLangsung) {
            $rencanaKegiatan = RencanaKegiatan::findOrFail($request->rencana_kegiatan_id);

            // PROTEKSI 1: Tolak jika status rencana belum 'disetujui'
            if ($rencanaKegiatan->status !== \App\Models\RencanaKegiatan::STATUS_DISETUJUI) {
                throw ValidationException::withMessages([
                    'rencana_kegiatan_id' => 'Pelanggaran keamanan: Laporan kegiatan HANYA dapat dibuat untuk rencana kegiatan yang berstatus "Disetujui".'
                ]);
            }

            // PROTEKSI 2: Tolak jika rencana ini sudah memiliki laporan (Mencegah duplikasi data)
            if ($rencanaKegiatan->laporanKegiatan()->exists()) {
                 throw ValidationException::withMessages([
                    'rencana_kegiatan_id' => 'Pelanggaran keamanan: Laporan untuk rencana kegiatan ini sudah pernah dibuat sebelumnya.'
                ]);
            }
        }

        // Handle file uploads
        $fotoKegiatanPaths = [];
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/foto_kegiatan', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $fotoKegiatanPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $daftarHadirPaths = [];
        if ($request->hasFile('daftar_hadir')) {
            foreach ($request->file('daftar_hadir') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/daftar_hadir', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $daftarHadirPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $notulenPaths = [];
        if ($request->hasFile('notulen')) {
            foreach ($request->file('notulen') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/notulen', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $notulenPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $materiPaths = [];
        if ($request->hasFile('materi')) {
            foreach ($request->file('materi') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/materi', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $materiPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $beritaAcaraPaths = [];
        if ($request->hasFile('berita_acara')) {
            foreach ($request->file('berita_acara') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/berita_acara', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $beritaAcaraPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        // Determine status based on action
        $action = $request->input('action', 'diajukan');
        $status = ($action === 'draft') ? LaporanKegiatan::STATUS_DRAFT : LaporanKegiatan::STATUS_DIAJUKAN;

        $laporan = LaporanKegiatan::create([
            'user_id' => auth()->id(),
            'rencana_kegiatan_id' => $request->rencana_kegiatan_id,
            'judul_kegiatan' => $request->judul_kegiatan,
            'lokasi_kegiatan' => $request->lokasi_kegiatan,
            'realisasi_tanggal_mulai' => $request->realisasi_tanggal_mulai,
            'realisasi_tanggal_selesai' => $request->realisasi_tanggal_selesai,
            'rangkaian_kegiatan' => $request->rangkaian_kegiatan,
            'target_peserta' => $rencanaKegiatan ? $rencanaKegiatan->estimasi_peserta : 0,
            'realisasi_peserta' => $request->realisasi_peserta,
            'profil_peserta' => $request->profil_peserta,
            'hasil_dicapai' => $request->hasil_dicapai,
            'output_nyata' => $request->output_nyata,
            'dampak_awal' => $request->dampak_awal,
            'kendala' => $request->kendala,
            'solusi' => $request->solusi,
            'evaluasi_rekomendasi' => $request->evaluasi_rekomendasi,
            'foto_kegiatan' => !empty($fotoKegiatanPaths) ? $fotoKegiatanPaths : null,
            'daftar_hadir' => !empty($daftarHadirPaths) ? $daftarHadirPaths : null,
            'notulen' => !empty($notulenPaths) ? $notulenPaths : null,
            'materi' => !empty($materiPaths) ? $materiPaths : null,
            'berita_acara' => !empty($beritaAcaraPaths) ? $beritaAcaraPaths : null,
            'status' => $status,
        ]);

        // Kirim notifikasi ke admin jika anggota yang mengajukan
        if ($isAnggota && $status === \App\Models\LaporanKegiatan::STATUS_DIAJUKAN) {
            if ($rencanaKegiatan) {
                $rencanaKegiatan->update([
                    'status' => \App\Models\RencanaKegiatan::STATUS_SELESAI,
                    'keterangan_status' => 'Kegiatan telah selesai dilaksanakan di lapangan dan laporan telah diajukan.'
                ]);
            }

            $notification = new LaporanActivityNotification(
                $laporan->uuid,
                $rencanaKegiatan ? $rencanaKegiatan->uuid : null,
                $rencanaKegiatan ? $rencanaKegiatan->nama_kegiatan : ($request->judul_kegiatan ?? 'Laporan Darurat'),
                $rencanaKegiatan ? $rencanaKegiatan->nama_kegiatan : ($request->judul_kegiatan ?? 'Laporan Darurat'),
                'diajukan',
                $user->name,
                null,
                now()
            );
            $this->notifyAdmins($notification);
        }

        $message = $status === \App\Models\LaporanKegiatan::STATUS_DRAFT 
            ? 'Draft laporan kegiatan berhasil disimpan!'
            : 'Laporan kegiatan berhasil diajukan!';
            
        toast($message, 'success');
        return redirect()->route('laporan_kegiatan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaporanKegiatan $laporanKegiatan)
    {
        // Check authorization
        $this->authorize('view', $laporanKegiatan);
        
        // Security Proteksi: Anggota tidak boleh melihat draft orang lain
        if (auth()->user()->role->role_name === 'anggota' && $laporanKegiatan->user_id != auth()->id()) {
            if (in_array($laporanKegiatan->status, ['draft', 'revisi'])) {
                abort(403, 'Akses Ditolak. Anda tidak bisa melihat draf milik pengguna lain.');
            }
        }
        
        $laporanKegiatan->load('rencanaKegiatan');
        return view('laporan_kegiatan.show', compact('laporanKegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanKegiatan $laporanKegiatan)
    {
        // Check authorization
        $this->authorize('update', $laporanKegiatan);
        
        // Transparansi Terkontrol: Cegah bypass edit data orang lain
        if ($laporanKegiatan->user_id != auth()->id() && auth()->user()->role->role_name !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengubah dokumen milik orang lain.');
        }
        
        // PROTEKSI: Tidak boleh mengedit jika status diajukan atau final
        if (in_array($laporanKegiatan->status, [LaporanKegiatan::STATUS_DIAJUKAN, LaporanKegiatan::STATUS_FINAL])) {
            abort(403, 'Dokumen terkunci dan tidak dapat diubah.');
        }
        
        $laporanKegiatan->load('rencanaKegiatan');
        return view('laporan_kegiatan.edit', compact('laporanKegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LaporanKegiatanRequest $request, LaporanKegiatan $laporanKegiatan)
    {
        // Check authorization
        $this->authorize('update', $laporanKegiatan);

        // PROTEKSI: Tidak boleh mengedit/update jika status diajukan atau final
        if (in_array($laporanKegiatan->status, [LaporanKegiatan::STATUS_DIAJUKAN, LaporanKegiatan::STATUS_FINAL])) {
            abort(403, 'Dokumen terkunci dan tidak dapat diperbarui.');
        }
        $user = auth()->user();
        $isAnggota = $user->role->role_name === 'anggota';

        // Handle file removals
        $currentFotoKegiatan = $laporanKegiatan->foto_kegiatan ?? [];
        $removeFotoKegiatan = $request->input('remove_foto_kegiatan', []);

        if (!empty($removeFotoKegiatan)) {
            foreach ($removeFotoKegiatan as $pathToRemove) {
                // Handle both old format (string) and new format (array)
                $found = false;
                foreach ($currentFotoKegiatan as $key => $fileData) {
                    $filePath = is_array($fileData) ? $fileData['path'] : $fileData;
                    if ($filePath === $pathToRemove) {
                        Storage::disk('public')->delete($filePath);
                        unset($currentFotoKegiatan[$key]);
                        $found = true;
                        break;
                    }
                }
                // Re-index array if found
                if ($found) {
                    $currentFotoKegiatan = array_values($currentFotoKegiatan);
                }
            }
        }

        $currentDaftarHadir = $laporanKegiatan->daftar_hadir ?? [];
        $removeDaftarHadir = $request->input('remove_daftar_hadir', []);

        if (!empty($removeDaftarHadir)) {
            foreach ($removeDaftarHadir as $pathToRemove) {
                // Handle both old format (string) and new format (array)
                $found = false;
                foreach ($currentDaftarHadir as $key => $fileData) {
                    $filePath = is_array($fileData) ? $fileData['path'] : $fileData;
                    if ($filePath === $pathToRemove) {
                        Storage::disk('public')->delete($filePath);
                        unset($currentDaftarHadir[$key]);
                        $found = true;
                        break;
                    }
                }
                // Re-index array if found
                if ($found) {
                    $currentDaftarHadir = array_values($currentDaftarHadir);
                }
            }
        }

        $currentNotulen = $laporanKegiatan->notulen ?? [];
        $removeNotulen = $request->input('remove_notulen', []);

        if (!empty($removeNotulen)) {
            foreach ($removeNotulen as $pathToRemove) {
                // Handle both old format (string) and new format (array)
                $found = false;
                foreach ($currentNotulen as $key => $fileData) {
                    $filePath = is_array($fileData) ? $fileData['path'] : $fileData;
                    if ($filePath === $pathToRemove) {
                        Storage::disk('public')->delete($filePath);
                        unset($currentNotulen[$key]);
                        $found = true;
                        break;
                    }
                }
                // Re-index array if found
                if ($found) {
                    $currentNotulen = array_values($currentNotulen);
                }
            }
        }

        $currentMateri = $laporanKegiatan->materi ?? [];
        $removeMateri = $request->input('remove_materi', []);

        if (!empty($removeMateri)) {
            foreach ($removeMateri as $pathToRemove) {
                // Handle both old format (string) and new format (array)
                $found = false;
                foreach ($currentMateri as $key => $fileData) {
                    $filePath = is_array($fileData) ? $fileData['path'] : $fileData;
                    if ($filePath === $pathToRemove) {
                        Storage::disk('public')->delete($filePath);
                        unset($currentMateri[$key]);
                        $found = true;
                        break;
                    }
                }
                // Re-index array if found
                if ($found) {
                    $currentMateri = array_values($currentMateri);
                }
            }
        }

        $currentBeritaAcara = $laporanKegiatan->berita_acara ?? [];
        $removeBeritaAcara = $request->input('remove_berita_acara', []);

        if (!empty($removeBeritaAcara)) {
            foreach ($removeBeritaAcara as $pathToRemove) {
                // Handle both old format (string) and new format (array)
                $found = false;
                foreach ($currentBeritaAcara as $key => $fileData) {
                    $filePath = is_array($fileData) ? $fileData['path'] : $fileData;
                    if ($filePath === $pathToRemove) {
                        Storage::disk('public')->delete($filePath);
                        unset($currentBeritaAcara[$key]);
                        $found = true;
                        break;
                    }
                }
                // Re-index array if found
                if ($found) {
                    $currentBeritaAcara = array_values($currentBeritaAcara);
                }
            }
        }

        // Handle new file uploads
        $newFotoKegiatanPaths = [];
        if ($request->hasFile('foto_kegiatan')) {
            foreach ($request->file('foto_kegiatan') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/foto_kegiatan', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $newFotoKegiatanPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $newDaftarHadirPaths = [];
        if ($request->hasFile('daftar_hadir')) {
            foreach ($request->file('daftar_hadir') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/daftar_hadir', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $newDaftarHadirPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $newNotulenPaths = [];
        if ($request->hasFile('notulen')) {
            foreach ($request->file('notulen') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/notulen', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $newNotulenPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $newMateriPaths = [];
        if ($request->hasFile('materi')) {
            foreach ($request->file('materi') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/materi', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $newMateriPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $newBeritaAcaraPaths = [];
        if ($request->hasFile('berita_acara')) {
            foreach ($request->file('berita_acara') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);
                
                // Simpan file dengan nama asli
                $path = $file->storeAs('laporan_kegiatan/berita_acara', $fileName, 'public');
                
                // Simpan array dengan path dan nama asli
                $newBeritaAcaraPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        // Merge existing and new files
        $finalFotoKegiatan = array_merge($currentFotoKegiatan, $newFotoKegiatanPaths);
        $finalDaftarHadir = array_merge($currentDaftarHadir, $newDaftarHadirPaths);
        $finalNotulen = array_merge($currentNotulen, $newNotulenPaths);
        $finalMateri = array_merge($currentMateri, $newMateriPaths);
        $finalBeritaAcara = array_merge($currentBeritaAcara, $newBeritaAcaraPaths);

        // Determine status based on action
        $action = $request->input('action');
        $currentStatus = $laporanKegiatan->status;
        
        $status = match(true) {
            $currentStatus === LaporanKegiatan::STATUS_DRAFT && $action === 'draft' => LaporanKegiatan::STATUS_DRAFT,
            $currentStatus === LaporanKegiatan::STATUS_DRAFT && $action === 'diajukan' => LaporanKegiatan::STATUS_DIAJUKAN,
            $currentStatus === LaporanKegiatan::STATUS_REVISI && $action === 'draft' => LaporanKegiatan::STATUS_REVISI,
            $currentStatus === LaporanKegiatan::STATUS_REVISI && $action === 'diajukan' => LaporanKegiatan::STATUS_DIAJUKAN,
            default => LaporanKegiatan::STATUS_DIAJUKAN,
        };

        $updateData = [
            'realisasi_tanggal_mulai' => $request->realisasi_tanggal_mulai,
            'realisasi_tanggal_selesai' => $request->realisasi_tanggal_selesai,
            'rangkaian_kegiatan' => $request->rangkaian_kegiatan,
            'realisasi_peserta' => $request->realisasi_peserta,
            'profil_peserta' => $request->profil_peserta,
            'hasil_dicapai' => $request->hasil_dicapai,
            'output_nyata' => $request->output_nyata,
            'dampak_awal' => $request->dampak_awal,
            'kendala' => $request->kendala,
            'solusi' => $request->solusi,
            'evaluasi_rekomendasi' => $request->evaluasi_rekomendasi,
            'foto_kegiatan' => !empty($finalFotoKegiatan) ? array_values($finalFotoKegiatan) : null,
            'daftar_hadir' => !empty($finalDaftarHadir) ? array_values($finalDaftarHadir) : null,
            'notulen' => !empty($finalNotulen) ? array_values($finalNotulen) : null,
            'materi' => !empty($finalMateri) ? array_values($finalMateri) : null,
            'berita_acara' => !empty($finalBeritaAcara) ? array_values($finalBeritaAcara) : null,
            'status' => $status,
        ];

        if ($laporanKegiatan->isDarurat()) {
            $updateData['judul_kegiatan'] = $request->judul_kegiatan;
            $updateData['lokasi_kegiatan'] = $request->lokasi_kegiatan;
        }

        $laporanKegiatan->update($updateData);

        // Kirim notifikasi ke admin jika anggota yang mengajukan ulang
        if ($isAnggota && $status === \App\Models\LaporanKegiatan::STATUS_DIAJUKAN) {
            $rencanaKegiatan = $laporanKegiatan->rencanaKegiatan;
            if ($rencanaKegiatan) {
                $rencanaKegiatan->update([
                    'status' => \App\Models\RencanaKegiatan::STATUS_SELESAI,
                    'keterangan_status' => 'Kegiatan telah selesai dilaksanakan di lapangan dan laporan telah diajukan.'
                ]);
            }

            $notification = new LaporanActivityNotification(
                $laporanKegiatan->uuid,
                $rencanaKegiatan ? $rencanaKegiatan->uuid : null,
                $rencanaKegiatan ? $rencanaKegiatan->nama_kegiatan : ($laporanKegiatan->judul_kegiatan ?? 'Laporan Darurat'),
                $rencanaKegiatan ? $rencanaKegiatan->nama_kegiatan : ($laporanKegiatan->judul_kegiatan ?? 'Laporan Darurat'),
                'diajukan',
                $user->name,
                null,
                now()
            );
            $this->notifyAdmins($notification);
        }

        $message = match(true) {
            $currentStatus === LaporanKegiatan::STATUS_DRAFT && $action === 'draft' => 'Draft laporan kegiatan berhasil diperbarui!',
            $currentStatus === LaporanKegiatan::STATUS_DRAFT && $action === 'diajukan' => 'Laporan kegiatan berhasil diajukan!',
            $currentStatus === LaporanKegiatan::STATUS_REVISI && $action === 'draft' => 'Draft revisi laporan berhasil diperbarui!',
            $currentStatus === LaporanKegiatan::STATUS_REVISI && $action === 'diajukan' => 'Laporan kegiatan berhasil direvisi dan diajukan ulang!',
            default => 'Laporan kegiatan berhasil diperbarui!'
        };

        toast($message, 'success');
        return redirect()->route('laporan_kegiatan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanKegiatan $laporanKegiatan)
    {
        // Check authorization
        $this->authorize('delete', $laporanKegiatan);
        
        // Transparansi Terkontrol: Cegah bypass delete data orang lain
        if ($laporanKegiatan->user_id != auth()->id() && auth()->user()->role->role_name !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus dokumen milik orang lain.');
        }

        // PROTEKSI: Hanya boleh menghapus jika status masih draft
        if ($laporanKegiatan->status !== LaporanKegiatan::STATUS_DRAFT) {
            abort(403, 'Dokumen terkunci dan tidak dapat dihapus.');
        }
        $user = auth()->user();
        $isAnggota = $user->role->role_name === 'anggota';

        // Simpan data untuk notifikasi sebelum dihapus
        $laporanUuid = $laporanKegiatan->uuid;
        $rencanaKegiatan = $laporanKegiatan->rencanaKegiatan;
        $rencanaUuid = $rencanaKegiatan ? $rencanaKegiatan->uuid : null;
        $rencanaNama = $rencanaKegiatan ? $rencanaKegiatan->nama_kegiatan : ($laporanKegiatan->judul_kegiatan ?? 'Laporan Darurat');

        // Delete all files with support for both old and new format
        if (!empty($laporanKegiatan->foto_kegiatan)) {
            foreach ($laporanKegiatan->foto_kegiatan as $fileData) {
                $path = is_array($fileData) ? $fileData['path'] : $fileData;
                Storage::disk('public')->delete($path);
            }
        }

        if (!empty($laporanKegiatan->daftar_hadir)) {
            foreach ($laporanKegiatan->daftar_hadir as $fileData) {
                $path = is_array($fileData) ? $fileData['path'] : $fileData;
                Storage::disk('public')->delete($path);
            }
        }

        if (!empty($laporanKegiatan->notulen)) {
            foreach ($laporanKegiatan->notulen as $fileData) {
                $path = is_array($fileData) ? $fileData['path'] : $fileData;
                Storage::disk('public')->delete($path);
            }
        }

        if (!empty($laporanKegiatan->materi)) {
            foreach ($laporanKegiatan->materi as $fileData) {
                $path = is_array($fileData) ? $fileData['path'] : $fileData;
                Storage::disk('public')->delete($path);
            }
        }

        if (!empty($laporanKegiatan->berita_acara)) {
            foreach ($laporanKegiatan->berita_acara as $fileData) {
                $path = is_array($fileData) ? $fileData['path'] : $fileData;
                Storage::disk('public')->delete($path);
            }
        }

        $laporanKegiatan->delete();

        // Kirim notifikasi ke admin jika anggota yang menghapus
        if ($isAnggota) {
            $notification = new LaporanActivityNotification(
                $laporanUuid,
                $rencanaUuid,
                $rencanaNama,
                $rencanaNama,
                'dihapus',
                $user->name,
                null,
                now()
            );
            $this->notifyAdmins($notification);
        }

        toast('Laporan kegiatan berhasil dihapus.', 'success');
        return redirect()->route('laporan_kegiatan.index');
    }

    /**
     * Print the specified laporan.
     */
    public function print(LaporanKegiatan $laporanKegiatan)
    {
        $this->authorize('print', $laporanKegiatan);

        $laporanKegiatan->load('rencanaKegiatan');

        return view('laporan_kegiatan.print', compact('laporanKegiatan'));
    }

    public function terimaLaporan(Request $request, $id)
    {
        if (auth()->user()->role->role_name !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $laporan = LaporanKegiatan::with('rencanaKegiatan')->where('uuid', $id)->firstOrFail();

        // Validasi transisi status: hanya laporan berstatus 'diajukan' yang bisa diterima
        if ($laporan->status !== LaporanKegiatan::STATUS_DIAJUKAN) {
            toast('Laporan tidak dapat diterima karena statusnya bukan "Diajukan".', 'error');
            return redirect()->back();
        }
        
        try {
            DB::transaction(function () use ($laporan) {
                // 1. Finalisasi Laporan
                $laporan->update([
                    'status' => \App\Models\LaporanKegiatan::STATUS_FINAL,
                ]);

                // 2. Selesaikan Rencana Kegiatan
                if ($laporan->rencanaKegiatan) {
                    $laporan->rencanaKegiatan->update([
                        'status' => \App\Models\RencanaKegiatan::STATUS_SELESAI,
                        'keterangan_status' => 'Kegiatan telah diselesaikan berdasarkan laporan final.'
                    ]);
                }
            });

            // Kirim notifikasi ke pembuat laporan
            if ($laporan->user_id !== auth()->id()) {
                $pembuat = \App\Models\User::find($laporan->user_id);
                if ($pembuat) {
                    $pembuat->notify(new \App\Notifications\StatusLaporanNotification(
                        $laporan->uuid,
                        $laporan->rencanaKegiatan->nama_kegiatan ?? 'Kegiatan',
                        \App\Models\LaporanKegiatan::STATUS_FINAL,
                        'Laporan telah diterima dan kegiatan selesai.',
                        now()
                    ));
                }
            }

            toast('Laporan diterima dan kegiatan dinyatakan Selesai secara otomatis.', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Gagal menyetujui laporan: ' . $e->getMessage());
            toast('Terjadi kesalahan sistem saat menyetujui laporan.', 'error');
            return redirect()->back();
        }
    }

    public function revisiLaporan(Request $request, $id)
    {
        if (auth()->user()->role->role_name !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $request->validate(['catatan_evaluasi' => 'required|string']);

        $laporan = LaporanKegiatan::where('uuid', $id)->firstOrFail();
        
        $laporan->update([
            'status' => \App\Models\LaporanKegiatan::STATUS_REVISI,
            'catatan_evaluasi' => $request->catatan_evaluasi,
        ]);

        // Kirim notifikasi ke pembuat laporan
        if ($laporan->user_id !== auth()->id()) {
            $pembuat = \App\Models\User::find($laporan->user_id);
            if ($pembuat) {
                $pembuat->notify(new \App\Notifications\StatusLaporanNotification(
                    $laporan->uuid,
                    $laporan->rencanaKegiatan->nama_kegiatan ?? 'Kegiatan',
                    \App\Models\LaporanKegiatan::STATUS_REVISI,
                    $request->catatan_evaluasi,
                    now()
                ));
            }
        }

        toast('Permintaan revisi berhasil dikirim ke anggota.', 'success');
        return redirect()->back();
    }

    /**
     * Helper to get list of missing mandatory fields for a LaporanKegiatan.
     */
    private function getMissingFields(LaporanKegiatan $laporan): array
    {
        $missing = [];

        if ($laporan->isDarurat()) {
            if (empty(trim($laporan->judul_kegiatan ?? ''))) {
                $missing[] = 'Judul Kegiatan';
            }
            if (empty(trim($laporan->lokasi_kegiatan ?? ''))) {
                $missing[] = 'Lokasi Kegiatan';
            }
        }
        if (empty($laporan->realisasi_tanggal_mulai)) {
            $missing[] = 'Realisasi Tanggal Mulai';
        }
        if (empty($laporan->realisasi_tanggal_selesai)) {
            $missing[] = 'Realisasi Tanggal Selesai';
        }
        if (empty(trim(strip_tags($laporan->rangkaian_kegiatan ?? '')))) {
            $missing[] = 'Rangkaian Kegiatan';
        }
        if (empty($laporan->realisasi_peserta)) {
            $missing[] = 'Realisasi Jumlah Peserta';
        }
        if (empty(trim(strip_tags($laporan->profil_peserta ?? '')))) {
            $missing[] = 'Profil Peserta';
        }
        if (empty(trim(strip_tags($laporan->hasil_dicapai ?? '')))) {
            $missing[] = 'Hasil yang Dicapai';
        }
        if (empty(trim(strip_tags($laporan->output_nyata ?? '')))) {
            $missing[] = 'Output Nyata';
        }
        if (empty(trim(strip_tags($laporan->dampak_awal ?? '')))) {
            $missing[] = 'Dampak Awal';
        }
        if (empty(trim(strip_tags($laporan->kendala ?? '')))) {
            $missing[] = 'Kendala yang Dihadapi';
        }
        if (empty(trim(strip_tags($laporan->solusi ?? '')))) {
            $missing[] = 'Solusi yang Dilakukan';
        }
        if (empty(trim(strip_tags($laporan->evaluasi_rekomendasi ?? '')))) {
            $missing[] = 'Catatan Evaluasi & Rekomendasi';
        }

        return $missing;
    }

    /**
     * Anggota action: Ajukan Laporan Kegiatan (Direct)
     */
    public function ajukanLaporan(Request $request, $id)
    {
        $laporanKegiatan = LaporanKegiatan::where('uuid', $id)->orWhere('id', $id)->firstOrFail();
        
        // Authorization check: User can only submit their own draft/revisi
        if ($laporanKegiatan->user_id !== auth()->id() || !in_array($laporanKegiatan->status, [LaporanKegiatan::STATUS_DRAFT, LaporanKegiatan::STATUS_REVISI])) {
            abort(403, 'Unauthorized action.');
        }

        // Validate required fields before submitting draft
        $missingFields = $this->getMissingFields($laporanKegiatan);
        if (!empty($missingFields)) {
            $pesan = 'Laporan kegiatan tidak dapat diajukan karena data wajib berikut belum terisi: ' . implode(', ', $missingFields) . '.';
            toast($pesan, 'error');
            return redirect()->back()->with('error', $pesan);
        }

        $laporanKegiatan->update([
            'status' => LaporanKegiatan::STATUS_DIAJUKAN,
            'catatan_evaluasi' => null // Reset catatan evaluasi
        ]);
        
        // Kirim notifikasi ke admin
        $rencanaKegiatan = $laporanKegiatan->rencanaKegiatan;
        $notification = new LaporanActivityNotification(
            $laporanKegiatan->uuid,
            $rencanaKegiatan ? $rencanaKegiatan->uuid : null,
            null,
            $rencanaKegiatan ? $rencanaKegiatan->nama_kegiatan : ($laporanKegiatan->judul_kegiatan ?? 'Laporan Darurat'),
            'diajukan',
            auth()->user()->name,
            null,
            now()
        );
        $this->notifyAdmins($notification);
        
        toast('Laporan kegiatan berhasil diajukan!', 'success');
        return redirect()->back();
    }
}
