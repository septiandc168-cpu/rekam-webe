<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RencanaKegiatan;
use App\Models\LaporanKegiatan;

class DummyRevisiLaporanSeeder extends Seeder
{
    public function run()
    {
        $anggotaUsers = User::whereHas('role', function($q) {
            $q->where('role_name', 'anggota');
        })->get();

        if ($anggotaUsers->isEmpty()) {
            $anggotaUsers = User::all();
        }

        $dummyData = [
            [
                'nama' => 'Sosialisasi & Edukasi Pengelolaan Sampah Plastik di Aliran Sungai Kendawangan',
                'jenis' => 'edukasi',
                'desa' => 'Desa Kendawangan Kanan',
                'lat' => -2.4900,
                'lng' => 110.1700,
                'kelompok' => 'Komunitas Karang Taruna Peduli Sungai',
                'target' => 40,
                'realisasi_peserta' => 38,
                'tgl_mulai' => '2026-07-05',
                'tgl_selesai' => '2026-07-06',
                'deskripsi' => 'Edukasi pengolahan sampah organik dan pemilahan limbah plastik skala rumah tangga di sepanjang alur sungai.',
                'tujuan' => 'Menurunkan volume pencemaran sampah plastik ke perairan muara sungai.',
                'hasil' => 'Warga menyepakati pembuatan 3 titik bank sampah swakelola di tingkat RT.',
                'output' => 'Terbentuknya 3 unit bank sampah RT dan leaflet panduan pemilahan sampah.',
                'dampak' => 'Lingkungan sungai menjadi lebih bersih dan tertata.',
                'catatan_evaluasi' => 'Mohon tambahkan foto dokumentasi pelaksanaan kegiatan di lapangan serta melampirkan berkas notulen diskusi warga.'
            ],
            [
                'nama' => 'Pelatihan Pembuatan Kerajinan Daur Ulang bagi Kelompok Ibu Pesisir',
                'jenis' => 'usaha masyarakat',
                'desa' => 'Desa Mekar Utama',
                'lat' => -2.4750,
                'lng' => 110.1500,
                'kelompok' => 'Kelompok Usaha Ibu Pesisir Mandiri',
                'target' => 30,
                'realisasi_peserta' => 28,
                'tgl_mulai' => '2026-07-15',
                'tgl_selesai' => '2026-07-17',
                'deskripsi' => 'Bimbingan teknis merajut sampah kemasan plastik menjadi tas dan cenderamata bernilai jual.',
                'tujuan' => 'Meningkatkan pendapatan keluarga nelayan melalui pemanfaatan limbah plastik.',
                'hasil' => 'Peserta berhasil memproduksi 15 sampel produk kerajinan tas dan dompet daur ulang.',
                'output' => '15 unit produk souvenir daur ulang layak jual.',
                'dampak' => 'Ibu-ibu memiliki keterampilan tambahan untuk menambah penghasilan keluarga.',
                'catatan_evaluasi' => 'Rincian realisasi anggaran dan evaluasi dampak ekonomi awal perlu diperjelas dengan angka persentase kelayakan jual produk.'
            ]
        ];

        foreach ($dummyData as $idx => $data) {
            $user = $anggotaUsers[$idx % $anggotaUsers->count()];

            // 1. Buat RencanaKegiatan dengan status 'disetujui'
            $rencana = RencanaKegiatan::create([
                'user_id' => $user->id,
                'nama_kegiatan' => $data['nama'],
                'jenis_kegiatan' => $data['jenis'],
                'deskripsi' => $data['deskripsi'],
                'tujuan' => $data['tujuan'],
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'desa' => $data['desa'],
                'tanggal_mulai' => $data['tgl_mulai'],
                'tanggal_selesai' => $data['tgl_selesai'],
                'waktu_mulai' => '08:30',
                'waktu_selesai' => '15:30',
                'penanggung_jawab' => $user->name,
                'kelompok' => $data['kelompok'],
                'estimasi_peserta' => $data['target'],
                'rincian_kebutuhan' => '• Konsumsi peserta = Rp1.000.000<br>• Bahan & ATK = Rp800.000',
                'status' => RencanaKegiatan::STATUS_DISETUJUI,
            ]);

            // 2. Buat LaporanKegiatan dengan status 'revisi'
            LaporanKegiatan::create([
                'user_id' => $user->id,
                'rencana_kegiatan_id' => $rencana->uuid,
                'judul_kegiatan' => $data['nama'],
                'lokasi_kegiatan' => $data['desa'],
                'realisasi_tanggal_mulai' => $data['tgl_mulai'],
                'realisasi_tanggal_selesai' => $data['tgl_selesai'],
                'rangkaian_kegiatan' => $data['deskripsi'],
                'target_peserta' => $data['target'],
                'realisasi_peserta' => $data['realisasi_peserta'],
                'profil_peserta' => 'Masyarakat desa pesisir Kendawangan',
                'hasil_dicapai' => $data['hasil'],
                'output_nyata' => $data['output'],
                'dampak_awal' => $data['dampak'],
                'kendala' => 'Tingkat keahlian awal peserta masih beragam.',
                'solusi' => 'Pendampingan secara intensif oleh instruktur teknis.',
                'evaluasi_rekomendasi' => 'Perlu dilakukan pelatihan lanjutan tahap II.',
                'status' => LaporanKegiatan::STATUS_REVISI,
                'catatan_evaluasi' => $data['catatan_evaluasi'],
            ]);
        }
    }
}
