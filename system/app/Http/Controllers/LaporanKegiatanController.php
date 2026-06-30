<?php

namespace App\Http\Controllers;

use App\Models\LaporanKegiatan;
use App\Models\RencanaKegiatan;
use App\Models\User;
use App\Http\Requests\LaporanKegiatanRequest;
use App\Notifications\LaporanActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LaporanKegiatanController extends Controller
{
    /**
     * Helper function to send notifications to all supervisors
     */
    private function notifySupervisors($notification)
    {
        $supervisors = User::whereHas('role', function($query) {
            $query->where('role_name', 'supervisor');
        })->get();

        foreach ($supervisors as $supervisor) {
            $supervisor->notify($notification);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $isSupervisor = $user->role->role_name === 'supervisor';
        
        // Filter data berdasarkan peran
        if ($isSupervisor) {
            // Supervisor melihat semua data
            $laporans = LaporanKegiatan::with('rencanaKegiatan', 'user')
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            // Admin hanya melihat datanya sendiri
            $laporans = LaporanKegiatan::with('rencanaKegiatan', 'user')
                ->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->get();
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

        return view('laporan_kegiatan.index', compact('laporans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check authorization
        $this->authorize('create', LaporanKegiatan::class);
        
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

        return view('laporan_kegiatan.create', compact('rencanaKegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LaporanKegiatanRequest $request)
    {
        // Check authorization
        $this->authorize('create', LaporanKegiatan::class);
        
        $user = auth()->user();
        $isAdmin = $user->role->role_name === 'admin';
        
        $rencanaKegiatan = RencanaKegiatan::findOrFail($request->rencana_kegiatan_id);

        // Double check if laporan can be created
        if (!LaporanKegiatan::canCreateFor($rencanaKegiatan)) {
            throw ValidationException::withMessages([
                'rencana_kegiatan_id' => 'Laporan tidak dapat dibuat untuk rencana kegiatan ini. ' .
                    ($rencanaKegiatan->status !== RencanaKegiatan::STATUS_DISETUJUI
                        ? 'Status rencana kegiatan harus "Disetujui".'
                        : 'Laporan sudah ada.')
            ]);
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
        $status = LaporanKegiatan::STATUS_FINAL; // Always save as final since we removed draft button

        $laporan = LaporanKegiatan::create([
            'user_id' => auth()->id(),
            'rencana_kegiatan_id' => $request->rencana_kegiatan_id,
            'realisasi_tanggal_mulai' => $request->realisasi_tanggal_mulai,
            'realisasi_tanggal_selesai' => $request->realisasi_tanggal_selesai,
            'rangkaian_kegiatan' => $request->rangkaian_kegiatan,
            'target_peserta' => $rencanaKegiatan->target_peserta,
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

        // Update rencana kegiatan status dan timestamp untuk memindahkan ke urutan teratas
        $rencanaKegiatan->update([
            'status' => \App\Models\RencanaKegiatan::STATUS_MENUNGGU_VERIFIKASI,
            'updated_at' => now()
        ]);

        // Kirim notifikasi ke supervisor jika admin yang menambahkan
        if ($isAdmin) {
            $notification = new LaporanActivityNotification(
                $laporan->uuid,
                $rencanaKegiatan->uuid,
                null,
                $rencanaKegiatan->nama_kegiatan,
                'ditambahkan',
                $user->name,
                null,
                now()
            );
            $this->notifySupervisors($notification);
        }

        toast('Laporan kegiatan berhasil disimpan!', 'success');
        return redirect()->route('laporan_kegiatan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaporanKegiatan $laporanKegiatan)
    {
        // Check authorization
        $this->authorize('view', $laporanKegiatan);
        
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

        $user = auth()->user();
        $isAdmin = $user->role->role_name === 'admin';

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
        $status = LaporanKegiatan::STATUS_FINAL; // Always save as final since we removed draft button

        $laporanKegiatan->update([
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
        ]);

        // Kirim notifikasi ke supervisor jika admin yang mengedit
        if ($isAdmin) {
            $rencanaKegiatan = $laporanKegiatan->rencanaKegiatan;
            $notification = new LaporanActivityNotification(
                $laporanKegiatan->uuid,
                $rencanaKegiatan->uuid,
                null,
                $rencanaKegiatan->nama_kegiatan,
                'diedit',
                $user->name,
                null,
                now()
            );
            $this->notifySupervisors($notification);
        }

        toast('Laporan kegiatan berhasil diperbarui!', 'success');
        return redirect()->route('laporan_kegiatan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanKegiatan $laporanKegiatan)
    {
        // Check authorization
        $this->authorize('delete', $laporanKegiatan);

        $user = auth()->user();
        $isAdmin = $user->role->role_name === 'admin';

        // Simpan data untuk notifikasi sebelum dihapus
        $laporanUuid = $laporanKegiatan->uuid;
        $rencanaKegiatan = $laporanKegiatan->rencanaKegiatan;
        $rencanaUuid = $rencanaKegiatan->uuid;
        $rencanaNama = $rencanaKegiatan->nama_kegiatan;

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

        // Kirim notifikasi ke supervisor jika admin yang menghapus
        if ($isAdmin) {
            $notification = new LaporanActivityNotification(
                $laporanUuid,
                $rencanaUuid,
                null,
                $rencanaNama,
                'dihapus',
                $user->name,
                null,
                now()
            );
            $this->notifySupervisors($notification);
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
}
