# 🍏 Setia Buah
**Sistem Manajemen Rantai Pasok (Supply Chain Management) & E-Commerce Buah Modern**

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)

---
Aplikasi **Setia Buah** dirancang dan dikembangkan dengan penuh dedikasi oleh:
    **Deyafa Arsetya**
    **Faishal Arrasyid**

---

##  Tentang Aplikasi
**Setia Buah** adalah sebuah platform terintegrasi yang mendigitalisasi seluruh alur suplai dan penjualan buah—mulai dari tingkat petani di kebun hingga ke tangan konsumen. 

Aplikasi ini menjembatani tiga pihak utama:
1. **Petani Mitra**: Yang mengajukan estimasi hasil panen dan memvalidasi ketersediaan pasokan.
2. **Administrator & Manajemen Gudang**: Yang menangani verifikasi penjemputan/pengadaan (procurement), kendali mutu (*Quality Control*/QC), dan pengelolaan inventaris toko.
3. **Pembeli (Konsumen)**: Yang dapat bertransaksi melalui toko online (e-commerce) yang cerdas dengan sistem pembayaran terintegrasi serta pelacakan pesanan real-time.

---

##  Fitur Keseluruhan

###  1. Portal Petani (Farmer Portal)
* **Dashboard Petani**: Ringkasan data pasokan dan aktivitas penjualan hasil tani.
* **Pengajuan & Estimasi Panen**: Petani dapat mendaftarkan estimasi hasil panen beserta kuantitas dan tanggal kesiapan panen untuk diproses oleh gudang.
* **Pembaruan Lokasi Geografis**: Integrasi peta titik lokasi lahan/kebun untuk memudahkan logistik penjemputan dan pengecekan pasokan.

###  2. Portal Admin & Supply Chain Management
* **Manajemen Pengadaan (Procurement Flow)**: 
  * Verifikasi & persetujuan (Approve/Reject) estimasi panen dari petani mitra.
  * Proses penjadwalan dan pencatatan penjemputan buah hingga masuk ke gudang penerimaan.
* **Antrian & Pemeriksaan Kendali Mutu (Quality Control / QC)**: 
  * Antrian khusus untuk inspeksi kualitas atas pasokan yang tiba.
  * Pencatatan hasil seleksi standar mutu sebelum komoditas masuk ke gudang stok.
* **Manajemen Inventaris & Katalog Etalase**: 
  * Kontrol stok pasokan, pengaturan harga jual e-commerce, dan fitur aktivasi/nonaktifasi tayang (toggle display).
* **Manajemen Pesanan & Operasional Sales**:
  * Monitoring masuknya pesanan konsumen, validasi status pembayaran, dan pengubahsuaian status pengiriman barang (Shipping/Shipped).
  * Cetak Faktur / Kwitansi Pembelian (Receipt) dengan format profesional.
* **Manajemen Mitra Petani**: Pengelolaan akun, data registrasi, dan informasi pekebun.

###  3. Portal Pembeli (E-Commerce & Konsumen)
* **Katalog Produk Buah Segar**: Tampilan etalase modern berkonsep responsive design dengan detail informasi spesifikasi produk, stok real-time, dan kualitas terjamin.
* **Keranjang Belanja (Shopping Cart) & Checkout**: Pengaturan item belanja dengan kalkulasi ongkir dinamis (Shipping Rates) ke lokasi pembeli.
* **Integrasi Pembayaran Digital (Midtrans Payment Gateway)**: 
  * Mendukung berbagai metode pembayaran instan (Transfer Bank / Virtual Account, QRIS, Dompet Digital / E-Wallet).
  * Pembaruan status pembayaran otomatis melalui Webhook Callbacks.
* **Pelacakan Pesanan & Status Pengiriman (Live Track Order)**: Pantau pergerakan pengiriman pesanan dengan tampilan kronologis hingga pembeli dapat mengonfirmasi pesanan telah diterimanya.
* **Manajemen Akun & Profil**: Pusat informasi riwayat transaksi serta penyesuaian biodata & alamat pembeli.

---

##  Teknologi Yang Digunakan

| Kategori | Teknologi / Library | Keterangan |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel 13.x** | Framework PHP modern berarsitektur kuat, stabil, dan aman. |
| **Bahasa Pemrograman**| **PHP 8.3** | Performa tingkat tinggi dengan dukungan sintaks standar teranyar. |
| **Frontend Styling** | **Tailwind CSS v4.0** | Utility-first CSS framework terbaru untuk antarmuka UI/UX yang elegan dan super responsif. |
| **Asset Bundler** | **Vite 8.0** | Tool pemrosesan aset bergrafik modern untuk kompilasi ekstra cepat. |
| **Database & ORM** | **SQLite / MySQL & Eloquent** | Relational database modeling terstruktur untuk kebutuhan supply chain & e-commerce. |
| **Payment Gateway** | **Midtrans API** | Integrasi pembayaran pihak ketiga yang aman untuk ekosistem financial digital Indonesia. |
| **Maps / GIS Integration**| **Interactive Leaflet / Maps** | Pemetaan geografis bagi koordinat kebun petani & pelacakan kurir logistik. |

---

<p align="center">
<b> Setia Buah.</b><br>
<i> Dibuat dan dikreasikan oleh Deyafa Arsetya & Faishal Arrasyid</i>
</p>
