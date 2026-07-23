<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRencanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by Policies in Controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isSupervisor = auth()->user()->role->role_name === 'admin';

        if ($isSupervisor) {
            // Supervisor hanya bisa ubah status dan keterangan
            return [
                'status' => 'required|in:diajukan,disetujui,revisi,ditolak,selesai',
                'keterangan_status' => 'required_if:status,disetujui,revisi,ditolak|string',
            ];
        }

        // Admin (anggota)
        return [
            'nama_kegiatan' => 'required|string',
            'jenis_kegiatan' => 'required|string',
            'jenis_kegiatan_lainnya' => 'required_if:jenis_kegiatan,lainnya|nullable|string',
            'deskripsi' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'desa' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'penanggung_jawab' => 'nullable|string',
            'kelompok' => 'nullable|string',
            'estimasi_peserta' => 'nullable|integer',
            'rincian_kebutuhan' => 'nullable|string',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:5120',
            'dokumen' => 'nullable|array|max:5',
            'dokumen.*' => 'file|mimes:pdf,doc,docx|max:5120',
            'remove_foto' => 'nullable|array',
            'remove_foto.*' => 'string',
            'remove_dokumen' => 'nullable|array',
            'remove_dokumen.*' => 'string',
            'anggaran_kegiatan' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
            'remove_anggaran_kegiatan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        $isSupervisor = auth()->user()->role->role_name === 'admin';

        if ($isSupervisor) {
            return [
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
                'keterangan_status.required_if' => 'Keterangan status wajib diisi saat menyetujui, merevisi, atau menolak.',
            ];
        }

        return [
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
            'lat.between' => 'Latitude harus antara -90 sampai 90.',
            'lng.between' => 'Longitude harus antara -180 sampai 180.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'foto.max' => 'Maksimal hanya 5 file media publikasi baru yang dapat diunggah sekaligus.',
            'dokumen.max' => 'Maksimal hanya 5 file dokumen tambahan baru yang dapat diunggah sekaligus.',
        ];
    }
}
