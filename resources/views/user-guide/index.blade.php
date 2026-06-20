@extends('layouts.app', ['pageTitle' => __('app.user_guide')])

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    @php $isId = App::getLocale() === 'id'; @endphp

    @if($isId)
    {{-- INDONESIAN VERSION --}}

    <!-- Pendahuluan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Panduan Pengguna M2Way Travel</h1>
        <p class="text-gray-600">Selamat datang di sistem manajemen M2Way Travel. Panduan ini akan membantu Anda memahami dan menggunakan setiap fitur yang tersedia.</p>
    </div>

    <!-- 1. Navigasi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">1</span>
            Navigasi
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Sidebar di sebelah kiri adalah menu utama untuk mengakses seluruh fitur. Anda dapat mengecilkan sidebar dengan mengklik ikon menu di pojok kiri atas.</p>
            <p>Setiap menu dikelompokkan berdasarkan fungsi:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Perencanaan</strong> — Jadwal, Timeline, Kalender</li>
                <li><strong>Operasional</strong> — Transaksi, Kelompok Jamaah, Pelanggan</li>
                <li><strong>Katalog</strong> — Paket, Kategori Paket</li>
                <li><strong>Keuangan</strong> — Keuangan, Akuntansi, Kategori Akuntansi</li>
                <li><strong>Dokumen</strong> — Dokumen Perjalanan</li>
                <li><strong>Administrasi</strong> — Pengguna, Peran, Log Aktivitas (khusus Admin)</li>
            </ul>
            <p>Setiap grup dapat diperluas atau ditutup dengan mengklik nama grup. Status grup akan disimpan secara otomatis.</p>
        </div>
    </div>

    <!-- 2. Dashboard -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">2</span>
            Dasbor
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Halaman dasbor menampilkan ringkasan data bisnis Anda:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Total pelanggan, transaksi, paket, dan pendapatan</li>
                <li>Pembayaran tertunda yang perlu ditindaklanjuti</li>
                <li>Transaksi terbaru</li>
                <li>Grafik pendapatan dan pengeluaran bulanan</li>
                <li>Distribusi paket (Haji / Umroh)</li>
            </ul>
        </div>
    </div>

    <!-- 3. Pelanggan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">3</span>
            Pelanggan
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Modul Pelanggan digunakan untuk mengelola data jamaah / pelanggan.</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Mencari pelanggan</strong> — Gunakan kolom pencarian untuk mencari berdasarkan nama atau nomor telepon</li>
                <li><strong>Menambah pelanggan</strong> — Klik tombol "Tambah Pelanggan" dan isi formulir</li>
                <li><strong>Mengedit pelanggan</strong> — Klik ikon pensil pada baris pelanggan yang ingin diubah</li>
                <li><strong>Menghapus pelanggan</strong> — Klik ikon hapus dan konfirmasi</li>
                <li><strong>Lampiran</strong> — Anda dapat menambahkan dokumen (KTP, paspor, dll.) ke data pelanggan</li>
            </ul>
        </div>
    </div>

    <!-- 4. Paket -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">4</span>
            Paket & Kategori Paket
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Modul Paket mengelola produk perjalanan yang ditawarkan (Haji, Umroh, dll).</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Kategori Paket</strong> — Kelola kategori seperti "Haji Reguler", "Umroh Plus", dll.</li>
                <li><strong>Paket</strong> — Setiap paket memiliki nama, kategori, harga, kuota, dan deskripsi</li>
                <li><strong>Status Paket</strong> — Paket dapat diaktifkan atau dinonaktifkan</li>
            </ul>
        </div>
    </div>

    <!-- 5. Transaksi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">5</span>
            Transaksi
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Modul Transaksi mencatat setiap pembelian paket oleh pelanggan.</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Membuat transaksi</strong> — Pilih pelanggan dan paket, atur jumlah peserta, dan sistem akan menghitung total otomatis</li>
                <li><strong>Pembayaran</strong> — Catat pembayaran dari pelanggan. Status pembayaran akan berubah menjadi "Lunas" jika total sudah terbayar</li>
                <li><strong>Refund</strong> — Gunakan tombol Refund jika transaksi dibatalkan</li>
                <li><strong>Invoice</strong> — Cetak invoice / faktur untuk transaksi</li>
                <li><strong>Ekspor</strong> — Ekspor data transaksi ke Excel atau PDF</li>
            </ul>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-yellow-800">
                <p class="text-xs font-medium">Catatan: Transaksi dengan status Lunas (payment_status = paid) dan Refund (status = refund) tidak dapat dihapus.</p>
            </div>
        </div>
    </div>

    <!-- 6. Keuangan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">6</span>
            Keuangan & Akuntansi
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p><strong>Keuangan</strong> menampilkan laporan keuangan keseluruhan yang dapat diekspor ke PDF atau Excel.</p>
            <p><strong>Akuntansi</strong> digunakan untuk mencatat pemasukan dan pengeluaran operasional:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Pilih tipe <strong>Pemasukan</strong> atau <strong>Pengeluaran</strong></li>
                <li>Pilih kategori yang sesuai (Biaya Operasional, Gaji, Marketing, dll.)</li>
                <li>Masukkan jumlah dalam Rupiah</li>
                <li>Lampirkan bukti dokumen (foto/PDF) jika diperlukan — bersifat opsional</li>
            </ul>
        </div>
    </div>

    <!-- 7. Perencanaan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">7</span>
            Perencanaan
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Modul Perencanaan membantu Anda mengatur jadwal perjalanan:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Jadwal</strong> — Buat dan kelola jadwal keberangkatan paket</li>
                <li><strong>Timeline</strong> — Lihat jadwal dalam bentuk timeline visual</li>
                <li><strong>Kalender</strong> — Lihat jadwal dalam tampilan kalender</li>
            </ul>
        </div>
    </div>

    <!-- 8. Dokumen Perjalanan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">8</span>
            Dokumen Perjalanan
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Modul ini untuk mengelola dokumen perjalanan jamaah seperti paspor, visa, tiket, dan asuransi.</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Setiap dokumen terkait dengan pelanggan tertentu</li>
                <li>Jenis dokumen: Paspor, Visa, Tiket, Asuransi, dan lainnya</li>
                <li>Dokumen dapat diunggah dan diunduh</li>
            </ul>
        </div>
    </div>

    <!-- 9. Kelompok Jamaah -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">9</span>
            Kelompok Jamaah
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Modul ini untuk mengelompokkan jamaah ke dalam grup tertentu, misalnya berdasarkan rombongan atau jadwal keberangkatan.</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Buat kelompok baru dengan nama dan deskripsi</li>
                <li>Tambahkan pelanggan ke dalam kelompok</li>
                <li>Satu pelanggan bisa berada di beberapa kelompok</li>
            </ul>
        </div>
    </div>

    @if(Auth::user()->isAdmin())
    <!-- 10. Administrasi (Admin) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">10</span>
            Administrasi (Khusus Admin)
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p><strong>Pengguna</strong> — Kelola akun pengguna yang dapat mengakses sistem. Admin dapat menambah, mengedit, dan menghapus pengguna.</p>
            <p><strong>Peran & Izin Akses</strong> — Atur peran dan izin akses setiap pengguna:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Admin</strong> — Akses penuh ke seluruh fitur termasuk manajemen pengguna dan peran</li>
                <li><strong>Finance</strong> — Akses ke keuangan, akuntansi, dan transaksi</li>
                <li><strong>Operational</strong> — Akses ke pelanggan, paket, perencanaan, dan dokumen perjalanan</li>
            </ul>
            <p><strong>Log Aktivitas</strong> — Lacak semua aktivitas yang dilakukan oleh pengguna, berguna untuk audit dan keamanan.</p>
        </div>
    </div>
    @endif

    @else
    {{-- ENGLISH VERSION --}}

    <!-- Introduction -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">M2Way Travel User Guide</h1>
        <p class="text-gray-600">Welcome to the M2Way Travel management system. This guide will help you understand and use every feature available.</p>
    </div>

    <!-- 1. Navigation -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">1</span>
            Navigation
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>The sidebar on the left is the main menu to access all features. You can collapse the sidebar by clicking the menu icon in the top left corner.</p>
            <p>Each menu is grouped by function:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Planning</strong> — Schedules, Timeline, Calendar</li>
                <li><strong>Operations</strong> — Transactions, Jamaah Groups, Customers</li>
                <li><strong>Catalog</strong> — Packages, Package Categories</li>
                <li><strong>Finance</strong> — Finance, Accounting, Accounting Categories</li>
                <li><strong>Documents</strong> — Travel Documents</li>
                <li><strong>Administration</strong> — Users, Roles, Activity Logs (Admin only)</li>
            </ul>
            <p>Each group can be expanded or collapsed by clicking the group name. The group state is saved automatically.</p>
        </div>
    </div>

    <!-- 2. Dashboard -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">2</span>
            Dashboard
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>The dashboard displays a summary of your business data:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Total customers, transactions, packages, and revenue</li>
                <li>Pending payments that need follow-up</li>
                <li>Recent transactions</li>
                <li>Monthly revenue and expense charts</li>
                <li>Package distribution (Hajj / Umroh)</li>
            </ul>
        </div>
    </div>

    <!-- 3. Customers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">3</span>
            Customers
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>The Customers module manages pilgrim / customer data.</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Searching customers</strong> — Use the search field to find by name or phone number</li>
                <li><strong>Adding customers</strong> — Click "Add Customer" and fill the form</li>
                <li><strong>Editing customers</strong> — Click the edit icon on the customer row</li>
                <li><strong>Deleting customers</strong> — Click the delete icon and confirm</li>
                <li><strong>Attachments</strong> — You can add documents (ID card, passport, etc.) to customer data</li>
            </ul>
        </div>
    </div>

    <!-- 4. Packages -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">4</span>
            Packages & Package Categories
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>The Packages module manages travel products offered (Hajj, Umroh, etc.).</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Package Categories</strong> — Manage categories like "Regular Hajj", "Umroh Plus", etc.</li>
                <li><strong>Packages</strong> — Each package has a name, category, price, quota, and description</li>
                <li><strong>Package Status</strong> — Packages can be activated or deactivated</li>
            </ul>
        </div>
    </div>

    <!-- 5. Transactions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">5</span>
            Transactions
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>The Transactions module records every package purchase by customers.</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Creating transactions</strong> — Select customer and package, set number of participants, and the system calculates totals automatically</li>
                <li><strong>Payments</strong> — Record payments from customers. Payment status changes to "Paid" when fully paid</li>
                <li><strong>Refund</strong> — Use the Refund button if a transaction is cancelled</li>
                <li><strong>Invoice</strong> — Print invoice for transactions</li>
                <li><strong>Export</strong> — Export transaction data to Excel or PDF</li>
            </ul>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-yellow-800">
                <p class="text-xs font-medium">Note: Transactions with Paid status (payment_status = paid) and Refund status cannot be deleted.</p>
            </div>
        </div>
    </div>

    <!-- 6. Finance & Accounting -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">6</span>
            Finance & Accounting
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p><strong>Finance</strong> displays overall financial reports that can be exported to PDF or Excel.</p>
            <p><strong>Accounting</strong> is used to record operational income and expenses:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Select <strong>Income</strong> or <strong>Expense</strong> type</li>
                <li>Select the appropriate category (Operational Cost, Salary, Marketing, etc.)</li>
                <li>Enter the amount in Rupiah</li>
                <li>Attach supporting documents (photo/PDF) if needed — optional</li>
            </ul>
        </div>
    </div>

    <!-- 7. Planning -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">7</span>
            Planning
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>The Planning module helps you organize travel schedules:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Schedules</strong> — Create and manage package departure schedules</li>
                <li><strong>Timeline</strong> — View schedules in a visual timeline</li>
                <li><strong>Calendar</strong> — View schedules in a calendar view</li>
            </ul>
        </div>
    </div>

    <!-- 8. Travel Documents -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">8</span>
            Travel Documents
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>This module manages pilgrim travel documents such as passports, visas, tickets, and insurance.</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Each document is linked to a specific customer</li>
                <li>Document types: Passport, Visa, Ticket, Insurance, and others</li>
                <li>Documents can be uploaded and downloaded</li>
            </ul>
        </div>
    </div>

    <!-- 9. Jamaah Groups -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">9</span>
            Jamaah Groups
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>This module groups pilgrims into specific groups, for example by batch or departure schedule.</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Create a new group with name and description</li>
                <li>Add customers to the group</li>
                <li>A customer can be in multiple groups</li>
            </ul>
        </div>
    </div>

    @if(Auth::user()->isAdmin())
    <!-- 10. Administration (Admin) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-brand/10 rounded-lg flex items-center justify-center text-brand text-sm font-bold">10</span>
            Administration (Admin Only)
        </h2>
        <div class="space-y-3 text-sm text-gray-600 leading-relaxed">
            <p><strong>Users</strong> — Manage user accounts that can access the system. Admin can add, edit, and delete users.</p>
            <p><strong>Roles & Permissions</strong> — Set user roles and access permissions:</p>
            <ul class="list-disc list-inside space-y-1 ml-4">
                <li><strong>Admin</strong> — Full access to all features including user and role management</li>
                <li><strong>Finance</strong> — Access to finance, accounting, and transaction modules</li>
                <li><strong>Operational</strong> — Access to customers, packages, planning, and travel documents</li>
            </ul>
            <p><strong>Activity Logs</strong> — Track all activities performed by users, useful for auditing and security.</p>
        </div>
    </div>
    @endif
    @endif
</div>
@endsection
