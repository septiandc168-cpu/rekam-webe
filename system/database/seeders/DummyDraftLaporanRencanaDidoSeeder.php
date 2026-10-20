<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\RencanaKegiatan;
use App\Models\LaporanKegiatan;
use Illuminate\Support\Str;

class DummyDraftLaporanRencanaDidoSeeder extends Seeder
{
    public function run()
    {
        // 1. Cari user dido
        $user = User::where('name', 'dido')
            ->orWhere('email', 'dido@gmail.com')
            ->first();

        if (!$user) {
            $user = User::where('name', 'like', '%dido%')->first();
        }

        if (!$user) {
            $roleAnggota = Role::where('role_name', 'anggota')->first();
            $user = User::create([
                'name' => 'dido',
                'email' => 'dido@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => $roleAnggota ? $roleAnggota->id : 2,
            ]);
        }

        // 2. Ambil 2 rencana kegiatan disetujui milik dido
        $rencana1 = RencanaKegiatan::where('user_id', $user->id)
            ->where('nama_kegiatan', 'Pelatihan Penanganan Mamalia Laut Terdampar')
            ->first();

        $rencana2 = RencanaKegiatan::where('user_id', $user->id)
            ->where('nama_kegiatan', 'Inisiasi Bank Sampah Pesisir Bersama Garda Emak')
            ->first();

        if (!$rencana1 || !$rencana2) {
            $approved = RencanaKegiatan::where('user_id', $user->id)
                ->where('status', RencanaKegiatan::STATUS_DISETUJUI)
                ->take(2)
                ->get();

            if ($approved->count() < 2) {
                $this->command->error("Tidak ditemukan minimal 2 rencana kegiatan disetujui milik dido.");
                return;
            }
            $rencana1 = $rencana1 ?: $approved[0];
            $rencana2 = $rencana2 ?: $approved[1];
        }

        $dummyReports = [
            [
                'rencana' => $rencana1,
                'judul' => 'Pelatihan Penanganan Mamalia Laut Terdampar',
                'lokasi' => 'Kendawangan, Kab. Ketapang',
                'tgl_mulai' => '2026-07-20',
                'tgl_selesai' => '2026-07-20',
                'target_peserta' => $rencana1->estimasi_peserta ?? 30,
                'realisasi_peserta' => 28,
                'profil_peserta' => '<p>Kelompok nelayan tangkap pesisir Kendawangan, pemuda karang taruna bahari, dan relawan penyelamat satwa laut perairan Kendawangan.</p>',
                'rangkaian' => '<ol><li>Pembukaan dan penyampaian materi pengenalan jenis mamalia laut dilindungi (dugong, lumba-lumba, paus) serta regulasi perlindungannya.</li><li>Simulasi teknik penanganan pertama mamalia laut terdampar hidup (cara menjaga kelembapan kulit, posisi lubang napas, dan penanganan stres).</li><li>Praktek evakuasi darurat menggunakan terpal tandu basah ke perairan yang lebih dalam.</li><li>Diskusi pembentukan call center jejaring informasi cepat tanggap warga pesisir.</li></ol>',
                'hasil' => '<p>Sebanyak 28 peserta memahami protokol dasar first responder penanganan mamalia laut terdampar serta terbentuknya kesepakatan koordinasi darurat dengan tim Yayasan WeBe dan BKSDA.</p>',
                'output' => '<ul><li>28 relawan dan nelayan tersertifikasi memiliki pemahaman dasar first responder mamalia laut.</li><li>Tersusunnya grup komunikasi siaga darurat mamalia laut wilayah pesisir Kendawangan.</li><li>1 unit SOP infografis penanganan satwa terdampar dibagikan ke tiap kelompok nelayan.</li></ul>',
                'dampak' => '<p>Meningkatnya kesiapsiagaan warga untuk tidak panik dan tidak memanfaatkan satwa mamalia laut yang terdampar secara ilegal, melainkan segera melakukan pertolongan pertama.</p>',
                'kendala' => '<p>Keterbatasan waktu sesi praktek lapangan karena kondisi pasang surut air laut yang cukup cepat di lokasi simulasi pantai.</p>',
                'solusi' => '<p>Praktek difokuskan pada tahapan krusial penanganan darurat di bibir pantai dan penandaan posisi koordinat GPS.</p>',
                'evaluasi' => '<p>Perlu pengadaan perlengkapan rescue kit permanen (terpal tandu apung dan pelampung evakuasi) yang disimpan di posko nelayan terdekat.</p>',
            ],
            [
                'rencana' => $rencana2,
                'judul' => 'Inisiasi Bank Sampah Pesisir Bersama Garda Emak',
                'lokasi' => 'Desa Cempedak Jaya, Kec. Kendawangan, Kab. Ketapang',
                'tgl_mulai' => '2026-07-01',
                'tgl_selesai' => '2026-07-03',
                'target_peserta' => $rencana2->estimasi_peserta ?? 20,
                'realisasi_peserta' => 22,
                'profil_peserta' => '<p>Ibu-ibu penggerak kelompok Garda Emak, perwakilan kader PKK Desa Cempedak Jaya, dan pegiat lingkungan setempat.</p>',
                'rangkaian' => '<ol><li>Sosialisasi konsep pemilahan sampah organik dan anorganik bernilai ekonomis bagi rumah tangga pesisir.</li><li>Pelatihan penimbangan, pencatatan buku tabungan nasabah bank sampah, dan penentuan katalog harga sampah daur ulang.</li><li>Penetapan jadwal penimbangan rutin mingguan dan kerja sama kemitraan dengan pengepul daur ulang lokal.</li><li>Simulasi operasional perdana penimbangan sampah plastik dan botol dari para nasabah awal.</li></ol>',
                'hasil' => '<p>Terbentuknya struktur kepengurusan resmi Bank Sampah Garda Emak dengan 22 nasabah awal terdaftar dan terkumpulnya 85 kg sampah anorganik terpilah pada penimbangan perdana.</p>',
                'output' => '<ul><li>1 unit struktur pengurus Bank Sampah Garda Emak terbentuk dan aktif.</li><li>22 buku tabungan nasabah bank sampah terbit dan mulai digunakan.</li><li>Kesepakatan jadwal operasional penimbangan rutin setiap hari Sabtu pagi.</li></ul>',
                'dampak' => '<p>Masyarakat mulai memilah sampah plastik dari dapur rumah masing-masing dan timbulan sampah plastik yang dibuang ke tepi pantai/muara mulai berkurang.</p>',
                'kendala' => '<p>Ruang penyimpanan sementara (gudang tampung) sampah terpilah sebelum dijemput pengepul masih terbatas kapasitasnya.</p>',
                'solusi' => '<p>Menyepakati frekuensi penjemputan oleh mitra pengepul dilakukan maksimal dua minggu sekali agar tidak terjadi penumpukan.</p>',
                'evaluasi' => '<p>Dibutuhkan tempat penampungan sampah anorganik yang terlindung dari hujan agar kardus dan plastik tetap kering dan nilai jualnya terjaga.</p>',
            ]
        ];

        foreach ($dummyReports as $item) {
            $rec = $item['rencana'];

            // Hapus laporan lama jika ada untuk rencana ini
            LaporanKegiatan::where('rencana_kegiatan_id', $rec->id)->delete();

            LaporanKegiatan::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => $user->id,
                'rencana_kegiatan_id' => $rec->id,
                'judul_kegiatan' => $item['judul'],
                'lokasi_kegiatan' => $item['lokasi'],
                'realisasi_tanggal_mulai' => $item['tgl_mulai'],
                'realisasi_tanggal_selesai' => $item['tgl_selesai'],
                'rangkaian_kegiatan' => $item['rangkaian'],
                'target_peserta' => $item['target_peserta'],
                'realisasi_peserta' => $item['realisasi_peserta'],
                'profil_peserta' => $item['profil_peserta'],
                'hasil_dicapai' => $item['hasil'],
                'output_nyata' => $item['output'],
                'dampak_awal' => $item['dampak'],
                'kendala' => $item['kendala'],
                'solusi' => $item['solusi'],
                'evaluasi_rekomendasi' => $item['evaluasi'],
                'foto_kegiatan' => null,
                'daftar_hadir' => null,
                'notulen' => null,
                'materi' => null,
                'berita_acara' => null,
                'status' => 'draft',
                'catatan_evaluasi' => null,
            ]);
        }
    }
}
