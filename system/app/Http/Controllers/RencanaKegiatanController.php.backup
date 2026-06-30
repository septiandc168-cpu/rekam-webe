<?php

namespace App\Http\Controllers;

use App\Models\RencanaKegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;

class RencanaKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSupervisor = $user->role->role_name === 'supervisor';
        
        // Filter data berdasarkan peran
        if ($isSupervisor) {
            // Supervisor melihat semua data
            $rencanaKegiatans = RencanaKegiatan::with('laporanKegiatan', 'user')
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            // Admin hanya melihat datanya sendiri
            $rencanaKegiatans = RencanaKegiatan::with('laporanKegiatan', 'user')
                ->where('user_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        
        // Konfigurasi SweetAlert untuk delete dengan warna danger
        $confirm = [
            'title' => 'Hapus Rencana Kegiatan?',
            'text' => 'Apakah Anda yakin ingin menghapus rencana kegiatan ini? Data yang dihapus tidak dapat dikembalikan.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#dc3545',
            'cancelButtonColor' => '#6c757d',
            'confirmButtonText' => 'Ya, Hapus',
            'cancelButtonText' => 'Batal'
        ];

        session()->flash('alert.delete', json_encode($confirm, JSON_UNESCAPED_SLASHES));

        return view('rencana_kegiatan.index', compact('rencanaKegiatans'));
    }

    public function create()
    {
        // Check authorization
        $this->authorize('create', RencanaKegiatan::class);
        
        return view('rencana_kegiatan.create');
    }

    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', RencanaKegiatan::class);
        
        $user = auth()->user();
        $isSupervisor = $user->role->role_name === 'supervisor';
        $isAdmin = $user->role->role_name === 'admin';

        // Different validation rules based on role
        if ($isSupervisor) {
            // Supervisor can change status and must provide keterangan for approve/reject
            $rules = [
                'nama_kegiatan' => 'required|string',
                'jenis_kegiatan' => 'required|string',
                'deskripsi' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'desa' => 'nullable|string',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'penanggung_jawab' => 'nullable|string',
                'kelompok' => 'nullable|string',
                'estimasi_peserta' => 'nullable|integer',
                'rincian_kebutuhan' => 'nullable|string',
                'status' => 'required|in:diajukan,disetujui,ditolak,selesai',
                'keterangan_status' => 'required_if:status,disetujui,ditolak|string',
                'foto' => 'nullable|array',
                'foto.*' => 'image|mimes:jpg,jpeg,png|max:4096',
                'dokumen' => 'nullable|array',
                'dokumen.*' => 'file|mimes:pdf,doc,docx|max:5120',
            ];

            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
                'keterangan_status.required_if' => 'Keterangan status wajib diisi saat menyetujui atau menolak.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            ];
        } else {
            // Admin cannot change status and no keterangan field
            $rules = [
                'nama_kegiatan' => 'required|string',
                'jenis_kegiatan' => 'required|string',
                'deskripsi' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'desa' => 'nullable|string',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'penanggung_jawab' => 'nullable|string',
                'kelompok' => 'nullable|string',
                'estimasi_peserta' => 'nullable|integer',
                'rincian_kebutuhan' => 'nullable|string',
                'foto' => 'nullable|array',
                'foto.*' => 'image|mimes:jpg,jpeg,png|max:4096',
                'dokumen' => 'nullable|array',
                'dokumen.*' => 'file|mimes:pdf,doc,docx|max:5120',
            ];

            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            ];
        }

        $validated = $request->validate($rules, $messages);

        $fotoPaths = [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file dengan nama asli
                $path = $file->storeAs('rencana_kegiatans', $fileName, 'public');

                // Simpan array dengan path dan nama asli
                $fotoPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        $dokumenPaths = [];
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file dengan nama asli
                $path = $file->storeAs('rencana_kegiatans/dokumen', $fileName, 'public');

                // Simpan array dengan path dan nama asli
                $dokumenPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        // if both dates present and end before start, swap them automatically
        if (!empty($validated['tanggal_mulai']) && !empty($validated['tanggal_selesai'])) {
            try {
                $d1 = Carbon::parse($validated['tanggal_mulai']);
                $d2 = Carbon::parse($validated['tanggal_selesai']);
                if ($d2->lt($d1)) {
                    // swap
                    $tmp = $validated['tanggal_mulai'];
                    $validated['tanggal_mulai'] = $validated['tanggal_selesai'];
                    $validated['tanggal_selesai'] = $tmp;
                }
            } catch (\Exception $e) {
                // ignore parse errors here; validation already ensured date format
            }
        }

        // map incoming fields to RencanaKegiatan structure
        $data = [
            'user_id' => $user->id,
            'nama_kegiatan' => $validated['nama_kegiatan'] ?? null,
            'jenis_kegiatan' => $validated['jenis_kegiatan'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tujuan' => $validated['tujuan'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'desa' => $validated['desa'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'penanggung_jawab' => $validated['penanggung_jawab'] ?? null,
            'kelompok' => $validated['kelompok'] ?? null,
            'estimasi_peserta' => $validated['estimasi_peserta'] ?? null,
            'rincian_kebutuhan' => $validated['rincian_kebutuhan'] ?? null,
            'foto' => !empty($fotoPaths) ? json_encode($fotoPaths) : null,
            'dokumen' => !empty($dokumenPaths) ? json_encode($dokumenPaths) : null,
            'status' => 'diajukan',
        ];

        RencanaKegiatan::create($data);

        // Alert::success('Berhasil', 'Rencana kegiatan berhasil disimpan!');
        toast('Rencana kegiatan berhasil disimpan!', 'success');
        return redirect()->route('rencana_kegiatan.index');
    }

    /**
     * Public front map view showing markers.
     */
    public function frontIndex()
    {
        $user = auth()->user();
        $isSupervisor = $user ? $user->role->role_name === 'supervisor' : false;
        
        // Filter data berdasarkan peran untuk public map view
        if ($isSupervisor) {
            // Supervisor melihat semua data
            $rencanaKegiatans = RencanaKegiatan::whereNotNull('lat')->whereNotNull('lng')->get();
        } elseif ($user) {
            // Admin hanya melihat datanya sendiri
            $rencanaKegiatans = RencanaKegiatan::where('user_id', $user->id)
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->get();
        } else {
            // Guest tidak melihat data apa-apa atau bisa disesuaikan
            $rencanaKegiatans = collect();
        }
        
        return view('rencana_kegiatan.front_index', compact('rencanaKegiatans'));
    }

    public function show(RencanaKegiatan $rencana_kegiatan)
    {
        // Check authorization
        $this->authorize('view', $rencana_kegiatan);
        
        $rencana_kegiatan->load('laporanKegiatan');
        return view('rencana_kegiatan.show', compact('rencana_kegiatan'));
    }

    public function edit(RencanaKegiatan $rencana_kegiatan)
    {
        // Check authorization
        $this->authorize('update', $rencana_kegiatan);

        return view('rencana_kegiatan.edit', compact('rencana_kegiatan'));
    }

    public function update(Request $request, RencanaKegiatan $rencana_kegiatan)
    {
        // Check authorization
        $this->authorize('update', $rencana_kegiatan);

        $user = auth()->user();
        $isSupervisor = $user->role->role_name === 'supervisor';
        $isAdmin = $user->role->role_name === 'admin';
        Log::info('RencanaKegiatanController@update called', ['id' => $rencana_kegiatan->id, 'input' => $request->all()]);

        // Different validation rules based on role
        if ($isSupervisor) {
            // Supervisor hanya bisa ubah status dan keterangan
            $rules = [
                'status' => 'required|in:diajukan,disetujui,ditolak,selesai',
                'keterangan_status' => 'required_if:status,disetujui,ditolak|string',
            ];

            $messages = [
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
                'keterangan_status.required_if' => 'Keterangan status wajib diisi saat menyetujui atau menolak.',
            ];
        } else {
            // Admin cannot change status and no keterangan field
            $rules = [
                'nama_kegiatan' => 'required|string',
                'jenis_kegiatan' => 'required|string',
                'deskripsi' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'desa' => 'nullable|string',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'penanggung_jawab' => 'nullable|string',
                'kelompok' => 'nullable|string',
                'estimasi_peserta' => 'nullable|integer',
                'rincian_kebutuhan' => 'nullable|string',
                'foto' => 'nullable|array',
                'foto.*' => 'image|mimes:jpg,jpeg,png|max:4096',
                'dokumen' => 'nullable|array',
                'dokumen.*' => 'file|mimes:pdf,doc,docx|max:5120',
                'remove_foto' => 'nullable|array',
                'remove_foto.*' => 'string',
                'remove_dokumen' => 'nullable|array',
                'remove_dokumen.*' => 'string',
            ];

            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            ];
        }

        $validated = $request->validate($rules, $messages);

        // Handle foto removals
        $currentFoto = $rencana_kegiatan->foto ?? [];
        // Pastikan currentFoto adalah array, decode jika masih string
        $currentFoto = is_string($currentFoto) ? json_decode($currentFoto, true) : $currentFoto;
        $removeFoto = $request->input('remove_foto', []);

        if (!empty($removeFoto)) {
            // Extract paths from current foto data
            $currentFotoPaths = [];
            if (is_string($currentFoto)) {
                $currentFoto = json_decode($currentFoto, true);
            }

            if (is_array($currentFoto)) {
                foreach ($currentFoto as $foto) {
                    if (is_array($foto)) {
                        $currentFotoPaths[] = $foto['path'];
                    } else {
                        $currentFotoPaths[] = $foto;
                    }
                }
            }

            foreach ($removeFoto as $path) {
                if (in_array($path, $currentFotoPaths)) {
                    Storage::disk('public')->delete($path);
                    $currentFotoPaths = array_diff($currentFotoPaths, [$path]);
                }
            }

            // Rebuild current foto array without removed items
            $newCurrentFoto = [];
            if (is_array($currentFoto)) {
                foreach ($currentFoto as $foto) {
                    $fotoPath = is_array($foto) ? $foto['path'] : $foto;
                    if (in_array($fotoPath, $currentFotoPaths)) {
                        $newCurrentFoto[] = $foto;
                    }
                }
            }
            $currentFoto = $newCurrentFoto;
        }

        // Handle dokumen removals
        $currentDokumen = $rencana_kegiatan->dokumen ?? [];
        // Pastikan currentDokumen adalah array, decode jika masih string
        $currentDokumen = is_string($currentDokumen) ? json_decode($currentDokumen, true) : $currentDokumen;
        $removeDokumen = $request->input('remove_dokumen', []);

        if (!empty($removeDokumen)) {
            // Extract paths from current dokumen data
            $currentDokumenPaths = [];
            if (is_string($currentDokumen)) {
                $currentDokumen = json_decode($currentDokumen, true);
            }

            if (is_array($currentDokumen)) {
                foreach ($currentDokumen as $dokumen) {
                    if (is_array($dokumen)) {
                        $currentDokumenPaths[] = $dokumen['path'];
                    } else {
                        $currentDokumenPaths[] = $dokumen;
                    }
                }
            }

            foreach ($removeDokumen as $path) {
                if (in_array($path, $currentDokumenPaths)) {
                    Storage::disk('public')->delete($path);
                    $currentDokumenPaths = array_diff($currentDokumenPaths, [$path]);
                }
            }

            // Rebuild current dokumen array without removed items
            $newCurrentDokumen = [];
            if (is_array($currentDokumen)) {
                foreach ($currentDokumen as $dokumen) {
                    $dokumenPath = is_array($dokumen) ? $dokumen['path'] : $dokumen;
                    if (in_array($dokumenPath, $currentDokumenPaths)) {
                        $newCurrentDokumen[] = $dokumen;
                    }
                }
            }
            $currentDokumen = $newCurrentDokumen;
        }

        // Handle new foto uploads
        $newFotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file dengan nama asli
                $path = $file->storeAs('rencana_kegiatans', $fileName, 'public');

                // Simpan array dengan path dan nama asli
                $newFotoPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        // Handle new dokumen uploads
        $newDokumenPaths = [];
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                // Buat nama file unik dengan nama asli
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . str_replace(' ', '_', $originalName);

                // Simpan file dengan nama asli
                $path = $file->storeAs('rencana_kegiatans/dokumen', $fileName, 'public');

                // Simpan array dengan path dan nama asli
                $newDokumenPaths[] = [
                    'path' => $path,
                    'original_name' => $originalName
                ];
            }
        }

        // Merge existing and new files
        $finalFoto = array_merge((array)$currentFoto, $newFotoPaths);
        $finalDokumen = array_merge((array)$currentDokumen, $newDokumenPaths);

        // if both dates present and end before start, swap them automatically
        if (!empty($validated['tanggal_mulai']) && !empty($validated['tanggal_selesai'])) {
            try {
                $d1 = Carbon::parse($validated['tanggal_mulai']);
                $d2 = Carbon::parse($validated['tanggal_selesai']);
                if ($d2->lt($d1)) {
                    $tmp = $validated['tanggal_mulai'];
                    $validated['tanggal_mulai'] = $validated['tanggal_selesai'];
                    $validated['tanggal_selesai'] = $tmp;
                }
            } catch (\Exception $e) {
                // ignore parse errors
            }
        }



        // Prepare update data based on role
        if ($isSupervisor) {
            // Supervisor hanya bisa update status dan keterangan_status
            $data = [
                'status' => $validated['status'],
                'keterangan_status' => $validated['keterangan_status'] ?? null,
            ];
        } else {
            // Admin revisi: reset status to 'diajukan' and clear keterangan
            $data = [
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'jenis_kegiatan' => $validated['jenis_kegiatan'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tujuan' => $validated['tujuan'] ?? null,
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
                'desa' => $validated['desa'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'penanggung_jawab' => $validated['penanggung_jawab'] ?? null,
                'kelompok' => $validated['kelompok'] ?? null,
                'estimasi_peserta' => $validated['estimasi_peserta'] ?? null,
                'rincian_kebutuhan' => $validated['rincian_kebutuhan'] ?? null,
                'status' => RencanaKegiatan::STATUS_DIAJUKAN, // Reset to diajukan for admin revisi
                'keterangan_status' => null, // Clear keterangan
                'foto' => !empty($finalFoto) ? array_values($finalFoto) : null,
                'dokumen' => !empty($finalDokumen) ? array_values($finalDokumen) : null,
            ];
        }

        $rencana_kegiatan->update($data);

        $message = $isSupervisor
            ? 'Rencana kegiatan berhasil diperbarui!'
            : 'Rencana kegiatan berhasil direvisi dan diajukan ulang!';

        toast($message, 'success');
        return redirect()->route('rencana_kegiatan.index');
    }

    public function destroy(RencanaKegiatan $rencana_kegiatan)
    {
        // Check authorization
        $this->authorize('delete', $rencana_kegiatan);

        // remove files
        // Hapus foto dengan format baru dan lama
        if (!empty($rencana_kegiatan->foto)) {
            // Pastikan foto adalah array, decode jika masih string
            $fotos = is_string($rencana_kegiatan->foto) ? json_decode($rencana_kegiatan->foto, true) : $rencana_kegiatan->foto;

            // Handle format JSON
            if (is_string($fotos)) {
                $fotos = json_decode($fotos, true);
            }

            if (is_array($fotos)) {
                foreach ($fotos as $foto) {
                    $path = null;

                    // Handle format baru (array dengan path dan original_name)
                    if (is_array($foto)) {
                        $path = $foto['path'];
                    }
                    // Handle format lama (string path)
                    elseif (is_string($foto)) {
                        $path = $foto;
                    }
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
        }

        // Hapus dokumen dengan format baru dan lama
        if (!empty($rencana_kegiatan->dokumen)) {
            // Pastikan dokumen adalah array, decode jika masih string
            $dokumens = is_string($rencana_kegiatan->dokumen) ? json_decode($rencana_kegiatan->dokumen, true) : $rencana_kegiatan->dokumen;

            // Handle format JSON
            if (is_string($dokumens)) {
                $dokumens = json_decode($dokumens, true);
            }

            if (is_array($dokumens)) {
                foreach ($dokumens as $dokumen) {
                    $path = null;

                    // Handle format baru (array dengan path dan original_name)
                    if (is_array($dokumen)) {
                        $path = $dokumen['path'];
                    }
                    // Handle format lama (string path)
                    elseif (is_string($dokumen)) {
                        $path = $dokumen;
                    }
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
        }

        $rencana_kegiatan->delete();
        // Alert::success('Berhasil', 'Rencana kegiatan berhasil dihapus.');
        toast('Rencana kegiatan berhasil dihapus.', 'success');
        return redirect()->route('rencana_kegiatan.index');
    }
}
