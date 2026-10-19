<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\RencanaKegiatan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDraftRencanaDidoSeeder extends Seeder
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

        $dummyData = [
            [
                'nama' => 'Pembuatan Pupuk Garda Emak Cempedak Jaya',
                'jenis' => 'usaha masyarakat',
                'jenis_lainnya' => null,
                'pj' => 'EVYANDRI',
                'tgl_mulai' => '2026-07-14',
                'tgl_selesai' => '2026-07-15',
                'desa' => 'Desa Cempedak Jaya',
                'lat' => -2.48310000,
                'lng' => 110.16540000,
                'kelompok' => 'Kelompok Garda Emak Cempedak Jaya',
                'peserta' => 25,
                'deskripsi' => '<p>Program pelatihan dan pendampingan pembuatan pupuk organik padat dan cair oleh kelompok perempuan penggerak Garda Emak dengan memanfaatkan limbah pertanian dan rumah tangga.</p>',
                'tujuan' => '<p>Meningkatkan keterampilan pengolahan pupuk organik mandiri dan mendukung kemandirian ekonomi keluarga nelayan dan petani di Desa Cempedak Jaya.</p>',
                'rincian' => '<p>• Bahan baku pupuk (EM4, tetes tebu, bekatul): Rp1.200.000<br>• Tong komposter & peralatan: Rp1.500.000<br>• Konsumsi peserta pelatihan (2 hari): Rp1.000.000</p>'
            ],
            [
                'nama' => 'Gogo Mangrove',
                'jenis' => 'konservasi',
                'jenis_lainnya' => null,
                'pj' => 'Hartono',
                'tgl_mulai' => '2026-07-06',
                'tgl_selesai' => '2026-09-30',
                'desa' => 'Desa Kendawangan Kanan',
                'lat' => -2.49000000,
                'lng' => 110.17000000,
                'kelompok' => 'Pokmaswas Cempedak Lestari',
                'peserta' => 40,
                'deskripsi' => '<p>Gerakan gotong royong pembibitan, penanaman, dan perawatan mangrove di sepanjang sabuk hijau pantai Kendawangan guna menahan gelombang pasang dan abrasi.</p>',
                'tujuan' => '<p>Merehabilitasi area pesisir seluas 2 hektar dengan 2.000 bibit mangrove dan melibatkan masyarakat lokal dalam pemantauan pertumbuhan pohon bakau.</p>',
                'rincian' => '<p>• Pengadaan 2.000 bibit mangrove: Rp5.000.000<br>• Ajir bambu & tali pengikat: Rp1.000.000<br>• Konsumsi gotong royong relawan: Rp2.000.000</p>'
            ],
            [
                'nama' => 'Gogo Mangrove Tahap 2',
                'jenis' => 'konservasi',
                'jenis_lainnya' => null,
                'pj' => 'Hartono',
                'tgl_mulai' => '2026-07-06',
                'tgl_selesai' => '2026-07-31',
                'desa' => 'Desa Kendawangan Kiri',
                'lat' => -2.47500000,
                'lng' => 110.15000000,
                'kelompok' => 'Kelompok Relawan Konservasi Pesisir',
                'peserta' => 30,
                'deskripsi' => '<p>Kegiatan lanjutan penanaman mangrove di zona tambak terlantar untuk mengembalikan ekosistem muara sungai dan habitat kepiting bakau.</p>',
                'tujuan' => '<p>Memulihkan daya dukung lingkungan pesisir dan memproteksi muara dari sedimentasi berlebih.</p>',
                'rincian' => '<p>• Bibit bakau jenis Rhizophora: Rp3.000.000<br>• Sewa perahu angkut bibit: Rp1.500.000<br>• Konsumsi dan logistik lapangan: Rp1.200.000</p>'
            ],
            [
                'nama' => 'FGD Reviu Zonasi KKPD Kendawangan di Desa Pembedilan',
                'jenis' => 'konservasi',
                'jenis_lainnya' => null,
                'pj' => 'M Luqinul',
                'tgl_mulai' => '2026-09-06',
                'tgl_selesai' => '2026-09-06',
                'desa' => 'Desa Pembedilan',
                'lat' => -2.52000000,
                'lng' => 110.21000000,
                'kelompok' => 'Perwakilan Nelayan & Tokoh Masyarakat Desa Pembedilan',
                'peserta' => 35,
                'deskripsi' => '<p>Focus Group Discussion (FGD) bersama kelompok nelayan, kepala dusun, dan pemangku kepentingan desa untuk meninjau kembali kesepakatan tata batas zonasi kawasan konservasi perairan daerah (KKPD).</p>',
                'tujuan' => '<p>Mendapatkan masukan konkret dari masyarakat nelayan tradisional terhadap zona inti, zona perikanan berkelanjutan, dan zona pemanfaatan terbatas.</p>',
                'rincian' => '<p>• Sewa ruang pertemuan desa & sound: Rp500.000<br>• Honorarium narasumber: Rp1.000.000<br>• Konsumsi 35 peserta: Rp1.050.000<br>• ATK dan modul draft dokumen zonasi: Rp600.000</p>'
            ],
            [
                'nama' => 'Talkshow Pengelolaan Kawasan Konservasi dan Perairan Kalimantan Barat',
                'jenis' => 'konservasi',
                'jenis_lainnya' => null,
                'pj' => 'Anggun Safitri',
                'tgl_mulai' => '2026-08-15',
                'tgl_selesai' => '2026-08-15',
                'desa' => 'Pontianak',
                'lat' => -0.02630000,
                'lng' => 109.34250000,
                'kelompok' => 'Akademisi, LSM Lingkungan, & Mahasiswa Kalbar',
                'peserta' => 50,
                'deskripsi' => '<p>Penyelenggaraan talkshow hybrid mengangkat tema sinergi multipihak dalam menjaga keanekaragaman hayati laut dan kawasan konservasi perairan di Kalimantan Barat.</p>',
                'tujuan' => '<p>Meningkatkan kesadaran publik dan menyosialisasikan pentingnya pengelolaan kawasan konservasi perairan berbasis masyarakat kepada generasi muda dan pegiat lingkungan.</p>',
                'rincian' => '<p>• Sewa gedung & streaming setup: Rp2.500.000<br>• Honor narasumber ahli kelautan: Rp2.000.000<br>• Konsumsi dan seminar kit: Rp2.500.000</p>'
            ],
            [
                'nama' => 'Menghadiri Soft Launching Dan Reaktivasi PLB (Pos Lintas Batas)',
                'jenis' => 'lainnya',
                'jenis_lainnya' => 'Kegiatan bersama mitra',
                'pj' => 'Anggun Safitri',
                'tgl_mulai' => '2026-08-12',
                'tgl_selesai' => '2026-08-12',
                'desa' => 'Jagoi Babang',
                'lat' => 1.33400000,
                'lng' => 109.92700000,
                'kelompok' => 'Mitra Kolaborasi Antar Lembaga',
                'peserta' => 15,
                'deskripsi' => '<p>Partisipasi delegasi Yayasan WeBe dalam agenda peresmian dan reaktivasi pos lintas batas bersama instansi terkait untuk memperkuat jejaring kerja sama pengawasan perbatasan.</p>',
                'tujuan' => '<p>Membangun kemitraan strategis dalam edukasi kelestarian lingkungan dan pencegahan perdagangan ilegal satwa liar di wilayah perbatasan.</p>',
                'rincian' => '<p>• Transportasi darat PP: Rp1.800.000<br>• Akomodasi penginapan 2 malam: Rp1.200.000<br>• Uang saku dan konsumsi tim perwakilan: Rp1.000.000</p>'
            ],
            [
                'nama' => 'Pendampingan Wisata Pantai Tanjung Api',
                'jenis' => 'usaha masyarakat',
                'jenis_lainnya' => null,
                'pj' => 'Nica',
                'tgl_mulai' => '2026-08-22',
                'tgl_selesai' => '2026-08-22',
                'desa' => 'Pantai Tanjung Api',
                'lat' => 1.91600000,
                'lng' => 109.31700000,
                'kelompok' => 'Pokdarwis Tanjung Api Bersatu',
                'peserta' => 25,
                'deskripsi' => '<p>Pendampingan kelompok sadar wisata (Pokdarwis) dalam manajemen homestay ramah lingkungan, penyusunan paket ekowisata pesisir, serta standardisasi sanitasi kebersihan area pantai.</p>',
                'tujuan' => '<p>Memperkuat kapasitas pengelola wisata lokal agar mampu mengelola potensi Pantai Tanjung Api secara berkelanjutan tanpa merusak ekosistem pesisir.</p>',
                'rincian' => '<p>• Papan informasi edukasi ekowisata: Rp1.500.000<br>• Modul pelatihan hospitality pokdarwis: Rp750.000<br>• Konsumsi pelatihan: Rp800.000</p>'
            ],
            [
                'nama' => 'Pelatihan Penanganan Mamalia Laut Terdampar',
                'jenis' => 'edukasi',
                'jenis_lainnya' => null,
                'pj' => 'Setra Kusumardana',
                'tgl_mulai' => '2026-07-20',
                'tgl_selesai' => '2026-07-20',
                'desa' => 'Kendawangan',
                'lat' => -2.48600000,
                'lng' => 110.16200000,
                'kelompok' => 'Komunitas Nelayan & Relawan Penyelamat Mamalia Laut',
                'peserta' => 30,
                'deskripsi' => '<p>Bimbingan teknis first responder penanganan lumba-lumba, pesut, dan dugong yang terdampar hidup maupun mati, sesuai standar kode etik dan SOP medis veteriner satwa dilindungi.</p>',
                'tujuan' => '<p>Membekali nelayan dan relawan dengan keterampilan penyelamatan cepat mamalia laut terdampar agar tidak terluka atau dieksploitasi sembarangan.</p>',
                'rincian' => '<p>• Terpal penyelamat & perlengkapan basah: Rp1.500.000<br>• Perlengkapan P3K satwa: Rp800.000<br>• Konsumsi dan sertifikat pelatihan peserta: Rp1.200.000</p>'
            ],
            [
                'nama' => 'Inisiasi Bank Sampah Pesisir Bersama Garda Emak',
                'jenis' => 'usaha masyarakat',
                'jenis_lainnya' => null,
                'pj' => 'EVYANDRI',
                'tgl_mulai' => '2026-07-01',
                'tgl_selesai' => '2026-07-03',
                'desa' => 'Desa Cempedak Jaya',
                'lat' => -2.48200000,
                'lng' => 110.16800000,
                'kelompok' => 'Pengurus Bank Sampah Garda Emak',
                'peserta' => 20,
                'deskripsi' => '<p>Inisiasi pembentukan sistem tata kelola bank sampah warga pesisir, penyusunan buku tabungan nasabah, dan kerja sama penyaluran sampah anorganik terpilah dengan pengepul daur ulang.</p>',
                'tujuan' => '<p>Mengurangi timbulan sampah plastik laut dari muara serta menciptakan nilai tabungan ekonomi langsung bagi rumah tangga pesisir.</p>',
                'rincian' => '<p>• Timbangan digital & karung pilah: Rp1.000.000<br>• Buku tabungan nasabah & cap organisasi: Rp400.000<br>• Konsumsi rapat inisiasi 3 hari: Rp900.000</p>'
            ],
            [
                'nama' => 'Monitoring Tutupan Lamun dan Terumbu Karang Teluk Kendawangan',
                'jenis' => 'konservasi',
                'jenis_lainnya' => null,
                'pj' => 'M Luqinul',
                'tgl_mulai' => '2026-07-10',
                'tgl_selesai' => '2026-07-12',
                'desa' => 'Perairan Kendawangan',
                'lat' => -2.53000000,
                'lng' => 110.18000000,
                'kelompok' => 'Tim Selam Monitoring WeBe & Pokmaswas',
                'peserta' => 10,
                'deskripsi' => '<p>Survei biofisik bawah air menggunakan metode Underwater Visual Census (UVC) dan line transect untuk mengukur kepadatan tutupan padang lamun serta karang keras pasca musim timur.</p>',
                'tujuan' => '<p>Memperoleh data berkala kesehatan ekosistem habitat pesisir sebagai bahan laporan berkala evaluasi efektivitas kawasan konservasi.</p>',
                'rincian' => '<p>• Sewa kapal motor monitoring 3 hari: Rp3.000.000<br>• Pengisian tabung selam & logistik: Rp2.000.000<br>• Transect tape & slate bawah air: Rp800.000</p>'
            ],
        ];

        $insertedCount = 0;
        foreach ($dummyData as $data) {
            $exists = RencanaKegiatan::where('nama_kegiatan', $data['nama'])
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                continue;
            }

            RencanaKegiatan::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => $user->id,
                'nama_kegiatan' => $data['nama'],
                'jenis_kegiatan' => $data['jenis'],
                'jenis_kegiatan_lainnya' => $data['jenis_lainnya'],
                'deskripsi' => $data['deskripsi'],
                'tujuan' => $data['tujuan'],
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'desa' => $data['desa'],
                'tanggal_mulai' => $data['tgl_mulai'],
                'tanggal_selesai' => $data['tgl_selesai'],
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '16:00:00',
                'penanggung_jawab' => $data['pj'],
                'kelompok' => $data['kelompok'],
                'estimasi_peserta' => $data['peserta'],
                'rincian_kebutuhan' => $data['rincian'],
                'foto' => null,
                'dokumen' => null,
                'anggaran_kegiatan' => null,
                'status' => RencanaKegiatan::STATUS_DRAFT,
                'keterangan_status' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $insertedCount++;
        }

        if (isset($this->command)) {
            $this->command->info("Berhasil memasukkan {$insertedCount} data dummy rencana kegiatan draft untuk user: {$user->name} ({$user->email})");
        }
    }
}
