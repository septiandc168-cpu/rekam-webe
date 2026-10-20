<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\LaporanKegiatan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDraftLaporanLangsungDidoSeeder extends Seeder
{
    public function run()
    {
        // 1. Cari atau buat user dido
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

        $dummyLaporanLangsung = [
            [
                'judul' => 'Evakuasi dan Penyelamatan Penyu Hijau Terdampar di Pantai Jambat Kendawangan',
                'lokasi' => 'Pantai Jambat, Desa Kendawangan Kiri, Kab. Ketapang',
                'tgl_mulai' => '2026-10-15',
                'tgl_selesai' => '2026-10-15',
                'target_peserta' => 0,
                'realisasi_peserta' => 12,
                'profil_peserta' => '<p>Tim respon cepat Yayasan WeBe, nelayan lokal Pantai Jambat, dan petugas BKSDA Pos Kendawangan.</p>',
                'rangkaian' => '<ol><li>Menerima laporan darurat dari kelompok nelayan setempat mengenai penyu hijau terdampar dengan sirip terlilit sisa jaring hanyut.</li><li>Tim respon cepat bergerak ke lokasi membawa perlengkapan rescue dan terpal basah.</li><li>Melakukan identifikasi spesies, pengukuran morfometrik, dan pembersihan lilitan jaring secara cermat.</li><li>Pemeriksaan kesehatan fisik serta observasi respon gerak penyu di air dangkal.</li><li>Pelepasliaran kembali ke laut lepas di perairan yang aman dari jaring nelayan.</li></ol>',
                'hasil' => '<p>Satu ekor penyu hijau (Chelonia mydas) dengan panjang karapas 82 cm dan lebar 74 cm berhasil diselamatkan dari jeratan jaring dan dilepasliarkan kembali ke alam dalam kondisi sehat dan aktif.</p>',
                'output' => '<ul><li>1 ekor penyu hijau terselamatkan dan kembali ke habitat alaminya.</li><li>Data morfometrik dan dokumentasi kejadian tercatat rapi untuk laporan konservasi satwa laut dilindungi.</li></ul>',
                'dampak' => '<p>Meningkatnya kesadaran dan kepedulian para nelayan Pantai Jambat untuk langsung menghubungi tim relawan jika menemukan satwa laut yang terdampar atau terjebak jaring.</p>',
                'kendala' => '<p>Kondisi pantai berbatu karang yang cukup licin memerlukan kehati-hatian ekstra saat evakuasi agar tidak mencederai tempurung penyu.</p>',
                'solusi' => '<p>Menggunakan tandu matras terpal basah yang dipikul bersama oleh 4 orang untuk memindahkan penyu ke area berpasir lembut.</p>',
                'evaluasi' => '<p>Perlu disiapkan rescue kit standar penanganan penyu di sekretariat Pokmaswas terdekat agar respon awal warga bisa lebih cepat.</p>',
            ],
            [
                'judul' => 'Pembersihan Darurat Tumpahan Limbah Minyak di Pesisir Muara Sungai Kendawangan',
                'lokasi' => 'Muara Sungai Kendawangan, Desa Kendawangan Kanan, Kab. Ketapang',
                'tgl_mulai' => '2026-10-18',
                'tgl_selesai' => '2026-10-19',
                'target_peserta' => 0,
                'realisasi_peserta' => 25,
                'profil_peserta' => '<p>Relawan pemuda pesisir Kendawangan, anggota Pokmaswas Cempedak Lestari, dan warga RT 04 Desa Kendawangan Kanan.</p>',
                'rangkaian' => '<ol><li>Verifikasi lokasi ceceran limbah minyak solar yang mencemari tepian muara dan sabuk mangrove.</li><li>Pemasangan pembatas darurat menggunakan karung berisi sekam padi dan sabut kelapa untuk melokalisir sebaran minyak.</li><li>Pembersihan dan pengangkatan lapisan minyak terapung menggunakan serokan khusus secara manual.</li><li>Pembersihan lumpur berlumur minyak di perakaran bibit bakau.</li><li>Pengemasan limbah terkumpul ke dalam drum kedap untuk dikirim ke tempat pengolahan limbah B3.</li></ol>',
                'hasil' => '<p>Area pesisir muara sepanjang 250 meter berhasil dilokalisir dan dibersihkan dari lapisan minyak, mencegah kerusakan parah pada 500 bibit mangrove yang baru ditanam.</p>',
                'output' => '<ul><li>3 drum limbah minyak dan sedimen terkontaminasi berhasil diamankan.</li><li>Area vegetasi mangrove dan tambak warga di sekitar muara terselamatkan dari kontaminasi solar.</li></ul>',
                'dampak' => '<p>Kualitas air muara kembali normal dan aroma bahan bakar yang mengganggu pemukiman nelayan sekitar muara berhasil dihilangkan.</p>',
                'kendala' => '<p>Keterbatasan peralatan oil absorbent boom pabrikan di lokasi pada saat kejadian darurat.</p>',
                'solusi' => '<p>Menggunakan inovasi lokal berbahan sekam padi dan sabut kelapa kering dalam karung goni yang terbukti efektif menyerap minyak di permukaan air.</p>',
                'evaluasi' => '<p>Diperlukan sosialisasi kepada kapal-kapal motor nelayan agar tidak membuang sisa oli/solar sembarangan di perairan muara sungai.</p>',
            ]
        ];

        $insertedCount = 0;
        foreach ($dummyLaporanLangsung as $data) {
            $exists = LaporanKegiatan::where('judul_kegiatan', $data['judul'])
                ->where('user_id', $user->id)
                ->whereNull('rencana_kegiatan_id')
                ->exists();

            if ($exists) {
                continue;
            }

            LaporanKegiatan::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => $user->id,
                'rencana_kegiatan_id' => null, // Laporan Langsung (Tanpa Rencana)
                'judul_kegiatan' => $data['judul'],
                'lokasi_kegiatan' => $data['lokasi'],
                'realisasi_tanggal_mulai' => $data['tgl_mulai'],
                'realisasi_tanggal_selesai' => $data['tgl_selesai'],
                'rangkaian_kegiatan' => $data['rangkaian'],
                'target_peserta' => $data['target_peserta'],
                'realisasi_peserta' => $data['realisasi_peserta'],
                'profil_peserta' => $data['profil_peserta'],
                'hasil_dicapai' => $data['hasil'],
                'output_nyata' => $data['output'],
                'dampak_awal' => $data['dampak'],
                'kendala' => $data['kendala'],
                'solusi' => $data['solusi'],
                'evaluasi_rekomendasi' => $data['evaluasi'],
                'foto_kegiatan' => null,
                'daftar_hadir' => null,
                'notulen' => null,
                'materi' => null,
                'berita_acara' => null,
                'status' => LaporanKegiatan::STATUS_DRAFT,
                'catatan_evaluasi' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $insertedCount++;
        }

        if (isset($this->command)) {
            $this->command->info("Berhasil menambahkan {$insertedCount} data dummy laporan langsung berstatus draft untuk user: {$user->name} ({$user->email})");
        }
    }
}
