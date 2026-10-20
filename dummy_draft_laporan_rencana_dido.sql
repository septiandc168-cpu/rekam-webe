-- =========================================================================
-- RekamWeBe - 2 Data Dummy Laporan Kegiatan Draft dari Rencana Disetujui
-- Akun: dido
-- =========================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ambil ID user dido
SET @dido_id = (SELECT `id` FROM `users` WHERE `name` = 'dido' OR `email` = 'dido@gmail.com' LIMIT 1);

-- 2. Ambil ID rencana kegiatan yang disetujui milik dido
SET @rencana_1_id = (
  SELECT `id` FROM `rencana_kegiatans` 
  WHERE `user_id` = @dido_id 
    AND `nama_kegiatan` = 'Pelatihan Penanganan Mamalia Laut Terdampar' 
    AND `status` = 'disetujui'
  LIMIT 1
);

SET @rencana_2_id = (
  SELECT `id` FROM `rencana_kegiatans` 
  WHERE `user_id` = @dido_id 
    AND `nama_kegiatan` = 'Inisiasi Bank Sampah Pesisir Bersama Garda Emak' 
    AND `status` = 'disetujui'
  LIMIT 1
);

-- Fallback jika nama kegiatan sedikit berbeda: ambil 2 rencana disetujui pertama milik dido
SET @rencana_1_id = IFNULL(@rencana_1_id, (SELECT `id` FROM `rencana_kegiatans` WHERE `user_id` = @dido_id AND `status` = 'disetujui' ORDER BY `id` ASC LIMIT 1));
SET @rencana_2_id = IFNULL(@rencana_2_id, (SELECT `id` FROM `rencana_kegiatans` WHERE `user_id` = @dido_id AND `status` = 'disetujui' AND `id` != @rencana_1_id ORDER BY `id` ASC LIMIT 1));

-- 3. Hapus laporan sebelumnya untuk 2 rencana ini jika ada (mencegah duplikat)
DELETE FROM `laporan_kegiatans` WHERE `rencana_kegiatan_id` IN (@rencana_1_id, @rencana_2_id);

-- 4. Masukkan Laporan 1: Pelatihan Penanganan Mamalia Laut Terdampar
INSERT INTO `laporan_kegiatans` (
  `user_id`, `uuid`, `rencana_kegiatan_id`, `judul_kegiatan`, `lokasi_kegiatan`,
  `realisasi_tanggal_mulai`, `realisasi_tanggal_selesai`,
  `rangkaian_kegiatan`, `target_peserta`, `realisasi_peserta`, `profil_peserta`,
  `hasil_dicapai`, `output_nyata`, `dampak_awal`,
  `kendala`, `solusi`, `evaluasi_rekomendasi`,
  `foto_kegiatan`, `daftar_hadir`, `notulen`, `materi`, `berita_acara`,
  `status`, `catatan_evaluasi`,
  `created_at`, `updated_at`
)
SELECT 
  @dido_id, UUID(), @rencana_1_id,
  'Pelatihan Penanganan Mamalia Laut Terdampar',
  'Kendawangan, Kab. Ketapang',
  '2026-07-20', '2026-07-20',
  '<ol><li>Pembukaan dan penyampaian materi pengenalan jenis mamalia laut dilindungi (dugong, lumba-lumba, paus) serta regulasi perlindungannya.</li><li>Simulasi teknik penanganan pertama mamalia laut terdampar hidup (cara menjaga kelembapan kulit, posisi lubang napas, dan penanganan stres).</li><li>Praktek evakuasi darurat menggunakan terpal tandu basah ke perairan yang lebih dalam.</li><li>Diskusi pembentukan call center jejaring informasi cepat tanggap warga pesisir.</li></ol>',
  30, 28,
  '<p>Kelompok nelayan tangkap pesisir Kendawangan, pemuda karang taruna bahari, dan relawan penyelamat satwa laut perairan Kendawangan.</p>',
  '<p>Sebanyak 28 peserta memahami protokol dasar first responder penanganan mamalia laut terdampar serta terbentuknya kesepakatan koordinasi darurat dengan tim Yayasan WeBe dan BKSDA.</p>',
  '<ul><li>28 relawan dan nelayan tersertifikasi memiliki pemahaman dasar first responder mamalia laut.</li><li>Tersusunnya grup komunikasi siaga darurat mamalia laut wilayah pesisir Kendawangan.</li><li>1 unit SOP infografis penanganan satwa terdampar dibagikan ke tiap kelompok nelayan.</li></ul>',
  '<p>Meningkatnya kesiapsiagaan warga untuk tidak panik dan tidak memanfaatkan satwa mamalia laut yang terdampar secara ilegal, melainkan segera melakukan pertolongan pertama.</p>',
  '<p>Keterbatasan waktu sesi praktek lapangan karena kondisi pasang surut air laut yang cukup cepat di lokasi simulasi pantai.</p>',
  '<p>Praktek difokuskan pada tahapan krusial penanganan darurat di bibir pantai dan penandaan posisi koordinat GPS.</p>',
  '<p>Perlu pengadaan perlengkapan rescue kit permanen (terpal tandu apung dan pelampung evakuasi) yang disimpan di posko nelayan terdekat.</p>',
  NULL, NULL, NULL, NULL, NULL,
  'draft', NULL,
  NOW(), NOW()
WHERE @rencana_1_id IS NOT NULL;

-- 5. Masukkan Laporan 2: Inisiasi Bank Sampah Pesisir Bersama Garda Emak
INSERT INTO `laporan_kegiatans` (
  `user_id`, `uuid`, `rencana_kegiatan_id`, `judul_kegiatan`, `lokasi_kegiatan`,
  `realisasi_tanggal_mulai`, `realisasi_tanggal_selesai`,
  `rangkaian_kegiatan`, `target_peserta`, `realisasi_peserta`, `profil_peserta`,
  `hasil_dicapai`, `output_nyata`, `dampak_awal`,
  `kendala`, `solusi`, `evaluasi_rekomendasi`,
  `foto_kegiatan`, `daftar_hadir`, `notulen`, `materi`, `berita_acara`,
  `status`, `catatan_evaluasi`,
  `created_at`, `updated_at`
)
SELECT 
  @dido_id, UUID(), @rencana_2_id,
  'Inisiasi Bank Sampah Pesisir Bersama Garda Emak',
  'Desa Cempedak Jaya, Kec. Kendawangan, Kab. Ketapang',
  '2026-07-01', '2026-07-03',
  '<ol><li>Sosialisasi konsep pemilahan sampah organik dan anorganik bernilai ekonomis bagi rumah tangga pesisir.</li><li>Pelatihan penimbangan, pencatatan buku tabungan nasabah bank sampah, dan penentuan katalog harga sampah daur ulang.</li><li>Penetapan jadwal penimbangan rutin mingguan dan kerja sama kemitraan dengan pengepul daur ulang lokal.</li><li>Simulasi operasional perdana penimbangan sampah plastik dan botol dari para nasabah awal.</li></ol>',
  20, 22,
  '<p>Ibu-ibu penggerak kelompok Garda Emak, perwakilan kader PKK Desa Cempedak Jaya, dan pegiat lingkungan setempat.</p>',
  '<p>Terbentuknya struktur kepengurusan resmi Bank Sampah Garda Emak dengan 22 nasabah awal terdaftar dan terkumpulnya 85 kg sampah anorganik terpilah pada penimbangan perdana.</p>',
  '<ul><li>1 unit struktur pengurus Bank Sampah Garda Emak terbentuk dan aktif.</li><li>22 buku tabungan nasabah bank sampah terbit dan mulai digunakan.</li><li>Kesepakatan jadwal operasional penimbangan rutin setiap hari Sabtu pagi.</li></ul>',
  '<p>Masyarakat mulai memilah sampah plastik dari dapur rumah masing-masing dan timbulan sampah plastik yang dibuang ke tepi pantai/muara mulai berkurang.</p>',
  '<p>Ruang penyimpanan sementara (gudang tampung) sampah terpilah sebelum dijemput pengepul masih terbatas kapasitasnya.</p>',
  '<p>Menyepakati frekuensi penjemputan oleh mitra pengepul dilakukan maksimal dua minggu sekali agar tidak terjadi penumpukan.</p>',
  '<p>Dibutuhkan tempat penampungan sampah anorganik yang terlindung dari hujan agar kardus dan plastik tetap kering dan nilai jualnya terjaga.</p>',
  NULL, NULL, NULL, NULL, NULL,
  'draft', NULL,
  NOW(), NOW()
WHERE @rencana_2_id IS NOT NULL;

SET FOREIGN_KEY_CHECKS = 1;
