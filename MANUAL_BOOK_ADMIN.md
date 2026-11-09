# 📖 Buku Panduan Penggunaan Rekam WeBe (Role: Admin)
**Sistem Perencanaan dan Pelaporan Realisasi Kegiatan Yayasan WeBe**

---

## 📌 1. Tentang Panduan Ini
Selamat datang! Buku panduan ini disusun khusus untuk memandu **Admin / Pengurus / Supervisor** dalam mengoperasikan aplikasi **Rekam WeBe** versi terbaru. Bahasa yang digunakan dibuat ringkas, lugas, dan praktis tanpa istilah teknis yang rumit agar mudah dipahami dan langsung dapat diterapkan.

Tugas dan kewenangan utama Admin di aplikasi ini meliputi:
1. **Mengelola Akun Pengguna**: Mendaftarkan anggota baru, mengubah data, dan mereset kata sandi jika anggota lupa.
2. **Review & Approval Rencana Kegiatan**: Memeriksa usulan program dari anggota, memberikan persetujuan, meminta revisi, atau menolak usulan. Admin juga dapat mengembalikan rencana yang telah disetujui ke status revisi jika terjadi perubahan jadwal/anggaran di lapangan.
3. **Validasi & Finalisasi Laporan Kegiatan**: Memeriksa realisasi pelaksanaan lapangan beserta bukti dokumen pendukung (Laporan dari Rencana maupun Laporan Langsung/Insidental), lalu mengesahkannya menjadi status **Final**.
4. **Monitoring & Arsip Data**: Memantau kalender kegiatan terpadu, grafik kegiatan bulanan, peta lokasi sebaran kegiatan, serta mengunduh rekapitulasi data lengkap ke dalam format **Excel / CSV**.

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
  - **Laporan Aktif**: Laporan hasil kegiatan yang menunggu verifikasi atau sedang direvisi.
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

## 📝 6. Memeriksa & Mengesahkan Laporan Kegiatan (Menu: Laporan Kegiatan)

Setelah kegiatan terlaksana di lapangan, anggota akan mengunggah laporan hasil kegiatan beserta seluruh bukti administratif digital.

### 6.1 Memahami 2 Jenis Laporan yang Masuk
Admin akan menerima dua jenis laporan kegiatan pada menu **Laporan Kegiatan**:
1. **Laporan dari Rencana yang Disetujui (Reguler)**: Laporan yang terhubung langsung dengan usulan rencana sebelumnya. Memuat target rencana vs realisasi lapangan.
2. **Laporan Langsung (Insidental / Tanggap Darurat)**: Laporan kegiatan mendadak yang tidak sempat direncanakan sebelumnya. Pada jenis ini, rincian pelaksanaan dan hasil dirangkum langsung secara mandiri.

---

### 6.2 Memeriksa Bukti Dokumen Lapangan
Buka detail laporan yang berstatus **Diajukan**. Periksa kelengkapan poin-poin berikut:
- **Pelaksanaan Riil**: Tanggal mulai/selesai sebenarnya dan jumlah peserta yang hadir.
- **Hasil & Evaluasi**: Hasil nyata yang dicapai, output fisik, dampak awal, serta kendala lapangan beserta solusinya.
- **Bukti Digital Otentik**:
  - 📷 **Foto Kegiatan**: Dokumentasi foto suasana kegiatan (sistem telah mengompres ukuran gambar secara cerdas dan mendukung foto kamera iPhone / HEIC).
  - 📋 **Daftar Hadir**: Lembar presensi bertanda tangan peserta (wajib pada laporan reguler).
  - 📝 **Notulen**: Catatan rapat atau notulensi proses jalannya kegiatan.
  - 📚 **Materi**: Berkas modul atau paparan presentasi yang disampaikan.
  - 📜 **Berita Acara**: Lembar berita acara serah terima atau penyelesaian pekerjaan.

---

### 6.3 Mengesahkan Laporan (Finalisasi)
- **Jika Laporan Sudah Lengkap dan Benar (Terima Laporan)**:
  - Klik tombol hijau **Terima Laporan**.
  - Status laporan resmi menjadi **Final**.
  - **Otomatisasi Sistem**: Sistem secara otomatis akan memperbarui status Rencana Kegiatan terkait menjadi **Selesai**. Data otomatis diarsipkan permanen ke menu *History Realisasi*.
- **Jika Bukti Belum Lengkap (Minta Revisi Laporan)**:
  - Klik tombol kuning **Minta Revisi Laporan**.
  - Tuliskan catatan perbaikan (contoh: *"Lembar presensi halaman kedua blur, mohon unggah foto presensi yang lebih jelas"*).
  - Status laporan berubah menjadi **Revisi** agar diperbaiki oleh anggota pembuat laporan.

### 6.4 Fitur Pembaruan: Meminta Revisi pada Laporan yang Telah Diterima (Final)
Jika suatu laporan telah disetujui namun ditemukan kekeliruan administratif belakangan (misalnya nota tambahan atau revisi berita acara):
- Admin dapat membuka laporan terkait dan menekan tombol **Minta Revisi Laporan**.
- Laporan akan kembali dibuka akses editnya untuk anggota, dan status rencana terkait akan dikembalikan dari Selesai untuk sementara hingga laporan difinalisasi ulang.

> 📸 **[TEMPATKAN SCREENSHOT 8 DI SINI]**
> - **Halaman yang dibuka:** Halaman detail laporan kegiatan (`/laporan_kegiatan/{id}`).
> - **Bagian yang di-screenshot:** Area lampiran bukti (foto, daftar hadir, notulen) dan tombol "Terima Laporan" serta "Minta Revisi".
> - **Keterangan gambar:** *Pemeriksaan Bukti Laporan Kegiatan dan Tombol Finalisasi Admin*

---

## 📜 7. Riwayat Realisasi & Ekspor Laporan (Menu: History Realisasi)

Menu **History Realisasi** merupakan pusat bank data arsip seluruh kegiatan yayasan yang telah selesai dan memiliki laporan resmi.

### 7.1 Filter Data Canggih
Anda dapat memfilter arsip kegiatan berdasarkan kebutuhan pelaporan:
- **Bulan** dan **Tahun** pelaksanaan.
- **Status Laporan** (menampilkan laporan berstatus Final, Diajukan, atau Revisi).
- **Kolom Penanggung Jawab (PJ)**: Menampilkan nama koordinator lapangan untuk setiap kegiatan secara jelas.
- **Kotak Pencarian Cepat (DataTable Search)**: Cukup ketikkan kata kunci nama desa, jenis kegiatan, atau kata tertentu untuk menemukan data dalam hitungan detik.

### 7.2 Mengunduh Rekapitulasi ke Excel / CSV
1. Atur saringan data sesuai kebutuhan Anda (misal: Tahun 2026), atau biarkan tanpa filter jika ingin mengunduh seluruh data riwayat yayasan.
2. Klik tombol hijau bertuliskan **Export CSV / Excel** di pojok kanan atas tabel.
3. Berkas spreadsheet (.xlsx/.csv) akan terunduh otomatis ke komputer Anda, rapi dan siap diolah untuk rapat evaluasi bulanan maupun laporan donatur.

> 📸 **[TEMPATKAN SCREENSHOT 9 DI SINI]**
> - **Halaman yang dibuka:** Menu History Realisasi.
> - **Bagian yang di-screenshot:** Bilah filter (Bulan, Tahun, Status Laporan), tabel riwayat kegiatan dengan kolom Penanggung Jawab, dan tombol "Export CSV / Excel".
> - **Keterangan gambar:** *Halaman History Realisasi dan Fitur Export Data*

---

## 🔔 8. Notifikasi Lonceng & Alur Komunikasi Sistem

- Pada bilah navigasi kanan atas, terdapat ikon **Lonceng Notifikasi**.
- Angka merah pada lonceng menunjukkan adanya:
  - Usulan Rencana Kegiatan baru dari anggota.
  - Usulan perbaikan Rencana yang telah diperbarui oleh anggota.
  - Pengajuan Laporan Kegiatan baru atau perbaikan laporan yang telah diajukan ulang.
- Klik pada item notifikasi untuk langsung menuju ke halaman dokumen yang memerlukan perhatian Anda tanpa perlu mencarinya secara manual.

> 📸 **[TEMPATKAN SCREENSHOT 10 DI SINI]**
> - **Halaman yang dibuka:** Navbar pojok kanan atas (dropdown lonceng).
> - **Bagian yang di-screenshot:** Panel dropdown notifikasi yang menampilkan daftar pemberitahuan pengajuan kegiatan baru.
> - **Keterangan gambar:** *Menu Lonceng Notifikasi Sistem Rekam WeBe*

---

## 💡 9. Tips & Praktik Terbaik bagi Admin
1. **Sertakan Catatan Spesifik Saat Revisi**: Saat menekan tombol *Minta Revisi*, tuliskan instruksi yang gamblang agar anggota di lapangan langsung mengerti apa yang harus diperbaiki tanpa perlu bolak-balik bertanya di grup WhatsApp.
2. **Periksa Kejelasan Presensi & Foto**: Sebelum mengesahkan laporan menjadi Final, pastikan lembar presensi bertanda tangan dan foto kegiatan terlihat jelas karena berkas ini yang akan terlampir resmi saat laporan dicetak.
3. **Manfaatkan Kalender Dashboard**: Sebelum menyetujui tanggal rencana kegiatan baru, tengok sekilas kalender di dashboard untuk memastikan tim lapangan memiliki jeda waktu yang cukup antar kegiatan.
4. **Cetak PDF Mandiri**: Admin dapat mencetak versi fisik atau menyimpan PDF dari setiap rencana kegiatan (tombol *Unduh PDF*) maupun laporan kegiatan (tombol *Cetak Laporan*) dengan kop surat resmi Yayasan WeBe.

---
*Buku Panduan Penggunaan Rekam WeBe (Admin) - Yayasan WeBe*
