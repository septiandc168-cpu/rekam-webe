-- ============================================================
-- RekamWeBe - 2 Data Dummy Laporan Langsung Draft (Akun: dido)
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
DELETE FROM `laporan_kegiatans` WHERE `user_id` = @dido_id AND `rencana_kegiatan_id` IS NULL AND `judul_kegiatan` IN (
  'Evakuasi dan Penyelamatan Penyu Hijau Terdampar di Pantai Jambat Kendawangan',
  'Pembersihan Darurat Tumpahan Limbah Minyak di Pesisir Muara Sungai Kendawangan'
);

-- 4. Masukkan 2 Data Dummy Laporan Langsung berstatus draft
INSERT INTO `laporan_kegiatans` (
  `user_id`, `uuid`, `rencana_kegiatan_id`, `judul_kegiatan`, `lokasi_kegiatan`,
  `realisasi_tanggal_mulai`, `realisasi_tanggal_selesai`,
  `rangkaian_kegiatan`, `target_peserta`, `realisasi_peserta`, `profil_peserta`,
  `hasil_dicapai`, `output_nyata`, `dampak_awal`,
  `kendala`, `solusi`, `evaluasi_rekomendasi`,
  `foto_kegiatan`, `daftar_hadir`, `notulen`, `materi`, `berita_acara`,
  `status`, `catatan_evaluasi`,
  `created_at`, `updated_at`
) VALUES
(
  @dido_id, UUID(), NULL,
  'Evakuasi dan Penyelamatan Penyu Hijau Terdampar di Pantai Jambat Kendawangan',
  'Pantai Jambat, Desa Kendawangan Kiri, Kab. Ketapang',
  '2026-10-15', '2026-10-15',
  '<ol><li>Menerima laporan darurat dari kelompok nelayan setempat mengenai penyu hijau terdampar dengan sirip terlilit sisa jaring hanyut.</li><li>Tim respon cepat bergerak ke lokasi membawa perlengkapan rescue dan terpal basah.</li><li>Melakukan identifikasi spesies, pengukuran morfometrik, dan pembersihan lilitan jaring secara cermat.</li><li>Pemeriksaan kesehatan fisik serta observasi respon gerak penyu di air dangkal.</li><li>Pelepasliaran kembali ke laut lepas di perairan yang aman dari jaring nelayan.</li></ol>',
  0, 12,
  '<p>Tim respon cepat Yayasan WeBe, nelayan lokal Pantai Jambat, dan petugas BKSDA Pos Kendawangan.</p>',
  '<p>Satu ekor penyu hijau (Chelonia mydas) dengan panjang karapas 82 cm dan lebar 74 cm berhasil diselamatkan dari jeratan jaring dan dilepasliarkan kembali ke alam dalam kondisi sehat dan aktif.</p>',
  '<ul><li>1 ekor penyu hijau terselamatkan dan kembali ke habitat alaminya.</li><li>Data morfometrik dan dokumentasi kejadian tercatat rapi untuk laporan konservasi satwa laut dilindungi.</li></ul>',
  '<p>Meningkatnya kesadaran dan kepedulian para nelayan Pantai Jambat untuk langsung menghubungi tim relawan jika menemukan satwa laut yang terdampar atau terjebak jaring.</p>',
  '<p>Kondisi pantai berbatu karang yang cukup licin memerlukan kehati-hatian ekstra saat evakuasi agar tidak mencederai tempurung penyu.</p>',
  '<p>Menggunakan tandu matras terpal basah yang dipikul bersama oleh 4 orang untuk memindahkan penyu ke area berpasir lembut.</p>',
  '<p>Perlu disiapkan rescue kit standar penanganan penyu di sekretariat Pokmaswas terdekat agar respon awal warga bisa lebih cepat.</p>',
  NULL, NULL, NULL, NULL, NULL,
  'draft', NULL,
  NOW(), NOW()
),
(
  @dido_id, UUID(), NULL,
  'Pembersihan Darurat Tumpahan Limbah Minyak di Pesisir Muara Sungai Kendawangan',
  'Muara Sungai Kendawangan, Desa Kendawangan Kanan, Kab. Ketapang',
  '2026-10-18', '2026-10-19',
  '<ol><li>Verifikasi lokasi ceceran limbah minyak solar yang mencemari tepian muara dan sabuk mangrove.</li><li>Pemasangan pembatas darurat menggunakan karung berisi sekam padi dan sabut kelapa untuk melokalisir sebaran minyak.</li><li>Pembersihan dan pengangkatan lapisan minyak terapung menggunakan serokan khusus secara manual.</li><li>Pembersihan lumpur berlumur minyak di perakaran bibit bakau.</li><li>Pengemasan limbah terkumpul ke dalam drum kedap untuk dikirim ke tempat pengolahan limbah B3.</li></ol>',
  0, 25,
  '<p>Relawan pemuda pesisir Kendawangan, anggota Pokmaswas Cempedak Lestari, dan warga RT 04 Desa Kendawangan Kanan.</p>',
  '<p>Area pesisir muara sepanjang 250 meter berhasil dilokalisir dan dibersihkan dari lapisan minyak, mencegah kerusakan parah pada 500 bibit mangrove yang baru ditanam.</p>',
  '<ul><li>3 drum limbah minyak dan sedimen terkontaminasi berhasil diamankan.</li><li>Area vegetasi mangrove dan tambak warga di sekitar muara terselamatkan dari kontaminasi solar.</li></ul>',
  '<p>Kualitas air muara kembali normal dan aroma bahan bakar yang mengganggu pemukiman nelayan sekitar muara berhasil dihilangkan.</p>',
  '<p>Keterbatasan peralatan oil absorbent boom pabrikan di lokasi pada saat kejadian darurat.</p>',
  '<p>Menggunakan inovasi lokal berbahan sekam padi dan sabut kelapa kering dalam karung goni yang terbukti efektif menyerap minyak di permukaan air.</p>',
  '<p>Diperlukan sosialisasi kepada kapal-kapal motor nelayan agar tidak membuang sisa oli/solar sembarangan di perairan muara sungai.</p>',
  NULL, NULL, NULL, NULL, NULL,
  'draft', NULL,
  NOW(), NOW()
);

SET FOREIGN_KEY_CHECKS = 1;
