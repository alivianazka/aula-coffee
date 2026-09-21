
# Penjelasan Fungsi store (Bahasa Mudah Mahasiswa)

Fungsi ini dipakai untuk memproses input perubahan stok barang dari form.  
Intinya: data dicek dulu, lalu stok disimpan ke riwayat, kemudian stok barang otomatis ditambah atau dikurangi sesuai tipe transaksi.

## 1. Validasi input awal
Sebelum masuk proses utama, fungsi memastikan:
1. barang_id wajib ada dan harus benar-benar ada di tabel barang.
2. tipe hanya boleh masuk atau keluar.
3. qty harus angka bulat dan minimal 1.
4. keterangan boleh kosong, tapi kalau diisi maksimal 500 karakter.

Kalau validasi gagal, Laravel otomatis balik ke form dengan pesan error.

## 2. Siapkan variabel peringatan
soWarningMsg diisi null dulu.  
Variabel ini nanti dipakai kalau setelah update stok ternyata stok sudah rendah (di bawah batas SO).

## 3. Proses utama di dalam transaction
Semua langkah dibungkus DB transaction supaya aman:
1. Ambil data barang berdasarkan barang_id.
2. Kalau tipe keluar dan qty lebih besar dari stok sekarang, langsung lempar exception.
   Artinya: tidak boleh mengeluarkan barang melebihi stok tersedia.
3. Simpan riwayat pergerakan stok ke tabel stok_movement (siapa user-nya, tipe, qty, keterangan, tanggal).
4. Update stok barang:
   - Kalau tipe masuk: qty dan stok_akhir ditambah.
   - Kalau tipe keluar: qty dan stok_akhir dikurangi (ini bagian stok otomatis berkurang).
5. Refresh model barang agar nilai terbaru terbaca.
6. Cek apakah stok rendah dengan isStockLow:
   - Jika iya, hapus notifikasi lama yang belum dibaca untuk barang itu.
   - Buat notifikasi baru ke admin.
   - Isi soWarningMsg supaya nanti tampil warning ke user.

Kenapa transaction penting:
- Kalau salah satu langkah gagal, semua perubahan dibatalkan otomatis.
- Jadi data tetap konsisten (tidak setengah berhasil).

## 4. Penanganan error
Kalau ada exception (contoh: stok keluar melebihi stok), fungsi:
1. Batalkan proses transaction.
2. Redirect ke halaman input stok.
3. Tampilkan pesan error dari exception.

## 5. Redirect sukses
Kalau semua lancar:
1. Redirect ke halaman input stok dengan pesan sukses.
2. Jika soWarningMsg terisi, tambahkan pesan warning stok rendah.

---
