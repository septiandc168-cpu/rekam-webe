# 📖 Buku Panduan Penggunaan Rekam WeBe (Role: Admin)
**Sistem Perencanaan dan Pelaporan Realisasi Kegiatan Yayasan WeBe**

---

## 📌 1. Tentang Panduan Ini
Selamat datang! Buku panduan ini disusun khusus untuk memandu **Admin / Pengurus / Supervisor** dalam mengoperasikan aplikasi **Rekam WeBe** versi terbaru. Bahasa yang digunakan dibuat ringkas, lugas, dan praktis tanpa istilah teknis yang rumit agar mudah dipahami dan langsung dapat diterapkan.

Tugas dan kewenangan utama Admin di aplikasi ini meliputi:
1. **Mengelola Akun Pengguna**: Mendaftarkan anggota baru, mengubah data, dan mereset kata sandi jika anggota lupa.
2. **Review & Approval Rencana Kegiatan**: Memeriksa usulan program dari anggota, memberikan persetujuan, meminta revisi, atau menolak usulan. Admin juga dapat mengembalikan rencana yang telah disetujui ke status revisi jika terjadi perubahan jadwal/anggaran di lapangan.
3. **Validasi Laporan Kegiatan Reguler**: Memeriksa realisasi pelaksanaan lapangan beserta bukti dokumen pendukung yang terhubung dengan usulan rencana, lalu mengesahkannya menjadi status **Final**.
4. **Validasi Laporan Langsung (Insidental / Tanggap Darurat)**: Memeriksa laporan mandiri atas kegiatan mendadak lapangan tanpa rencana awal, memastikan bukti foto otentik, lalu mengesahkannya ke arsip resmi.
5. **Monitoring & Arsip Data**: Memantau kalender kegiatan terpadu, grafik kegiatan bulanan, peta lokasi sebaran kegiatan, serta mengunduh rekapitulasi data lengkap ke dalam format **Excel / CSV**.

---

## 🔑 2. Masuk ke Aplikasi (Login) & Keamanan Akun

### 2.1 Cara Masuk (Login)
1. Buka peramban (*browser*) di laptop atau komputer Anda (disarankan Google Chrome atau Microsoft Edge).
2. Akses alamat web **Rekam WeBe**.
3. Masukkan alamat **Email** dan **Password** Admin Anda.
4. Klik tombol **Login**.

> 📸 **[TEMPATKAN SCREENSHOT 1 DI SINI]**
> - **Halaman yang dibuka:** Halaman utama Login.
> - **Bagian yang di-screenshot:** Form login yang menampilkan logo Rekam WeBe, kolom Email, Password, dan tombol "Login".
> - **Keterangan gambar:** *Tampilan Halaman Login Rekam WeBe*

---

### 2.2 Cara Mengganti Kata Sandi (Password)
Demi keamanan berkala, Anda dapat memperbarui kata sandi akun Admin:
1. Klik **nama profil Anda** di pojok kanan atas bilah navigasi (*navbar*).
2. Pilih menu **Ganti Password**.
3. Pada jendela kecil (*modal pop-up*) yang muncul:
   - **Password Saat Ini**: Ketik kata sandi yang sedang Anda gunakan saat ini.
   - **Password Baru**: Buat kata sandi baru (minimal 8 karakter).
   - **Konfirmasi Password Baru**: Ketik ulang kata sandi baru tersebut.
4. Klik tombol **Simpan**.

> 📸 **[TEMPATKAN SCREENSHOT 2 DI SINI]**
> - **Halaman yang dibuka:** Menu profil pojok kanan atas ➔ Klik "Ganti Password".
> - **Bagian yang di-screenshot:** Jendela kecil (*modal pop-up*) formulir Ganti Password.
> - **Keterangan gambar:** *Jendela Penggantian Kata Sandi Admin*

---

## 👥 3. Mengelola Akun Anggota (Menu: Pengguna)

Menu **Pengguna** hanya dapat diakses oleh Admin. Menu ini berfungsi untuk mengatur hak akses seluruh personel yayasan.

### 3.1 Menambah Anggota Baru
1. Klik menu **Pengguna** pada bilah menu sebelah kiri (*sidebar*).
2. Klik tombol **+ Tambah User** di bagian atas tabel.
3. Isi data anggota:
   - **Nama Lengkap**
   - **Email** (digunakan oleh anggota untuk masuk ke sistem)
   - **No. HP / WhatsApp**
   - **Jabatan** dan **Unit Kerja**
   - **NIK / NIP** (identitas kepegawaian)
   - **Role**: Pilih `anggota`
4. Klik tombol **Simpan**.
   > *Catatan Penting: Kata sandi awal (*default*) untuk anggota yang baru didaftarkan otomatis disetel ke `12345678`. Informasikan kata sandi ini kepada anggota agar mereka dapat login pertama kali dan langsung menggantinya secara mandiri.*

> 📸 **[TEMPATKAN SCREENSHOT 3 DI SINI]**
> - **Halaman yang dibuka:** Menu Pengguna ➔ Klik `+ Tambah User`.
> - **Bagian yang di-screenshot:** Formulir isian data anggota baru lengkap dengan pemilihan role.
> - **Keterangan gambar:** *Formulir Tambah Pengguna Baru*

---

### 3.2 Mengatur Ulang Kata Sandi Anggota (Reset Password)
Jika ada anggota yang lupa kata sandinya:
1. Masuk ke menu **Pengguna**.
2. Cari nama anggota tersebut pada tabel (gunakan kolom pencarian jika diperlukan).
3. Pada kolom **Aksi** di ujung kanan baris, klik **tombol kunci berwarna kuning** (*Reset Password*).
4. Klik **Ya, Reset** pada kotak dialog konfirmasi.
5. Kata sandi anggota tersebut seketika kembali menjadi `12345678`.

> 📸 **[TEMPATKAN SCREENSHOT 4 DI SINI]**
> - **Halaman yang dibuka:** Menu Pengguna.
> - **Bagian yang di-screenshot:** Tabel pengguna dengan sorotan pada tombol aksi kunci kuning (Reset Password).
> - **Keterangan gambar:** *Tombol Reset Password Pengguna pada Tabel Pengguna*

---

## 📊 4. Memantau Halaman Dashboard

Halaman **Dashboard** menyajikan ringkasan operasional yayasan secara terpadu dan *real-time*:
- **Kartu Statistik (Bagian Atas)**:
  - **Rencana Diajukan**: Usulan baru dari anggota yang menunggu pemeriksaan Admin.
  - **Rencana Disetujui**: Kegiatan yang telah disahkan dan siap dieksekusi.
  - **Laporan Aktif**: Laporan hasil kegiatan (reguler maupun langsung) yang menunggu verifikasi atau sedang direvisi.
  - **Total Anggota Aktif**: Jumlah pengguna dengan hak akses anggota.
- **Grafik Kegiatan Bulanan**: Menampilkan perbandingan tren kegiatan yang disetujui dan kegiatan yang telah selesai terlaksana sepanjang tahun.
- **Kalender Agenda Yayasan**: Kalender interaktif yang memuat seluruh jadwal kegiatan yang telah disetujui. Memudahkan pengurus memantau agar tidak terjadi jadwal acara yang bertabrakan.
- **Peta Sebaran Kegiatan**: Menampilkan titik koordinat geografis tempat kegiatan-kegiatan yayasan dilaksanakan.

> 📸 **[TEMPATKAN SCREENSHOT 5 DI SINI]**
> - **Halaman yang dibuka:** Menu Dashboard.
> - **Bagian yang di-screenshot:** Layar Dashboard lengkap (kartu statistik, grafik kegiatan, kalender kegiatan, dan peta lokasi).
> - **Keterangan gambar:** *Tampilan Utama Dashboard Pengurus / Admin*

---

## 📋 5. Memeriksa & Mengelola Rencana Kegiatan (Menu: Rencana Kegiatan)

Setiap rencana kegiatan yang diusulkan oleh anggota memerlukan peninjauan dan persetujuan dari Admin sebelum dapat dilaksanakan di lapangan.

### 5.1 Memeriksa Usulan Masuk
1. Klik menu **Rencana Kegiatan** di sebelah kiri.
2. Pada tabel, usulan baru yang membutuhkan keputusan Anda bertanda status biru (**Diajukan**).
3. Anda dapat melihat informasi ringkas: **Nama Kegiatan**, **Lokasi (Desa/Wilayah)**, **Tanggal Pelaksanaan**, dan **Penanggung Jawab (PJ)**.
4. Klik tombol bergambar mata (**Lihat Detail**) untuk memeriksa usulan secara menyeluruh:
   - Deskripsi dan tujuan program.
   - Waktu pelaksanaan dan titik pin lokasi di peta interaktif.
   - Estimasi jumlah peserta dan kelompok dampingan pelaksana.
   - Catatan rincian kebutuhan serta berkas lampiran anggaran (RAB / proposal).
   - Foto survei / banner publikasi dan dokumen pendukung lainnya.

> 📸 **[TEMPATKAN SCREENSHOT 6 DI SINI]**
> - **Halaman yang dibuka:** Menu Rencana Kegiatan.
> - **Bagian yang di-screenshot:** Tabel rencana kegiatan yang menampilkan kolom nama kegiatan, lokasi, tanggal, PJ, dan status "Diajukan".
> - **Keterangan gambar:** *Daftar Rencana Kegiatan yang Masuk dari Anggota*

---

### 5.2 Mengambil Tindakan Keputusan Rencana
Pada halaman detail rencana kegiatan, Admin memiliki 3 pilihan tombol keputusan utama:

1. **Tombol Hijau (Setujui Rencana)**:
   - Pilih jika seluruh usulan, jadwal, dan anggaran sudah memenuhi standar yayasan.
   - Masukkan catatan persetujuan singkat (opsional/konfirmasi), lalu klik Setujui.
   - Status berubah menjadi **Disetujui**. Kegiatan otomatis terjadwal di Kalender Dashboard dan membuka akses bagi anggota untuk membuat laporan kegiatan setelah acara terlaksana.
2. **Tombol Kuning (Minta Revisi)**:
   - Pilih jika terdapat hal yang perlu dilengkapi atau diperbaiki oleh anggota (contoh: *"Rincian biaya transportasi belum jelas dan mohon lampirkan surat izin desa"*).
   - Tuliskan catatan evaluasi pada kotak yang disediakan, lalu kirim. Status berubah menjadi **Revisi**. Anggota akan menerima notifikasi dan dapat mengedit kembali usulannya.
3. **Tombol Merah (Tolak Rencana)**:
   - Pilih jika usulan tidak sesuai dengan prioritas program kerja yayasan.
   - Wajib menyertakan alasan penolakan tertulis. Status berubah menjadi **Ditolak**.

### 5.3 Fitur Pembaruan: Meminta Revisi pada Rencana yang Sudah Disetujui
Jika suatu rencana kegiatan **sudah berstatus Disetujui**, namun di kemudian hari terdapat perubahan mendadak dari lapangan (misalnya perubahan tanggal pelaksanaan, pergantian lokasi desa, atau penyesuaian anggaran penting):
- Admin dapat membuka kembali detail rencana kegiatan tersebut.
- Tombol **Minta Revisi** tetap tersedia bagi Admin untuk mengembalikan status kegiatan menjadi **Revisi** disertai catatan alasan perubahan.
- Hal ini memungkinkan anggota untuk memperbarui data resmi tanpa harus membuat pengajuan baru dari awal.

> 📸 **[TEMPATKAN SCREENSHOT 7 DI SINI]**
> - **Halaman yang dibuka:** Halaman detail rencana kegiatan (`/rencana_kegiatan/{id}`).
> - **Bagian yang di-screenshot:** Panel tombol keputusan Admin ("Setujui", "Minta Revisi", dan "Tolak").
> - **Keterangan gambar:** *Panel Tindakan Keputusan Admin pada Rencana Kegiatan*

---

## 📝 6. Memeriksa & Mengesahkan Laporan Kegiatan Reguler (Menu: Laporan Kegiatan)

Laporan Kegiatan Reguler adalah laporan hasil kegiatan yang terhubung langsung dengan usulan rencana kegiatan yang telah disetujui sebelumnya.

### 6.1 Memeriksa Bukti Dokumen Lapangan Reguler
Buka detail laporan reguler yang berstatus **Diajukan**. Periksa kelengkapan poin-poin berikut:
- **Pelaksanaan Riil**: Tanggal mulai/selesai sebenarnya dan jumlah peserta yang hadir nyata.
- **Hasil & Evaluasi**: Hasil nyata yang dicapai, output fisik, dampak awal, serta kendala lapangan beserta solusinya.
- **Bukti Digital Otentik**:
  - 📷 **Foto Kegiatan (Wajib)**: Dokumentasi foto suasana kegiatan (sistem telah mengompres ukuran gambar secara cerdas dan mendukung foto kamera iPhone / HEIC).
  - 📋 **Daftar Hadir (Wajib)**: Lembar presensi bertanda tangan peserta asli.
  - 📝 **Notulen (Opsional)**: Catatan rapat atau notulensi proses jalannya kegiatan.
  - 📚 **Materi (Wajib)**: Berkas modul atau paparan presentasi yang disampaikan.
  - 📜 **Berita Acara (Wajib)**: Lembar berita acara serah terima atau penyelesaian pekerjaan.

---

### 6.2 Mengesahkan Laporan Reguler (Finalisasi)
- **Jika Laporan Sudah Lengkap dan Benar (Terima Laporan)**:
  - Klik tombol hijau **Terima Laporan**.
  - Status laporan resmi menjadi **Final**.
  - **Otomatisasi Sistem**: Sistem secara otomatis akan memperbarui status Rencana Kegiatan terkait menjadi **Selesai**. Data otomatis diarsipkan permanen ke menu *History Realisasi*.
- **Jika Bukti Belum Lengkap (Minta Revisi Laporan)**:
  - Klik tombol kuning **Minta Revisi Laporan**.
  - Tuliskan catatan perbaikan (contoh: *"Lembar presensi halaman kedua buram, mohon unggah foto presensi yang lebih jelas"*).
  - Status laporan berubah menjadi **Revisi** agar diperbaiki oleh anggota pembuat laporan.

### 6.3 Meminta Revisi pada Laporan yang Telah Diterima (Final)
Jika suatu laporan telah disetujui namun ditemukan kekeliruan administratif belakangan:
- Admin dapat membuka laporan terkait dan menekan tombol **Minta Revisi Laporan**.
- Laporan akan kembali dibuka akses editnya untuk anggota, dan status rencana terkait akan dikembalikan dari Selesai untuk sementara hingga laporan difinalisasi ulang.

> 📸 **[TEMPATKAN SCREENSHOT 8 DI SINI]**
> - **Halaman yang dibuka:** Halaman detail laporan kegiatan reguler (`/laporan_kegiatan/{id}`).
> - **Bagian yang di-screenshot:** Area lampiran bukti (foto, daftar hadir, notulen) dan tombol "Terima Laporan" serta "Minta Revisi".
> - **Keterangan gambar:** *Pemeriksaan Bukti Laporan Kegiatan Reguler dan Tombol Finalisasi Admin*

---

## ⚡ 7. KHUSUS: Memeriksa & Mengesahkan Laporan Langsung (Insidental / Darurat)

Aplikasi **Rekam WeBe** memiliki fitur **Laporan Langsung** untuk menampung kegiatan lapangan yang bersifat mendadak, tanggap darurat, atau insidental tanpa melalui pengajuan rencana kegiatan di awal.

### 7.1 Mengenali Laporan Langsung yang Masuk
Pada menu **Laporan Kegiatan** maupun halaman detailnya, Admin dapat langsung mengenali Laporan Langsung melalui tanda-tanda berikut:
- **Lencana Khusus**: Terdapat keterangan/badge bertuliskan *"Laporan Langsung / Tanggap Darurat"*.
- **Tidak Terikat Rencana**: Dokumen ini tidak memiliki tautan konteks rencana kegiatan sebelumnya karena kegiatan langsung dieksekusi di lapangan.

---

### 7.2 Poin-Poin Utama yang Diperiksa oleh Admin
Saat memeriksa Laporan Langsung yang diajukan oleh anggota, Admin perlu memfokuskan evaluasi pada:
1. **Nama Kegiatan & Lokasi**: Memastikan nama kegiatan dan lokasi spesifik (desa/pantai/kawasan) telah diuraikan dengan jelas.
2. **Deskripsi Kegiatan**: Membaca latar belakang situasi darurat atau konteks insidental mengapa kegiatan tersebut harus langsung dijalankan di lapangan.
3. **Rangkaian Kegiatan & Capaian**: Meninjau kronologi aksi yang dilakukan serta hasil nyata yang dicapai dari penanganan tersebut.
4. **Foto Bukti Lapangan (Krusial)**: Karena Laporan Langsung bersifat insidental, **Foto Dokumentasi Lapangan adalah bukti utama**. Pastikan foto yang diunggah valid dan menggambarkan aksi lapangan secara otentik.
5. **Fleksibilitas Lampiran Dokumen**: Pada Laporan Langsung, lampiran daftar hadir, materi, dan berita acara bersifat **opsional**. Jika anggota tidak melampirkan lembar daftar hadir (misal pada aksi penyelamatan penyu atau pembersihan sampah darurat), hal tersebut sah menurut sistem dan kolom kosong otomatis disembunyikan.

---

### 7.3 Mengambil Tindakan pada Laporan Langsung
1. **Terima Laporan (Final)**:
   - Klik tombol hijau **Terima Laporan**.
   - Status Laporan Langsung seketika menjadi **Final**.
   - Laporan langsung otomatis masuk ke bank data arsip resmi di menu **History Realisasi**.
2. **Minta Revisi Laporan**:
   - Klik tombol kuning **Minta Revisi Laporan** jika uraian hasil penanganan atau foto bukti masih kurang jelas.
   - Masukkan catatan evaluasi, lalu kirim ke anggota untuk diperbaiki.

### 7.4 Tampilan Cetak Resmi Laporan Langsung
Laporan Langsung yang telah disetujui memiliki format cetak resmi (*print view*) yang secara otomatis menampilkan kop Yayasan WeBe bertajuk *"Laporan Kegiatan Langsung / Tanggap Darurat"*. Bagian peserta yang kosong tidak akan menampilkan baris kosong yang merusak tata letak dokumen.

> 📸 **[TEMPATKAN SCREENSHOT 9 DI SINI]**
> - **Halaman yang dibuka:** Halaman detail Laporan Langsung (`/laporan_kegiatan/{id}`).
> - **Bagian yang di-screenshot:** Header dokumen yang memuat label Laporan Langsung/Darurat, deskripsi kegiatan insidental, foto dokumentasi, dan panel tombol aksi "Terima Laporan".
> - **Keterangan gambar:** *Pemeriksaan Detail Laporan Langsung oleh Admin*

---

## 📜 8. Riwayat Realisasi & Ekspor Laporan (Menu: History Realisasi)

Menu **History Realisasi** merupakan pusat bank data arsip seluruh kegiatan yayasan yang telah selesai dan memiliki laporan resmi (baik Laporan Reguler maupun Laporan Langsung).

### 8.1 Filter Data Canggih
Anda dapat memfilter arsip kegiatan berdasarkan kebutuhan pelaporan:
- **Bulan** dan **Tahun** pelaksanaan.
- **Status Laporan** (menampilkan laporan berstatus Final, Diajukan, atau Revisi).
- **Kolom Penanggung Jawab (PJ)**: Menampilkan nama koordinator lapangan untuk setiap kegiatan secara jelas.
- **Kotak Pencarian Cepat (DataTable Search)**: Cukup ketikkan kata kunci nama desa, jenis kegiatan, atau kata tertentu untuk menemukan data dalam hitungan detik.

### 8.2 Mengunduh Rekapitulasi ke Excel / CSV
1. Atur saringan data sesuai kebutuhan Anda (misal: Tahun 2026), atau biarkan tanpa filter jika ingin mengunduh seluruh data riwayat yayasan.
2. Klik tombol hijau bertuliskan **Export CSV / Excel** di pojok kanan atas tabel.
3. Berkas spreadsheet (.xlsx/.csv) akan terunduh otomatis ke komputer Anda, rapi dan siap diolah untuk rapat evaluasi bulanan maupun laporan donatur.

> 📸 **[TEMPATKAN SCREENSHOT 10 DI SINI]**
> - **Halaman yang dibuka:** Menu History Realisasi.
> - **Bagian yang di-screenshot:** Bilah filter (Bulan, Tahun, Status Laporan), tabel riwayat kegiatan dengan kolom Penanggung Jawab, dan tombol "Export CSV / Excel".
> - **Keterangan gambar:** *Halaman History Realisasi dan Fitur Export Data*

---

## 🔔 9. Notifikasi Lonceng & Alur Komunikasi Sistem

- Pada bilah navigasi kanan atas, terdapat ikon **Lonceng Notifikasi**.
- Angka merah pada lonceng menunjukkan adanya:
  - Usulan Rencana Kegiatan baru dari anggota.
  - Usulan perbaikan Rencana yang telah diperbarui oleh anggota.
  - Pengajuan Laporan Kegiatan baru (Reguler maupun Laporan Langsung) atau perbaikan laporan yang diajukan ulang.
- Klik pada item notifikasi untuk langsung menuju ke halaman dokumen yang memerlukan perhatian Anda tanpa perlu mencarinya secara manual.

> 📸 **[TEMPATKAN SCREENSHOT 11 DI SINI]**
> - **Halaman yang dibuka:** Navbar pojok kanan atas (dropdown lonceng).
> - **Bagian yang di-screenshot:** Panel dropdown notifikasi yang menampilkan daftar pemberitahuan pengajuan kegiatan baru.
> - **Keterangan gambar:** *Menu Lonceng Notifikasi Sistem Rekam WeBe*

---

## 💡 10. Tips & Praktik Terbaik bagi Admin
1. **Sertakan Catatan Spesifik Saat Revisi**: Saat menekan tombol *Minta Revisi*, tuliskan instruksi yang gamblang agar anggota di lapangan langsung mengerti apa yang harus diperbaiki tanpa perlu bolak-balik bertanya di grup WhatsApp.
2. **Fleksibilitas Menilai Laporan Langsung**: Pahami bahwa Laporan Langsung biasanya lahir dari situasi darurat lapangan. Fokus utama penilaian ada pada kejelasan deskripsi tindakan dan foto otentik, bukan pada kelengkapan lembar presensi atau modul materi.
3. **Periksa Kejelasan Presensi & Foto**: Sebelum mengesahkan laporan menjadi Final, pastikan lembar presensi bertanda tangan dan foto kegiatan terlihat jelas karena berkas ini yang akan terlampir resmi saat laporan dicetak.
4. **Manfaatkan Kalender Dashboard**: Sebelum menyetujui tanggal rencana kegiatan baru, tengok sekilas kalender di dashboard untuk memastikan tim lapangan memiliki jeda waktu yang cukup antar kegiatan.
5. **Cetak PDF Mandiri**: Admin dapat mencetak versi fisik atau menyimpan PDF dari setiap rencana kegiatan (tombol *Unduh PDF*) maupun laporan kegiatan (tombol *Cetak Laporan*) dengan kop surat resmi Yayasan WeBe.

---
*Buku Panduan Penggunaan Rekam WeBe (Admin) - Yayasan WeBe*
