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
        $rules = [
            // Informasi Pelaksanaan
            'realisasi_tanggal_mulai' => 'required|date',
            'realisasi_tanggal_selesai' => 'required|date|after_or_equal:realisasi_tanggal_mulai',
            'rangkaian_kegiatan' => 'required|string|min:20',
            'realisasi_peserta' => 'required|integer|min:0',
            'profil_peserta' => 'required|string|min:10',

            // Hasil dan Output
            'hasil_dicapai' => 'required|string|min:20',
            'output_nyata' => 'required|string|min:20',
            'dampak_awal' => 'required|string|min:20',

            // Kendala dan Evaluasi
            'kendala' => 'nullable|string|min:10',
            'solusi' => 'nullable|string|min:10',
            'evaluasi_rekomendasi' => 'nullable|string|min:10',

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

        // Add rencana_kegiatan_id only for store
        if ($this->isMethod('POST')) {
            $rules['rencana_kegiatan_id'] = 'required|exists:rencana_kegiatans,uuid';
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
            'rangkaian_kegiatan.min' => 'Rangkaian kegiatan minimal 20 karakter.',
            'realisasi_peserta.required' => 'Realisasi jumlah peserta wajib diisi.',
            'realisasi_peserta.integer' => 'Realisasi jumlah peserta harus berupa angka.',
            'realisasi_peserta.min' => 'Realisasi jumlah peserta tidak boleh kurang dari 0.',
            'profil_peserta.required' => 'Profil peserta wajib diisi.',
            'profil_peserta.min' => 'Profil peserta minimal 10 karakter.',

            // Hasil dan Output
            'hasil_dicapai.required' => 'Hasil yang dicapai wajib diisi.',
            'hasil_dicapai.min' => 'Hasil yang dicapai minimal 20 karakter.',
            'output_nyata.required' => 'Output nyata wajib diisi.',
            'output_nyata.min' => 'Output nyata minimal 20 karakter.',
            'dampak_awal.required' => 'Dampak awal yang terlihat wajib diisi.',
            'dampak_awal.min' => 'Dampak awal minimal 20 karakter.',

            // Kendala dan Evaluasi
            'kendala.min' => 'Kendala minimal 10 karakter.',
            'solusi.min' => 'Solusi minimal 10 karakter.',
            'evaluasi_rekomendasi.min' => 'Evaluasi dan rekomendasi minimal 10 karakter.',

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
