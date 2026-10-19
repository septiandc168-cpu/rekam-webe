-- ============================================================
-- RekamWeBe - 10 Data Dummy Rencana Kegiatan Draft (Akun: dido)
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Pastikan akun user dido tersedia
INSERT INTO `users` (`id`, `uuid`, `role_id`, `name`, `email`, `password`, `created_at`, `updated_at`)
SELECT 4, UUID(), 2, 'dido', 'dido@gmail.com', '$2y$12$iV4a7CyI90cqmD9hz3n.quRhbyIDa.VM2GSq3rL9knwhYtYTyhHmK', NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `users` WHERE `name` = 'dido' OR `email` = 'dido@gmail.com')
LIMIT 1;

-- 2. Ambil ID user dido
SET @dido_id = (SELECT `id` FROM `users` WHERE `name` = 'dido' OR `email` = 'dido@gmail.com' LIMIT 1);

-- 3. Hapus dummy yang sama jika sudah pernah ada (mencegah duplikat)
DELETE FROM `rencana_kegiatans` WHERE `user_id` = @dido_id AND `nama_kegiatan` IN (
  'Pembuatan Pupuk Garda Emak Cempedak Jaya',
  'Gogo Mangrove',
  'Gogo Mangrove Tahap 2',
  'FGD Reviu Zonasi KKPD Kendawangan di Desa Pembedilan',
  'Talkshow Pengelolaan Kawasan Konservasi dan Perairan Kalimantan Barat',
  'Menghadiri Soft Launching Dan Reaktivasi PLB (Pos Lintas Batas)',
  'Pendampingan Wisata Pantai Tanjung Api',
  'Pelatihan Penanganan Mamalia Laut Terdampar',
  'Inisiasi Bank Sampah Pesisir Bersama Garda Emak',
  'Monitoring Tutupan Lamun dan Terumbu Karang Teluk Kendawangan'
);

-- 4. Masukkan 10 Data Dummy Rencana Kegiatan berstatus draft
INSERT INTO `rencana_kegiatans` (
  `user_id`, `uuid`, `nama_kegiatan`, `jenis_kegiatan`, `jenis_kegiatan_lainnya`,
  `deskripsi`, `tujuan`, `lat`, `lng`, `desa`,
  `tanggal_mulai`, `tanggal_selesai`, `waktu_mulai`, `waktu_selesai`,
  `penanggung_jawab`, `kelompok`, `estimasi_peserta`, `rincian_kebutuhan`,
  `foto`, `dokumen`, `anggaran_kegiatan`, `status`, `keterangan_status`,
  `created_at`, `updated_at`
) VALUES
(
  @dido_id, UUID(), 'Pembuatan Pupuk Garda Emak Cempedak Jaya', 'usaha masyarakat', NULL,
  '<p>Program pelatihan dan pendampingan pembuatan pupuk organik padat dan cair oleh kelompok perempuan penggerak Garda Emak dengan memanfaatkan limbah pertanian dan rumah tangga.</p>',
  '<p>Meningkatkan keterampilan pengolahan pupuk organik mandiri dan mendukung kemandirian ekonomi keluarga nelayan dan petani di Desa Cempedak Jaya.</p>',
  -2.48310000, 110.16540000, 'Desa Cempedak Jaya',
  '2026-07-14', '2026-07-15', '08:00:00', '16:00:00',
  'EVYANDRI', 'Kelompok Garda Emak Cempedak Jaya', 25,
  '<p>• Bahan baku pupuk (EM4, tetes tebu, bekatul): Rp1.200.000<br>• Tong komposter & peralatan: Rp1.500.000<br>• Konsumsi peserta pelatihan (2 hari): Rp1.000.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'Gogo Mangrove', 'konservasi', NULL,
  '<p>Gerakan gotong royong pembibitan, penanaman, dan perawatan mangrove di sepanjang sabuk hijau pantai Kendawangan guna menahan gelombang pasang dan abrasi.</p>',
  '<p>Merehabilitasi area pesisir seluas 2 hektar dengan 2.000 bibit mangrove dan melibatkan masyarakat lokal dalam pemantauan pertumbuhan pohon bakau.</p>',
  -2.49000000, 110.17000000, 'Desa Kendawangan Kanan',
  '2026-07-06', '2026-09-30', '08:00:00', '16:00:00',
  'Hartono', 'Pokmaswas Cempedak Lestari', 40,
  '<p>• Pengadaan 2.000 bibit mangrove: Rp5.000.000<br>• Ajir bambu & tali pengikat: Rp1.000.000<br>• Konsumsi gotong royong relawan: Rp2.000.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'Gogo Mangrove Tahap 2', 'konservasi', NULL,
  '<p>Kegiatan lanjutan penanaman mangrove di zona tambak terlantar untuk mengembalikan ekosistem muara sungai dan habitat kepiting bakau.</p>',
  '<p>Memulihkan daya dukung lingkungan pesisir dan memproteksi muara dari sedimentasi berlebih.</p>',
  -2.47500000, 110.15000000, 'Desa Kendawangan Kiri',
  '2026-07-06', '2026-07-31', '08:00:00', '16:00:00',
  'Hartono', 'Kelompok Relawan Konservasi Pesisir', 30,
  '<p>• Bibit bakau jenis Rhizophora: Rp3.000.000<br>• Sewa perahu angkut bibit: Rp1.500.000<br>• Konsumsi dan logistik lapangan: Rp1.200.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'FGD Reviu Zonasi KKPD Kendawangan di Desa Pembedilan', 'konservasi', NULL,
  '<p>Focus Group Discussion (FGD) bersama kelompok nelayan, kepala dusun, dan pemangku kepentingan desa untuk meninjau kembali kesepakatan tata batas zonasi kawasan konservasi perairan daerah (KKPD).</p>',
  '<p>Mendapatkan masukan konkret dari masyarakat nelayan tradisional terhadap zona inti, zona perikanan berkelanjutan, dan zona pemanfaatan terbatas.</p>',
  -2.52000000, 110.21000000, 'Desa Pembedilan',
  '2026-09-06', '2026-09-06', '08:00:00', '16:00:00',
  'M Luqinul', 'Perwakilan Nelayan & Tokoh Masyarakat Desa Pembedilan', 35,
  '<p>• Sewa ruang pertemuan desa & sound: Rp500.000<br>• Honorarium narasumber: Rp1.000.000<br>• Konsumsi 35 peserta: Rp1.050.000<br>• ATK dan modul draft dokumen zonasi: Rp600.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'Talkshow Pengelolaan Kawasan Konservasi dan Perairan Kalimantan Barat', 'konservasi', NULL,
  '<p>Penyelenggaraan talkshow hybrid mengangkat tema sinergi multipihak dalam menjaga keanekaragaman hayati laut dan kawasan konservasi perairan di Kalimantan Barat.</p>',
  '<p>Meningkatkan kesadaran publik dan menyosialisasikan pentingnya pengelolaan kawasan konservasi perairan berbasis masyarakat kepada generasi muda dan pegiat lingkungan.</p>',
  -0.02630000, 109.34250000, 'Pontianak',
  '2026-08-15', '2026-08-15', '08:00:00', '16:00:00',
  'Anggun Safitri', 'Akademisi, LSM Lingkungan, & Mahasiswa Kalbar', 50,
  '<p>• Sewa gedung & streaming setup: Rp2.500.000<br>• Honor narasumber ahli kelautan: Rp2.000.000<br>• Konsumsi dan seminar kit: Rp2.500.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'Menghadiri Soft Launching Dan Reaktivasi PLB (Pos Lintas Batas)', 'lainnya', 'Kegiatan bersama mitra',
  '<p>Partisipasi delegasi Yayasan WeBe dalam agenda peresmian dan reaktivasi pos lintas batas bersama instansi terkait untuk memperkuat jejaring kerja sama pengawasan perbatasan.</p>',
  '<p>Membangun kemitraan strategis dalam edukasi kelestarian lingkungan dan pencegahan perdagangan ilegal satwa liar di wilayah perbatasan.</p>',
  1.33400000, 109.92700000, 'Jagoi Babang',
  '2026-08-12', '2026-08-12', '08:00:00', '16:00:00',
  'Anggun Safitri', 'Mitra Kolaborasi Antar Lembaga', 15,
  '<p>• Transportasi darat PP: Rp1.800.000<br>• Akomodasi penginapan 2 malam: Rp1.200.000<br>• Uang saku dan konsumsi tim perwakilan: Rp1.000.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'Pendampingan Wisata Pantai Tanjung Api', 'usaha masyarakat', NULL,
  '<p>Pendampingan kelompok sadar wisata (Pokdarwis) dalam manajemen homestay ramah lingkungan, penyusunan paket ekowisata pesisir, serta standardisasi sanitasi kebersihan area pantai.</p>',
  '<p>Memperkuat kapasitas pengelola wisata lokal agar mampu mengelola potensi Pantai Tanjung Api secara berkelanjutan tanpa merusak ekosistem pesisir.</p>',
  1.91600000, 109.31700000, 'Pantai Tanjung Api',
  '2026-08-22', '2026-08-22', '08:00:00', '16:00:00',
  'Nica', 'Pokdarwis Tanjung Api Bersatu', 25,
  '<p>• Papan informasi edukasi ekowisata: Rp1.500.000<br>• Modul pelatihan hospitality pokdarwis: Rp750.000<br>• Konsumsi pelatihan: Rp800.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'Pelatihan Penanganan Mamalia Laut Terdampar', 'edukasi', NULL,
  '<p>Bimbingan teknis first responder penanganan lumba-lumba, pesut, dan dugong yang terdampar hidup maupun mati, sesuai standar kode etik dan SOP medis veteriner satwa dilindungi.</p>',
  '<p>Membekali nelayan dan relawan dengan keterampilan penyelamatan cepat mamalia laut terdampar agar tidak terluka atau dieksploitasi sembarangan.</p>',
  -2.48600000, 110.16200000, 'Kendawangan',
  '2026-07-20', '2026-07-20', '08:00:00', '16:00:00',
  'Setra Kusumardana', 'Komunitas Nelayan & Relawan Penyelamat Mamalia Laut', 30,
  '<p>• Terpal penyelamat & perlengkapan basah: Rp1.500.000<br>• Perlengkapan P3K satwa: Rp800.000<br>• Konsumsi dan sertifikat pelatihan peserta: Rp1.200.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'Inisiasi Bank Sampah Pesisir Bersama Garda Emak', 'usaha masyarakat', NULL,
  '<p>Inisiasi pembentukan sistem tata kelola bank sampah warga pesisir, penyusunan buku tabungan nasabah, dan kerja sama penyaluran sampah anorganik terpilah dengan pengepul daur ulang.</p>',
  '<p>Mengurangi timbulan sampah plastik laut dari muara serta menciptakan nilai tabungan ekonomi langsung bagi rumah tangga pesisir.</p>',
  -2.48200000, 110.16800000, 'Desa Cempedak Jaya',
  '2026-07-01', '2026-07-03', '08:00:00', '16:00:00',
  'EVYANDRI', 'Pengurus Bank Sampah Garda Emak', 20,
  '<p>• Timbangan digital & karung pilah: Rp1.000.000<br>• Buku tabungan nasabah & cap organisasi: Rp400.000<br>• Konsumsi rapat inisiasi 3 hari: Rp900.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), 'Monitoring Tutupan Lamun dan Terumbu Karang Teluk Kendawangan', 'konservasi', NULL,
  '<p>Survei biofisik bawah air menggunakan metode Underwater Visual Census (UVC) dan line transect untuk mengukur kepadatan tutupan padang lamun serta karang keras pasca musim timur.</p>',
  '<p>Memperoleh data berkala kesehatan ekosistem habitat pesisir sebagai bahan laporan berkala evaluasi efektivitas kawasan konservasi.</p>',
  -2.53000000, 110.18000000, 'Perairan Kendawangan',
  '2026-07-10', '2026-07-12', '08:00:00', '16:00:00',
  'M Luqinul', 'Tim Selam Monitoring WeBe & Pokmaswas', 10,
  '<p>• Sewa kapal motor monitoring 3 hari: Rp3.000.000<br>• Pengisian tabung selam & logistik: Rp2.000.000<br>• Transect tape & slate bawah air: Rp800.000</p>',
  NULL, NULL, NULL, 'draft', NULL,
  NOW(), NOW()
);

SET FOREIGN_KEY_CHECKS = 1;
