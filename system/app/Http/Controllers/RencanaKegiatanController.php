<?php

namespace App\Http\Controllers;

use App\Models\RencanaKegiatan;
use App\Models\User;
use App\Notifications\StatusKegiatanNotification;
use App\Notifications\KegiatanActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;

class RencanaKegiatanController extends Controller
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

    public function index(Request $request)
    {
        $user = auth()->user();
        $isSupervisor = $user->role->role_name === 'admin';
        
        // Handle filter_status parameter from dashboard
        $filterStatus = $request->get('filter_status');
        if ($filterStatus && $filterStatus !== 'all') {
            // Convert filter_status to status parameter for the form
            $request->merge(['status' => $filterStatus]);
        }
        
        // Filter data berdasarkan peran
        if ($isSupervisor) {
            // Supervisor (admin) melihat semua data
            $query = RencanaKegiatan::with('laporanKegiatan', 'user');
            
            // Apply filters if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('bulan')) {
                $query->whereMonth('tanggal_mulai', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_mulai', $request->tahun);
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            $rencanaKegiatans = $query->orderBy('updated_at', 'desc')->get();
            
            // Get all anggota users for filter
            $users = User::whereHas('role', function($query) {
                $query->where('role_name', 'anggota');
            })->orderBy('name')->get();
        } else {
            // Anggota hanya melihat datanya sendiri
            $query = RencanaKegiatan::with('laporanKegiatan', 'user')
                ->where('user_id', $user->id);
            
            // Apply filters if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('bulan')) {
                $query->whereMonth('tanggal_mulai', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_mulai', $request->tahun);
            }
            
            $rencanaKegiatans = $query->orderBy('updated_at', 'desc')->get();
            
            $users = null;
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

        return view('rencana_kegiatan.index', compact('rencanaKegiatans', 'users'));
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
                'jenis_kegiatan_lainnya' => 'required_if:jenis_kegiatan,lainnya|nullable|string',
                'deskripsi' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'desa' => 'nullable|string',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'waktu_mulai' => 'nullable|date_format:H:i',
                'waktu_selesai' => 'nullable|date_format:H:i',
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
                'anggaran_kegiatan' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
            ];

            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'jenis_kegiatan_lainnya.required_if' => 'Deskripsi jenis kegiatan lainnya wajib diisi saat memilih "Lainnya".',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
                'keterangan_status.required_if' => 'Keterangan status wajib diisi saat menyetujui atau menolak.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'waktu_mulai.date_format' => 'Format waktu mulai tidak valid (HH:MM).',
                'waktu_selesai.date_format' => 'Format waktu selesai tidak valid (HH:MM).',
                'anggaran_kegiatan.required' => 'Anggaran kegiatan wajib diunggah.',
            ];
        } else {
            // Admin cannot change status and no keterangan field
            $rules = [
                'nama_kegiatan' => 'required|string',
                'jenis_kegiatan' => 'required|string',
                'jenis_kegiatan_lainnya' => 'required_if:jenis_kegiatan,lainnya|nullable|string',
                'deskripsi' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'desa' => 'nullable|string',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'waktu_mulai' => 'nullable|date_format:H:i',
                'waktu_selesai' => 'nullable|date_format:H:i',
                'penanggung_jawab' => 'nullable|string',
                'kelompok' => 'nullable|string',
                'estimasi_peserta' => 'nullable|integer',
                'rincian_kebutuhan' => 'nullable|string',
                'foto' => 'nullable|array',
                'foto.*' => 'image|mimes:jpg,jpeg,png|max:4096',
                'dokumen' => 'nullable|array',
                'dokumen.*' => 'file|mimes:pdf,doc,docx|max:5120',
                'anggaran_kegiatan' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
            ];

            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'jenis_kegiatan_lainnya.required_if' => 'Deskripsi jenis kegiatan lainnya wajib diisi saat memilih "Lainnya".',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'waktu_mulai.date_format' => 'Format waktu mulai tidak valid (HH:MM).',
                'waktu_selesai.date_format' => 'Format waktu selesai tidak valid (HH:MM).',
                'anggaran_kegiatan.required' => 'Anggaran kegiatan wajib diunggah.',
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

        $anggaranKegiatanPath = null;
        if ($request->hasFile('anggaran_kegiatan')) {
            $file = $request->file('anggaran_kegiatan');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);
            
            $path = $file->storeAs('rencana_kegiatans/anggaran', $fileName, 'public');
            
            $anggaranKegiatanPath = [
                'path' => $path,
                'original_name' => $originalName
            ];
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
            'jenis_kegiatan_lainnya' => $validated['jenis_kegiatan_lainnya'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tujuan' => $validated['tujuan'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'desa' => $validated['desa'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'penanggung_jawab' => $validated['penanggung_jawab'] ?? null,
            'kelompok' => $validated['kelompok'] ?? null,
            'estimasi_peserta' => $validated['estimasi_peserta'] ?? null,
            'rincian_kebutuhan' => $validated['rincian_kebutuhan'] ?? null,
            'foto' => !empty($fotoPaths) ? $fotoPaths : null,
            'dokumen' => !empty($dokumenPaths) ? $dokumenPaths : null,
            'anggaran_kegiatan' => $anggaranKegiatanPath ?: null,
            'status' => $request->input('action') === 'draft' ? 'draft' : 'diajukan',
        ];

        $rencanaKegiatan = RencanaKegiatan::create($data);

        // Kirim notifikasi ke supervisor jika admin yang menambahkan
        if ($isAdmin) {
            $notification = new KegiatanActivityNotification(
                $rencanaKegiatan->uuid,
                $rencanaKegiatan->nama_kegiatan,
                'ditambahkan',
                $user->name,
                null,
                now()
            );
            $this->notifySupervisors($notification);
        }

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
        $isSupervisor = $user->role->role_name === 'admin';
        $isAdmin = $user->role->role_name === 'anggota';
        Log::info('RencanaKegiatanController@update called', ['id' => $rencana_kegiatan->id, 'input' => $request->all()]);

        // Different validation rules based on role
        if ($isSupervisor) {
            // Supervisor hanya bisa ubah status dan keterangan
            $rules = [
                'status' => 'required|in:diajukan,disetujui,revisi,ditolak,selesai',
                'keterangan_status' => 'required_if:status,disetujui,revisi,ditolak|string',
            ];

            $messages = [
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
                'keterangan_status.required_if' => 'Keterangan status wajib diisi saat menyetujui, merevisi, atau menolak.',
            ];
        } else {
            // Admin cannot change status and no keterangan field
            $rules = [
                'nama_kegiatan' => 'required|string',
                'jenis_kegiatan' => 'required|string',
                'jenis_kegiatan_lainnya' => 'required_if:jenis_kegiatan,lainnya|nullable|string',
                'deskripsi' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'desa' => 'nullable|string',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai' => 'nullable|date',
                'waktu_mulai' => 'nullable|date_format:H:i',
                'waktu_selesai' => 'nullable|date_format:H:i',
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
                'anggaran_kegiatan' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
                'remove_anggaran_kegiatan' => 'nullable|string',
            ];

            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'jenis_kegiatan_lainnya.required_if' => 'Deskripsi jenis kegiatan lainnya wajib diisi saat memilih "Lainnya".',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
                'waktu_mulai.date_format' => 'Format waktu mulai tidak valid (HH:MM).',
                'waktu_selesai.date_format' => 'Format waktu selesai tidak valid (HH:MM).',
                'anggaran_kegiatan.required' => 'Anggaran kegiatan wajib diunggah.',
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

        // Handle anggaran kegiatan removal
        $currentAnggaran = $rencana_kegiatan->anggaran_kegiatan;
        $removeAnggaran = $request->input('remove_anggaran_kegiatan');
        
        if ($removeAnggaran) {
            if ($currentAnggaran) {
                $anggaranPath = is_array($currentAnggaran) ? $currentAnggaran['path'] : $currentAnggaran;
                Storage::disk('public')->delete($anggaranPath);
                $currentAnggaran = null;
            }
        }

        // Handle new anggaran kegiatan upload
        $newAnggaranPath = null;
        if ($request->hasFile('anggaran_kegiatan')) {
            $file = $request->file('anggaran_kegiatan');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);
            
            $path = $file->storeAs('rencana_kegiatans/anggaran', $fileName, 'public');
            
            $newAnggaranPath = [
                'path' => $path,
                'original_name' => $originalName
            ];
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
                'jenis_kegiatan_lainnya' => $validated['jenis_kegiatan_lainnya'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tujuan' => $validated['tujuan'] ?? null,
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
                'desa' => $validated['desa'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'waktu_mulai' => $validated['waktu_mulai'] ?? null,
                'waktu_selesai' => $validated['waktu_selesai'] ?? null,
                'penanggung_jawab' => $validated['penanggung_jawab'] ?? null,
                'kelompok' => $validated['kelompok'] ?? null,
                'estimasi_peserta' => $validated['estimasi_peserta'] ?? null,
                'rincian_kebutuhan' => $validated['rincian_kebutuhan'] ?? null,
                'status' => RencanaKegiatan::STATUS_DIAJUKAN, // Reset to diajukan for admin revisi
                'keterangan_status' => null, // Clear keterangan
                'foto' => !empty($finalFoto) ? array_values($finalFoto) : null,
                'dokumen' => !empty($finalDokumen) ? array_values($finalDokumen) : null,
                'anggaran_kegiatan' => $newAnggaranPath ? $newAnggaranPath : $currentAnggaran,
            ];
        }

        $rencana_kegiatan->update($data);

        // Kirim notifikasi ke supervisor jika admin yang mengedit
        if ($isAdmin) {
            $notification = new KegiatanActivityNotification(
                $rencana_kegiatan->uuid,
                $rencana_kegiatan->nama_kegiatan,
                'diedit',
                $user->name,
                null,
                now()
            );
            $this->notifySupervisors($notification);
        }

        $message = $isSupervisor
            ? 'Rencana kegiatan berhasil diperbarui!'
            : 'Rencana kegiatan berhasil direvisi dan diajukan ulang!';

        toast($message, 'success');
        return redirect()->route('rencana_kegiatan.index');
    }

    /**
     * Update status rencana kegiatan (supervisor only)
     */
    public function updateStatus(Request $request, RencanaKegiatan $rencana_kegiatan)
    {
        // Check authorization - hanya supervisor yang bisa update status
        $this->authorize('updateStatus', $rencana_kegiatan);

        $user = auth()->user();
        $isSupervisor = $user->role->role_name === 'admin';

        if (!$isSupervisor) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:diajukan,disetujui,revisi,ditolak,selesai',
            'keterangan_status' => 'required_if:status,disetujui,revisi,ditolak|string',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'keterangan_status.required_if' => 'Keterangan status wajib diisi saat menyetujui, merevisi, atau menolak.',
        ]);

        $statusLama = $rencana_kegiatan->status;
        $statusBaru = $validated['status'];

        // Jangan kirim notifikasi jika status tidak berubah
        if ($statusLama === $statusBaru) {
            toast('Status tidak ada perubahan.', 'info');
            return redirect()->back();
        }

        // Update status dan keterangan
        $data = [
            'status' => $statusBaru,
            'keterangan_status' => $validated['keterangan_status'] ?? null,
        ];

        $rencana_kegiatan->update($data);

        // Kirim notifikasi ke admin pembuat kegiatan
        // Jangan kirim jika user yang update adalah admin pemiliknya sendiri
        if ($rencana_kegiatan->user_id !== $user->id) {
            $adminPembuat = User::find($rencana_kegiatan->user_id);
            if ($adminPembuat) {
                $adminPembuat->notify(new StatusKegiatanNotification(
                    $rencana_kegiatan->uuid, // Use UUID
                    $rencana_kegiatan->nama_kegiatan,
                    $statusBaru,
                    $validated['keterangan_status'] ?? null,
                    now()
                ));
            }
        }

        toast('Status rencana kegiatan berhasil diperbarui!', 'success');
        return redirect()->route('rencana_kegiatan.index');
    }

    public function setujuiRencana(Request $request, $id)
    {
        $rencana = RencanaKegiatan::where('uuid', $id)->orWhere('id', $id)->firstOrFail();
        $this->authorize('updateStatus', $rencana);

        $rencana->update([
            'status' => RencanaKegiatan::STATUS_DISETUJUI,
            'keterangan_status' => null, // Bersihkan keterangan karena sudah disetujui
        ]);

        // Kirim notifikasi ke pembuat rencana
        if ($rencana->user_id !== auth()->id()) {
            $pembuat = User::find($rencana->user_id);
            if ($pembuat) {
                $pembuat->notify(new StatusKegiatanNotification(
                    $rencana->uuid,
                    $rencana->nama_kegiatan,
                    RencanaKegiatan::STATUS_DISETUJUI,
                    null,
                    now()
                ));
            }
        }

        toast('Rencana kegiatan berhasil disetujui!', 'success');
        return redirect()->back();
    }

    public function revisiRencana(Request $request, $id)
    {
        $rencana = RencanaKegiatan::where('uuid', $id)->orWhere('id', $id)->firstOrFail();
        $this->authorize('updateStatus', $rencana);

        $request->validate([
            'keterangan_status' => 'required|string',
        ], [
            'keterangan_status.required' => 'Catatan revisi wajib diisi.',
        ]);

        $rencana->update([
            'status' => RencanaKegiatan::STATUS_REVISI,
            'keterangan_status' => $request->keterangan_status,
        ]);

        // Kirim notifikasi ke pembuat rencana
        if ($rencana->user_id !== auth()->id()) {
            $pembuat = User::find($rencana->user_id);
            if ($pembuat) {
                $pembuat->notify(new StatusKegiatanNotification(
                    $rencana->uuid,
                    $rencana->nama_kegiatan,
                    RencanaKegiatan::STATUS_REVISI,
                    $request->keterangan_status,
                    now()
                ));
            }
        }

        toast('Permintaan revisi berhasil dikirim!', 'warning');
        return redirect()->back();
    }

    public function tolakRencana(Request $request, $id)
    {
        $rencana = RencanaKegiatan::where('uuid', $id)->orWhere('id', $id)->firstOrFail();
        $this->authorize('updateStatus', $rencana);

        $request->validate([
            'keterangan_status' => 'required|string',
        ], [
            'keterangan_status.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $rencana->update([
            'status' => RencanaKegiatan::STATUS_DITOLAK,
            'keterangan_status' => $request->keterangan_status,
        ]);

        // Kirim notifikasi ke pembuat rencana
        if ($rencana->user_id !== auth()->id()) {
            $pembuat = User::find($rencana->user_id);
            if ($pembuat) {
                $pembuat->notify(new StatusKegiatanNotification(
                    $rencana->uuid,
                    $rencana->nama_kegiatan,
                    RencanaKegiatan::STATUS_DITOLAK,
                    $request->keterangan_status,
                    now()
                ));
            }
        }

        toast('Rencana kegiatan telah ditolak.', 'error');
        return redirect()->back();
    }

    public function verifikasiLaporan(Request $request, RencanaKegiatan $rencana_kegiatan)
    {
        // Hanya supervisor yang bisa update status
        $this->authorize('updateStatus', $rencana_kegiatan);

        // Pastikan status saat ini memang sedang menunggu verifikasi
        if ($rencana_kegiatan->status !== RencanaKegiatan::STATUS_MENUNGGU_VERIFIKASI) {
            toast('Status tidak valid untuk diverifikasi.', 'error');
            return redirect()->back();
        }

        $rencana_kegiatan->update([
            'status' => RencanaKegiatan::STATUS_SELESAI,
            'keterangan_status' => 'Laporan telah diverifikasi dan disetujui.',
            'updated_at' => now()
        ]);

        toast('Laporan berhasil diverifikasi! Status kegiatan menjadi Selesai.', 'success');
        return redirect()->back();
    }

    public function destroy(RencanaKegiatan $rencana_kegiatan)
    {
        // Check authorization
        $this->authorize('delete', $rencana_kegiatan);

        $user = auth()->user();
        $isAdmin = $user->role->role_name === 'admin';

        // Simpan data untuk notifikasi sebelum dihapus
        $kegiatanUuid = $rencana_kegiatan->uuid;
        $kegiatanNama = $rencana_kegiatan->nama_kegiatan;

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

        // Hapus file anggaran kegiatan
        if (!empty($rencana_kegiatan->anggaran_kegiatan)) {
            // Pastikan anggaran_kegiatan adalah array, decode jika masih string
            $anggaran = is_string($rencana_kegiatan->anggaran_kegiatan) ? json_decode($rencana_kegiatan->anggaran_kegiatan, true) : $rencana_kegiatan->anggaran_kegiatan;

            // Handle format JSON
            if (is_string($anggaran)) {
                $anggaran = json_decode($anggaran, true);
            }

            if (is_array($anggaran)) {
                $path = $anggaran['path'] ?? null;
            } elseif (is_string($anggaran)) {
                $path = $anggaran;
            } else {
                $path = null;
            }

            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $rencana_kegiatan->delete();

        // Kirim notifikasi ke supervisor jika admin yang menghapus
        if ($isAdmin) {
            $notification = new KegiatanActivityNotification(
                $kegiatanUuid,
                $kegiatanNama,
                'dihapus',
                $user->name,
                null,
                now()
            );
            $this->notifySupervisors($notification);
        }

        // Alert::success('Berhasil', 'Rencana kegiatan berhasil dihapus.');
        toast('Rencana kegiatan berhasil dihapus.', 'success');
        return redirect()->route('rencana_kegiatan.index');
    }

    /**
     * Export rekap kegiatan to CSV (Supervisor only).
     */
    public function exportExcel(Request $request)
    {
        // Only admin can access this method
        if (auth()->user()->role->role_name !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'bulan' => 'nullable|integer|min:1|max:12',
            'tahun' => 'nullable|integer|min:2020|max:2030',
            'status' => 'nullable|in:diajukan,disetujui,ditolak,selesai',
            'user_id' => 'nullable|exists:users,id',
        ], [
            'bulan.integer' => 'Format bulan tidak valid.',
            'bulan.min' => 'Bulan minimal 1.',
            'bulan.max' => 'Bulan maksimal 12.',
            'tahun.integer' => 'Format tahun tidak valid.',
            'tahun.min' => 'Tahun minimal 2020.',
            'tahun.max' => 'Tahun maksimal 2030.',
            'status.in' => 'Status tidak valid.',
            'user_id.exists' => 'User tidak valid.',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $status = $request->status;
        $userId = $request->user_id;

        // Query data
        $query = RencanaKegiatan::with('user');

        if ($tahun) {
            $query->whereYear('tanggal_mulai', $tahun);
        }
        if ($bulan) {
            $query->whereMonth('tanggal_mulai', $bulan);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($status) {
            $query->where('status', $status);
        }

        // Filter berdasarkan user jika ada (hanya supervisor yang bisa filter per user)
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Filter berdasarkan status jika ada
        if ($status) {
            $query->where('status', $status);
        }

        $rencanaKegiatans = $query->orderBy('tanggal_mulai', 'asc')->get();

        // Header CSV
        $headers = [
            'No',
            'Nama Kegiatan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'User',
        ];

        $callback = function() use ($rencanaKegiatans, $headers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Write header
            fputcsv($file, $headers);
            
            // Write data
            $rowNumber = 0;
            foreach ($rencanaKegiatans as $rencana) {
                $rowNumber++;
                
                $rowData = [
                    $rowNumber,
                    $rencana->nama_kegiatan,
                    $rencana->tanggal_mulai ? $rencana->tanggal_mulai->format('d/m/Y') : '',
                    $rencana->tanggal_selesai ? $rencana->tanggal_selesai->format('d/m/Y') : '',
                    $this->formatStatus($rencana->status),
                    $rencana->user ? $rencana->user->name : 'Tidak diketahui',
                ];
                
                fputcsv($file, $rowData);
            }
            
            fclose($file);
        };

        $fileName = 'rencana_kegiatan.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Format status untuk display
     */
    private function formatStatus($status): string
    {
        $statusLabels = [
            'diajukan' => 'Diajukan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai',
        ];

        return $statusLabels[$status] ?? ucfirst($status);
    }

    public function exportPdf(RencanaKegiatan $rencanaKegiatan)
    {
        $this->authorize('view', $rencanaKegiatan);
        
        $rencanaKegiatan->load(['user', 'laporanKegiatan']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rencana_kegiatan.pdf', compact('rencanaKegiatan'));
        
        return $pdf->download('Rencana_Kegiatan_'.$rencanaKegiatan->uuid.'.pdf');
    }

    /**
     * Anggota action: Ajukan Rencana Kegiatan (Direct)
     */
    public function ajukanRencana(Request $request, $id)
    {
        $rencanaKegiatan = RencanaKegiatan::where('uuid', $id)->orWhere('id', $id)->firstOrFail();
        
        // Authorization check: User can only submit their own draft/revisi
        if ($rencanaKegiatan->user_id !== auth()->id() || !in_array($rencanaKegiatan->status, [RencanaKegiatan::STATUS_DRAFT, RencanaKegiatan::STATUS_REVISI])) {
            abort(403, 'Unauthorized action.');
        }

        $rencanaKegiatan->update([
            'status' => RencanaKegiatan::STATUS_DIAJUKAN,
            'keterangan_status' => null // Reset keterangan
        ]);
        
        // Send notification to supervisors
        $notification = new \App\Notifications\KegiatanActivityNotification(
            $rencanaKegiatan->uuid,
            $rencanaKegiatan->nama_kegiatan,
            'diajukan',
            auth()->user()->name,
            null,
            now()
        );
        $this->notifySupervisors($notification);
        
        toast('Rencana kegiatan berhasil diajukan!', 'success');
        return redirect()->back();
    }
}
