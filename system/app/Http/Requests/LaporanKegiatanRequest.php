<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanKegiatanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isDraft = $this->input('action') === 'draft';

        if ($isDraft) {
            $rules = [
                // Informasi Pelaksanaan - all nullable for draft
                'realisasi_tanggal_mulai' => 'nullable|date',
                'realisasi_tanggal_selesai' => 'nullable|date|after_or_equal:realisasi_tanggal_mulai',
                'rangkaian_kegiatan' => 'nullable|string',
                'realisasi_peserta' => 'nullable|integer',
                'profil_peserta' => 'nullable|string',

                // Hasil dan Output - all nullable for draft
                'hasil_dicapai' => 'nullable|string',
                'output_nyata' => 'nullable|string',
                'dampak_awal' => 'nullable|string',

                // Kendala dan Evaluasi
                'kendala' => 'nullable|string',
                'solusi' => 'nullable|string',
                'evaluasi_rekomendasi' => 'nullable|string',

                // Dokumentasi
                'foto_kegiatan' => 'nullable|array',
                'foto_kegiatan.*' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
                'daftar_hadir' => 'nullable|array',
                'daftar_hadir.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:3072',
                'notulen' => 'nullable|array',
                'notulen.*' => 'nullable|file|mimes:pdf,doc,docx|max:3072',
                'materi' => 'nullable|array',
                'materi.*' => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx|max:3072',
                'berita_acara' => 'nullable|array',
                'berita_acara.*' => 'nullable|file|mimes:pdf,doc,docx|max:3072',

                // Remove files (for edit)
                'remove_foto_kegiatan' => 'nullable|array',
                'remove_foto_kegiatan.*' => 'string',
                'remove_daftar_hadir' => 'nullable|array',
                'remove_daftar_hadir.*' => 'string',
                'remove_notulen' => 'nullable|array',
                'remove_notulen.*' => 'string',
                'remove_materi' => 'nullable|array',
                'remove_materi.*' => 'string',
                'remove_berita_acara' => 'nullable|array',
                'remove_berita_acara.*' => 'string',
            ];

            if ($this->isMethod('POST')) {
                if ($this->has('is_laporan_langsung') && $this->is_laporan_langsung == '1') {
                    $rules['rencana_kegiatan_id'] = 'nullable';
                    $rules['judul_kegiatan'] = 'required|string|max:255';
                    $rules['lokasi_kegiatan'] = 'nullable|string|max:255';
                } else {
                    $rules['rencana_kegiatan_id'] = 'required|exists:rencana_kegiatans,uuid';
                }
            }

            if (in_array($this->method(), ['PUT', 'PATCH']) && $this->route('laporan_kegiatan') && $this->route('laporan_kegiatan')->isDarurat()) {
                $rules['judul_kegiatan'] = 'required|string|max:255';
                $rules['lokasi_kegiatan'] = 'nullable|string|max:255';
            }

            return $rules;
        }

        $rules = [
            // Informasi Pelaksanaan
            'realisasi_tanggal_mulai' => 'required|date',
            'realisasi_tanggal_selesai' => 'required|date|after_or_equal:realisasi_tanggal_mulai',
            'rangkaian_kegiatan' => 'required|string',
            'realisasi_peserta' => 'required|integer',
            'profil_peserta' => 'required|string',

            // Hasil dan Output
            'hasil_dicapai' => 'required|string',
            'output_nyata' => 'required|string',
            'dampak_awal' => 'required|string',

            // Kendala dan Evaluasi
            'kendala' => 'required|string',
            'solusi' => 'required|string',
            'evaluasi_rekomendasi' => 'required|string',

            // Dokumentasi
            'foto_kegiatan' => 'nullable|array',
            'foto_kegiatan.*' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'daftar_hadir' => 'nullable|array',
            'daftar_hadir.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:3072',
            'notulen' => 'nullable|array',
            'notulen.*' => 'nullable|file|mimes:pdf,doc,docx|max:3072',
            'materi' => 'nullable|array',
            'materi.*' => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx|max:3072',
            'berita_acara' => 'nullable|array',
            'berita_acara.*' => 'nullable|file|mimes:pdf,doc,docx|max:3072',

            // Remove files (for edit)
            'remove_foto_kegiatan' => 'nullable|array',
            'remove_foto_kegiatan.*' => 'string',
            'remove_daftar_hadir' => 'nullable|array',
            'remove_daftar_hadir.*' => 'string',
            'remove_notulen' => 'nullable|array',
            'remove_notulen.*' => 'string',
            'remove_materi' => 'nullable|array',
            'remove_materi.*' => 'string',
            'remove_berita_acara' => 'nullable|array',
            'remove_berita_acara.*' => 'string',
        ];

        // Add rencana_kegiatan_id only for store, but make it nullable if it's a laporan langsung
        if ($this->isMethod('POST')) {
            if ($this->has('is_laporan_langsung') && $this->is_laporan_langsung == '1') {
                $rules['rencana_kegiatan_id'] = 'nullable';
                $rules['judul_kegiatan'] = 'required|string|max:255';
                $rules['lokasi_kegiatan'] = 'required|string|max:255';
            } else {
                $rules['rencana_kegiatan_id'] = 'required|exists:rencana_kegiatans,uuid';
            }
        }
        
        // Validation for edit if it's laporan langsung
        if (in_array($this->method(), ['PUT', 'PATCH']) && $this->route('laporan_kegiatan') && $this->route('laporan_kegiatan')->isDarurat()) {
            $rules['judul_kegiatan'] = 'required|string|max:255';
            $rules['lokasi_kegiatan'] = 'required|string|max:255';
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Informasi Pelaksanaan
            'realisasi_tanggal_mulai.required' => 'Realisasi tanggal mulai wajib diisi.',
            'realisasi_tanggal_mulai.date' => 'Realisasi tanggal mulai harus berupa tanggal yang valid.',
            'realisasi_tanggal_selesai.required' => 'Realisasi tanggal selesai wajib diisi.',
            'realisasi_tanggal_selesai.date' => 'Realisasi tanggal selesai harus berupa tanggal yang valid.',
            'realisasi_tanggal_selesai.after_or_equal' => 'Realisasi tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
            'rangkaian_kegiatan.required' => 'Rangkaian kegiatan wajib diisi.',
            'realisasi_peserta.required' => 'Realisasi jumlah peserta wajib diisi.',
            'realisasi_peserta.integer' => 'Realisasi jumlah peserta harus berupa angka.',
            'realisasi_peserta.min' => 'Realisasi jumlah peserta tidak boleh kurang dari 0.',
            'profil_peserta.required' => 'Profil peserta wajib diisi.',

            // Hasil dan Output
            'hasil_dicapai.required' => 'Hasil yang dicapai wajib diisi.',
            'output_nyata.required' => 'Output nyata wajib diisi.',
            'dampak_awal.required' => 'Dampak awal yang terlihat wajib diisi.',

            // Kendala dan Evaluasi
            'kendala.required' => 'Kendala yang dihadapi wajib diisi.',
            'solusi.required' => 'Solusi yang dilakukan wajib diisi.',
            'evaluasi_rekomendasi.required' => 'Catatan evaluasi & rekomendasi wajib diisi.',

            // Dokumentasi
            'foto_kegiatan.*.image' => 'Foto kegiatan harus berupa gambar.',
            'foto_kegiatan.*.mimes' => 'Format foto kegiatan harus jpg, jpeg, atau png.',
            'foto_kegiatan.*.max' => 'Ukuran maksimal foto kegiatan 3MB.',
            'daftar_hadir.*.file' => 'Daftar hadir harus berupa file.',
            'daftar_hadir.*.mimes' => 'Format daftar hadir harus pdf, doc, docx, xls, atau xlsx.',
            'daftar_hadir.*.max' => 'Ukuran maksimal file daftar hadir 3MB.',
            'notulen.*.file' => 'Notulen harus berupa file.',
            'notulen.*.mimes' => 'Format notulen harus pdf, doc, atau docx.',
            'notulen.*.max' => 'Ukuran maksimal file notulen 3MB.',
            'materi.*.file' => 'Materi harus berupa file.',
            'materi.*.mimes' => 'Format materi harus pdf, ppt, pptx, doc, atau docx.',
            'materi.*.max' => 'Ukuran maksimal file materi 3MB.',
            'berita_acara.*.file' => 'Berita acara harus berupa file.',
            'berita_acara.*.mimes' => 'Format berita acara harus pdf, doc, atau docx.',
            'berita_acara.*.max' => 'Ukuran maksimal file berita acara 3MB.',

            // Rencana Kegiatan
            'rencana_kegiatan_id.required' => 'Rencana kegiatan wajib dipilih.',
            'rencana_kegiatan_id.exists' => 'Rencana kegiatan tidak valid.',
            'judul_kegiatan.required' => 'Judul kegiatan wajib diisi untuk laporan langsung.',
            'lokasi_kegiatan.required' => 'Lokasi kegiatan wajib diisi untuk laporan langsung.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'realisasi_tanggal_mulai' => 'Realisasi Tanggal Mulai',
            'realisasi_tanggal_selesai' => 'Realisasi Tanggal Selesai',
            'rangkaian_kegiatan' => 'Rangkaian Kegiatan',
            'realisasi_peserta' => 'Realisasi Jumlah Peserta',
            'profil_peserta' => 'Profil Peserta',
            'hasil_dicapai' => 'Hasil yang Dicapai',
            'output_nyata' => 'Output Nyata',
            'dampak_awal' => 'Dampak Awal yang Terlihat',
            'kendala' => 'Kendala yang Dihadapi',
            'solusi' => 'Solusi yang Dilakukan',
            'evaluasi_rekomendasi' => 'Catatan Evaluasi & Rekomendasi',
        ];
    }
}
