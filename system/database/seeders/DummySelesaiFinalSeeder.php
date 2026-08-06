<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RencanaKegiatan;
use App\Models\LaporanKegiatan;

class DummySelesaiFinalSeeder extends Seeder
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
                'nama' => 'Penanaman 500 Bibit Bakau di Pesisir Kendawangan Kiri',
                'jenis' => 'konservasi',
                'desa' => 'Desa Kendawangan Kiri',
                'lat' => -2.4831,
                'lng' => 110.1654,
                'pj' => 'M. Luqinul Mifdlol Assiddiqi',
                'kelompok' => 'Kelompok Tani Mangrove Lestari',
                'target' => 50,
                'realisasi_peserta' => 54,
                'tgl_mulai' => '2026-01-10',
                'tgl_selesai' => '2026-01-12',
                'deskripsi' => 'Rehabilitasi kawasan pesisir rawan erosi dengan menanam bibit bakau jenis Rhizophora mucronata.',
                'tujuan' => 'Mencegah abrasi pantai dan mengembalikan habitat biota laut pesisir Kendawangan.',
                'hasil' => 'Berhasil menanam 500 bibit bakau dengan tingkat partisipasi warga yang sangat antusias.',
                'output' => '500 bibit bakau tertanam di area seluas 1.5 hektar pesisir pantai.',
                'dampak' => 'Meningkatnya kesadaran masyarakat pesisir tentang perlindungan ekosistem bakau.',
            ],
            [
                'nama' => 'Pelatihan Pembuatan Kompos Organik Limbah Rumah Tangga',
                'jenis' => 'edukasi',
                'desa' => 'Desa Kendawangan Kanan',
                'lat' => -2.4795,
                'lng' => 110.1580,
                'pj' => 'Anggun Safitri',
                'kelompok' => 'PKK Desa Kendawangan Kanan',
                'target' => 35,
                'realisasi_peserta' => 40,
                'tgl_mulai' => '2026-02-05',
                'tgl_selesai' => '2026-02-06',
                'deskripsi' => 'Edukasi dan praktek pengolahan sampah organik sisa dapur menjadi pupuk kompos berkualitas tinggi.',
                'tujuan' => 'Mengurangi volume sampah ke TPA dan memberdayakan pemanfaatan sampah organik warga.',
                'hasil' => 'Seluruh peserta berhasil mempraktekkan pemilahan sampah dan pembuatan takakura kompos.',
                'output' => '20 unit tong komposter dibagikan kepada kelompok ibu-ibu PKK.',
                'dampak' => 'Penurunan volume limbah organik rumah tangga sebesar 30% di pemukiman warga.',
            ],
            [
                'nama' => 'Workshop Pemberdayaan Usaha Olahan Kerupuk Ikan Pesisir',
                'jenis' => 'usaha masyarakat',
                'desa' => 'Desa Banjar Sari',
                'lat' => -2.4912,
                'lng' => 110.1711,
                'pj' => 'Hesty Yolanda',
                'kelompok' => 'Kelompok Wanita Tani (KWT) Banjar Mandiri',
                'target' => 30,
                'realisasi_peserta' => 32,
                'tgl_mulai' => '2026-03-12',
                'tgl_selesai' => '2026-03-14',
                'deskripsi' => 'Pelatihan peningkatan standar higienitas, pengemasan vakum, dan pemasaran digital hasil olahan ikan.',
                'tujuan' => 'Meningkatkan nilai jual dan jangkauan pemasaran produk olahan ikan hasil tangkapan nelayan.',
                'hasil' => 'Peserta mendapatkan sertifikasi pelatihan kemasan dan pemahaman branding produk.',
                'output' => '3 varian kemasan baru produk kerupuk ikan berstandar P-IRT.',
                'dampak' => 'Pendapatan anggota KWT meningkat rata-rata 25% pasca pelatihan.',
            ],
            [
                'nama' => 'Monitoring dan Pemetaan Terumbu Karang Karang Buaya',
                'jenis' => 'konservasi',
                'desa' => 'Pulau Pelapis',
                'lat' => -2.5200,
                'lng' => 110.1200,
                'pj' => 'M. Luqinul Mifdlol Assiddiqi',
                'kelompok' => 'Tim DIVE WeBe Konservasi',
                'target' => 15,
                'realisasi_peserta' => 15,
                'tgl_mulai' => '2026-04-18',
                'tgl_selesai' => '2026-04-20',
                'deskripsi' => 'Survei kondisi tutupan terumbu karang dan keanekaragaman hayati ikan karang di Perairan Karang Buaya.',
                'tujuan' => 'Mendapatkan data baseline kondisi kesehatan terumbu karang untuk rencana pemulihan.',
                'hasil' => 'Terpetakan area tutupan karang hidup seluas 4.2 hektar dengan kondisi sedang-baik.',
                'output' => 'Laporan peta spasial tutupan karang dan database spesies ikan indikator.',
                'dampak' => 'Teridentifikasinya zona perlindungan bahari lokal untuk masyarakat nelayan.',
            ],
            [
                'nama' => 'Sosialisasi Bahaya Sampah Plastik dan Aksi Bersih Pantai',
                'jenis' => 'edukasi',
                'desa' => 'Desa Sungai Air Hitam',
                'lat' => -2.4650,
                'lng' => 110.1890,
                'pj' => 'Anggun Safitri',
                'kelompok' => 'Pemuda Karang Taruna Sungai Air Hitam',
                'target' => 60,
                'realisasi_peserta' => 68,
                'tgl_mulai' => '2026-05-15',
                'tgl_selesai' => '2026-05-15',
                'deskripsi' => 'Kampanye pengurangan plastik sekali pakai dilanjutkan aksi coastal clean-up sepanjang garis pantai 2 km.',
                'tujuan' => 'Menjaga kebersihan pesisir dari anorganik laut dan membangun kebiasaan peduli lingkungan.',
                'hasil' => 'Terkumpul 450 kg sampah anorganik (terutama botol dan kantong plastik).',
                'output' => 'Garis pantai sepanjang 2 km bersih dari sampah makro-plastik.',
                'dampak' => 'Dibentuknya bank sampah pemuda desa untuk mengelola daur ulang limbah plastik.',
            ],
            [
                'nama' => 'Pelatihan Budidaya Kepiting Bakau Sistem Silvofishery',
                'jenis' => 'usaha masyarakat',
                'desa' => 'Desa Kendawangan Kiri',
                'lat' => -2.4860,
                'lng' => 110.1620,
                'pj' => 'Hesty Yolanda',
                'kelompok' => 'Kelompok Nelayan Tradisional Pesisir',
                'target' => 25,
                'realisasi_peserta' => 28,
                'tgl_mulai' => '2026-06-08',
                'tgl_selesai' => '2026-06-10',
                'deskripsi' => 'Praktek perakitan keramba budidaya kepiting soka terintegrasi dengan pelestarian hutan bakau.',
                'tujuan' => 'Memberikan alternatif mata pencaharian ramah lingkungan tanpa merusak ekosistem bakau.',
                'hasil' => 'Peserta mahir merakit keramba rajungan/kepiting ramah lingkungan.',
                'output' => '5 unit keramba percontohan silvofishery terpasang di alur muara.',
                'dampak' => 'Tersedianya penghasilan tambahan nelayan di luar musim melaut.',
            ]
        ];

        foreach ($dummyData as $idx => $data) {
            $user = $anggotaUsers[$idx % $anggotaUsers->count()];

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
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '16:00',
                'penanggung_jawab' => $user->name,
                'kelompok' => $data['kelompok'],
                'estimasi_peserta' => $data['target'],
                'rincian_kebutuhan' => '• Konsumsi peserta = Rp1.500.000<br>• ATK & Spanduk = Rp500.000<br>• Transportasi = Rp1.000.000',
                'status' => RencanaKegiatan::STATUS_SELESAI,
                'keterangan_status' => 'Kegiatan telah diselesaikan berdasarkan laporan final.',
            ]);

            LaporanKegiatan::create([
                'user_id' => $user->id,
                'rencana_kegiatan_id' => $rencana->uuid,
                'judul_kegiatan' => $data['nama'],
                'lokasi_kegiatan' => $data['desa'],
                'realisasi_tanggal_mulai' => $data['tgl_mulai'],
                'realisasi_tanggal_selesai' => $data['tgl_selesai'],
                'rangkaian_kegiatan' => '1. Pembukaan dan doa<br>2. Penyampaian materi & instruksi<br>3. Pelaksanaan aksi lapangan<br>4. Evaluasi & penutupan',
                'target_peserta' => $data['target'],
                'realisasi_peserta' => $data['realisasi_peserta'],
                'profil_peserta' => 'Warga lokal, tokoh masyarakat, dan anggota kelompok pemuda setempat.',
                'hasil_dicapai' => $data['hasil'],
                'output_nyata' => $data['output'],
                'dampak_awal' => $data['dampak'],
                'kendala' => 'Faktor cuaca pasang surut air laut di lokasi kegiatan.',
                'solusi' => 'Penyesuaian jadwal pelaksanaan mengikuti tabel pasang surut BMKG.',
                'evaluasi_rekomendasi' => 'Perlu dilakukan pendampingan berkala setiap 3 bulan.',
                'status' => LaporanKegiatan::STATUS_FINAL,
                'catatan_evaluasi' => 'Laporan lengkap dan disetujui oleh admin.',
            ]);
        }
    }
}
