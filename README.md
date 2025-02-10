# applied-newtronic

# Tools yang digunakan:
- PHP v8.4.3 (termasuk di XAMPP)
- jQuery 3.7.1 (sudah ada dalam kode)
- Laravel 11
- NodeJs
- Server.IO (sudah ada dalam kode, konfigurasi di php.ini)
- XAMPP
- Bootstrap 5.3.3 / 5.3.0 (sudah ada dalam kode)
- Database: SQLite3 (termasuk di XAMPP, konfigurasi di php.ini)
- Postman -- Soal 2
- TablePlus
- VS Code
- Google Chrome

# Petunjuk:
1. Install XAMPP, Postman, VS Code, Google Chrome, konfigurasi php.ini seperti pada file terlampir
2. Ekstrak file git / fork
3. pindahkan 3 folder ke dalam xampp/htdocs, tiap folder adalah nomor jawaban dari soal yang dikerjakan
4. Pastikan Apache dan MySQL aktif di XAMPP

## Jawaban No. 1 --> folder newtronic-1-transaksi

1. Masuk ke folder, atau kalau pakai VSCode klik kanan file tersebut
2. Open terminal, ketik 'code .' kemudian klik enter
3. Jalankan php artisan serve di terminal
4. Masuk ke http://localhost/newtronic-1-transaksi/public/
5. Klik login di pojok kanan atas
6. Login dengan email: refi@kasir.com, password: 12345678, atau bisa register akun sendiri di sebelah tulisan login
7. Terdapat 3 menu yang bisa diakses, User, Produk, dan Transaksi
8. Terdapat menu Lihat di tampilan utama, dan Ubah & Hapus di kolomnya, serta tombol tambah di bawah tabel

## Jawaban No. 2 --> folder newtronic-2-crawling

1. Jalankan php artisan serve di terminal
1. Buka Postman
2. Pilih HTTP method GET
3. Masukkan URL endpoint sesuai dengan konfigurasi, yaitu:
- http://localhost:8000/crawl-exchange-rates, atau
- http://localhost/newtronic-2-crawling/public/crawl-exchange-rates
4. Klik "Send" di Postman untuk mengirimkan permintaan
5. Cek di TablePlus dengan cara:
- Klik plus di kiri text field search for connection
- Pilih sqlite
- Isi nama database bebas
- Pilih database di lokasi file: newtronic-2-crawling/database/database.sqlite
- Klik test, jika muncul pesan OK maka klik Connect
- Klik tabel exchange_rates, maka akan muncul data yang masuk pada saat eksekusi postman
- Hasil dari postman yang sudah dilakukan ada di root folder dengan ekstensi: postman_collection
- Cek dengan klik import di postman

## Jawaban No. 3 --> folder newtronic-3-scoreboard-v5

1. Masuk ke folder, atau kalau pakai VSCode klik kanan file tersebut
2. Open terminal, ketik 'code .' kemudian klik enter
3. Jalankan php artisan serve di terminal
4. Jalan node server.js di terminal
5. Masuk ke http://localhost/newtronic-3-transaksi/public/ untuk akses scoreboard realtime
6. Masuk ke http://localhost/newtronic-3-transaksi/public/operator untuk akses perubahan skor realtime
7. Logging dapat dilihat di tabel score_logs, untuk caranya sama seperti pada nomor 2

Jika ada yang belum dipahami, dapat menghubungi saya via email: refi.fadholi.04@gmail.com atau via WhatsApp

<h5>Terima kasih</h5>
