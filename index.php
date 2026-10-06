<?php
session_start();
$isLoggedIn = isset($_SESSION['login']) && $_SESSION['login'] === true;

// Alur Simulasi Login Google
if (isset($_GET['google_login']) && $_GET['google_login'] === 'true') {
    $_SESSION['login'] = true;
    $_SESSION['username'] = 'User Google';
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Keuangan Bulanan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @keyframes spinSlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spinSlow 3s linear infinite;
        }
    </style>
</head>
<body class="h-full flex flex-col md:flex-row text-slate-800 overflow-x-hidden">

    <?php if (!$isLoggedIn): ?>
    <?php $page = isset($_GET['page']) ? $_GET['page'] : 'login'; ?>
    <!-- SCREEN LOGIN, REGISTER & RESET PASSWORD -->
    <div class="fixed inset-0 bg-slate-900 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 space-y-6">
            <div class="text-center space-y-2">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500 text-white font-bold text-3xl shadow-lg shadow-emerald-500/30 flex items-center justify-center mx-auto">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">
                    <?php 
                        if ($page === 'register') echo 'Buat Akun Baru';
                        elseif ($page === 'reset') echo 'Reset Password';
                        else echo 'Selamat Datang'; 
                    ?>
                </h2>
                <p class="text-xs text-slate-500">
                    <?php 
                        if ($page === 'register') echo 'Daftar untuk mulai mengelola keuangan Anda';
                        elseif ($page === 'reset') echo 'Masukkan username & password baru Anda';
                        else echo 'Silakan login untuk mengelola keuangan Anda'; 
                    ?>
                </p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs p-3 rounded-xl text-center font-medium">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 text-xs p-3 rounded-xl text-center font-medium">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if ($page === 'register'): ?>
            <form action="register_process.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Username Baru</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="username" placeholder="Masukkan username" required class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="password" placeholder="Masukkan password" required class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-shield-halved absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="confirm_password" placeholder="Ulangi password" required class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl shadow-md transition text-sm">
                    Daftar Akun
                </button>
            </form>

            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-3 text-[11px] text-slate-400 font-medium">atau</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <a href="index.php?google_login=true" class="w-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold py-2.5 rounded-xl shadow-sm transition text-xs flex items-center justify-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                Daftar dengan Google Gmail
            </a>

            <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
                Sudah punya akun? <a href="index.php" class="text-emerald-600 font-bold hover:underline">Login di sini</a>
            </div>

            <?php elseif ($page === 'reset'): ?>
            <form action="reset_process.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Username Akun</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="username" placeholder="Masukkan username akun Anda" required class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Password Baru</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock-open absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="new_password" placeholder="Masukkan password baru" required class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl shadow-md transition text-sm">
                    Simpan Password Baru
                </button>
            </form>

            <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
                Kembali ke <a href="index.php" class="text-emerald-600 font-bold hover:underline">Halaman Login</a>
            </div>

            <?php else: ?>
            <form action="login_process.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Username</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="username" placeholder="Masukkan username" required class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-xs font-semibold text-slate-600">Password</label>
                        <a href="index.php?page=reset" class="text-[11px] text-emerald-600 hover:underline font-semibold">Lupa Password?</a>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="password" name="password" placeholder="Masukkan password" required class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl shadow-md transition text-sm">
                    Masuk
                </button>
            </form>

            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-slate-200"></div>
                <span class="flex-shrink mx-3 text-[11px] text-slate-400 font-medium">atau</span>
                <div class="flex-grow border-t border-slate-200"></div>
            </div>

            <a href="index.php?google_login=true" class="w-full border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold py-2.5 rounded-xl shadow-sm transition text-xs flex items-center justify-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                Masuk dengan Google Gmail
            </a>

            <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
                Belum punya akun? <a href="index.php?page=register" class="text-emerald-600 font-bold hover:underline">Daftar Akun Baru</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col justify-between shadow-xl z-20">
        <div>
            <!-- Logo Header -->
            <div class="p-5 flex items-center justify-between border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-emerald-500/30">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-tight">Kelola Uang</h1>
                        <p class="text-xs text-slate-400">Financial Manager</p>
                    </div>
                </div>
            </div>

            <!-- USER INFO BADGE DENGAN ANIMASI 2 LAMPU BERPUTAR -->
            <?php if ($isLoggedIn): ?>
            <div class="p-3 mx-3 my-3">
                <div class="relative p-[2px] rounded-2xl overflow-hidden shadow-lg shadow-emerald-500/10">
                    <div class="absolute inset-[-100%] animate-spin-slow bg-[conic-gradient(from_0deg,#10b981_0deg,transparent_60deg,#3b82f6_180deg,transparent_240deg,#10b981_360deg)]"></div>
                    
                    <div class="relative bg-slate-900 p-3 rounded-2xl flex items-center justify-between z-10 border border-slate-800">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center text-sm font-bold shadow-md shadow-emerald-500/20">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-[10px] uppercase font-bold text-emerald-400 tracking-wider">Aktif User</p>
                                <h3 class="text-base font-extrabold text-white truncate tracking-wide"><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                            </div>
                        </div>
                        <button onclick="openLogoutModal()" class="text-slate-400 hover:text-rose-400 text-sm p-1.5 transition rounded-lg hover:bg-slate-800" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <nav class="p-3 space-y-1 text-sm font-medium">
                <button onclick="switchTab('dashboard')" id="nav-dashboard" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-chart-pie w-5 text-emerald-400"></i> Dashboard
                </button>
                <button onclick="switchTab('transaksi')" id="nav-transaksi" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-receipt w-5 text-blue-400"></i> Transaksi
                </button>
                <button onclick="switchTab('kategori')" id="nav-kategori" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-tags w-5 text-amber-400"></i> Kategori
                </button>
                <button onclick="switchTab('budget')" id="nav-budget" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-calculator w-5 text-purple-400"></i> Budget & Anggaran
                </button>
                <button onclick="switchTab('tabungan')" id="nav-tabungan" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-piggy-bank w-5 text-pink-400"></i> Tabungan
                </button>
                <button onclick="switchTab('investasi')" id="nav-investasi" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-chart-line w-5 text-indigo-400"></i> Investasi
                </button>
                <button onclick="switchTab('utang')" id="nav-utang" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-hand-holding-dollar w-5 text-red-400"></i> Utang & Cicilan
                </button>
                <button onclick="switchTab('akun')" id="nav-akun" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-building-columns w-5 text-cyan-400"></i> Akun / Dompet
                </button>
                <button onclick="switchTab('laporan')" id="nav-laporan" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-300 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-teal-400"></i> Laporan Bulanan
                </button>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800 text-xs text-slate-400 flex flex-col gap-3">
            <div class="relative p-[2px] rounded-xl overflow-hidden shadow-lg">
                <div class="absolute inset-[-100%] animate-spin-slow bg-[conic-gradient(from_0deg,#f43f5e_0deg,transparent_60deg,#fb7185_180deg,transparent_240deg,#f43f5e_360deg)]"></div>
                <button onclick="startResetStep1()" class="relative z-10 w-full bg-slate-900 hover:bg-rose-950/80 text-rose-300 hover:text-rose-200 py-2.5 px-3 rounded-xl transition text-left font-bold flex items-center gap-2 border border-slate-800">
                    <i class="fa-solid fa-rotate-right text-rose-400"></i> Reset Data Akun Ini
                </button>
            </div>

            <?php if ($isLoggedIn): ?>
            <button onclick="openLogoutModal()" class="w-full bg-slate-800/80 hover:bg-slate-800 text-slate-400 hover:text-white py-2 rounded-xl transition text-left px-3 flex items-center gap-2 border border-slate-800">
                <i class="fa-solid fa-right-from-bracket text-xs"></i> Logout
            </button>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col overflow-y-auto min-h-screen">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex flex-wrap justify-between items-center gap-4 sticky top-0 z-10 shadow-sm">
            <div>
                <h2 id="page-title" class="text-xl font-bold text-slate-800">Dashboard</h2>
                <p id="page-subtitle" class="text-xs text-slate-500">Ringkasan kondisi keuangan Anda</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative bg-slate-100 border border-slate-200 rounded-lg px-3 py-1.5 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-emerald-600"></i>
                    <input type="month" id="selected-month" onchange="handleMonthChange()" class="bg-transparent text-sm font-semibold text-slate-700 focus:outline-none cursor-pointer">
                </div>

                <button onclick="openTransactionModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Transaksi Baru
                </button>
            </div>
        </header>

        <div class="p-6 space-y-6 flex-1">

            <!-- DASHBOARD TAB -->
            <section id="tab-dashboard" class="tab-content space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase">Saldo Saat Ini</span>
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-wallet"></i></div>
                        </div>
                        <h3 id="dash-saldo" class="text-2xl font-bold text-slate-800">Rp0</h3>
                        <p class="text-xs text-slate-500 mt-1">Total di seluruh dompet</p>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase">Pemasukan</span>
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-arrow-down-left"></i></div>
                        </div>
                        <h3 id="dash-pemasukan" class="text-2xl font-bold text-blue-600">Rp0</h3>
                        <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> Total arus masuk</p>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase">Pengeluaran</span>
                            <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center"><i class="fa-solid fa-arrow-up-right"></i></div>
                        </div>
                        <h3 id="dash-pengeluaran" class="text-2xl font-bold text-rose-600">Rp0</h3>
                        <p class="text-xs text-slate-500 mt-1">Total konsumsi & pengeluaran</p>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase">Sisa Bersih</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-scale-balanced"></i></div>
                        </div>
                        <h3 id="dash-sisa" class="text-2xl font-bold text-slate-800">Rp0</h3>
                        <p class="text-xs text-slate-500 mt-1">Pemasukan - Pengeluaran</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-900 text-white p-4 rounded-2xl flex items-center justify-between">
                        <div><p class="text-xs text-slate-400">Total Tabungan</p><h4 id="dash-tabungan" class="text-lg font-bold">Rp0</h4></div>
                        <i class="fa-solid fa-piggy-bank text-2xl text-pink-400 opacity-80"></i>
                    </div>
                    <div class="bg-slate-900 text-white p-4 rounded-2xl flex items-center justify-between">
                        <div><p class="text-xs text-slate-400">Total Investasi</p><h4 id="dash-investasi" class="text-lg font-bold">Rp0</h4></div>
                        <i class="fa-solid fa-chart-line text-2xl text-indigo-400 opacity-80"></i>
                    </div>
                    <div class="bg-slate-900 text-white p-4 rounded-2xl flex items-center justify-between">
                        <div><p class="text-xs text-slate-400">Sisa Utang Aktif</p><h4 id="dash-utang" class="text-lg font-bold text-rose-300">Rp0</h4></div>
                        <i class="fa-solid fa-hand-holding-dollar text-2xl text-red-400 opacity-80"></i>
                    </div>
                    <div class="bg-slate-900 text-white p-4 rounded-2xl flex items-center justify-between">
                        <div><p class="text-xs text-slate-400">Rata-rata Budget Terpakai</p><h4 id="dash-budget-percentage" class="text-lg font-bold text-amber-300">0%</h4></div>
                        <i class="fa-solid fa-pie-chart text-2xl text-amber-400 opacity-80"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-slate-800">Pemasukan vs Pengeluaran Bulanan</h3>
                            <span class="text-xs bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md font-semibold">Tren 3 Bulan Terakhir</span>
                        </div>
                        <div class="h-64 relative"><canvas id="chartIncomeExpense"></canvas></div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-slate-800">Pengeluaran Per Kategori</h3>
                        </div>
                        <div class="h-64 relative flex items-center justify-center"><canvas id="chartCategoryExpense"></canvas></div>
                    </div>
                </div>
            </section>

            <!-- TRANSAKSI TAB -->
            <section id="tab-transaksi" class="tab-content space-y-6 hidden">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:w-60">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="tx-search" oninput="renderTransactions()" placeholder="Cari keterangan..." class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <select id="tx-filter-type" onchange="renderTransactions()" class="py-2 px-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="ALL">Semua Jenis</option>
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                        <select id="tx-filter-account" onchange="renderTransactions()" class="py-2 px-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="ALL">Semua Akun</option>
                        </select>
                    </div>
                    <button onclick="openTransactionModal()" class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Transaksi
                    </button>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase">
                                    <th class="p-4">Tanggal</th>
                                    <th class="p-4">Jenis</th>
                                    <th class="p-4">Kategori / Sub</th>
                                    <th class="p-4">Keterangan</th>
                                    <th class="p-4">Akun</th>
                                    <th class="p-4 text-right">Nominal</th>
                                    <th class="p-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tx-table-body" class="divide-y divide-slate-100 text-slate-700"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- KATEGORI TAB -->
            <section id="tab-kategori" class="tab-content space-y-6 hidden">
                <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                    <div>
                        <h3 class="font-bold text-slate-800">Master Kategori & Subkategori</h3>
                        <p class="text-xs text-slate-500">Kelola item pilihan transaksi agar sesuai kebutuhan Anda</p>
                    </div>
                    <button onclick="openAddCategoryModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Subkategori
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="category-grid"></div>
            </section>

            <!-- BUDGET TAB -->
            <section id="tab-budget" class="tab-content space-y-6 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="budget-container"></div>
            </section>

            <!-- TABUNGAN TAB -->
            <section id="tab-tabungan" class="tab-content space-y-6 hidden">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800">Target Impian & Dana Darurat</h3>
                        <p class="text-xs text-slate-500">Pantau progres alokasi tabungan masa depan Anda</p>
                    </div>
                    <button onclick="openSavingsModal()" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Target Savings
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="savings-container"></div>
            </section>

            <!-- INVESTASI TAB -->
            <section id="tab-investasi" class="tab-content space-y-6 hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm"><p class="text-xs text-slate-500">Total Modal Investasi</p><h3 id="inv-total-modal" class="text-xl font-bold text-slate-800">Rp0</h3></div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm"><p class="text-xs text-slate-500">Nilai Portofolio Saat Ini</p><h3 id="inv-total-nilai" class="text-xl font-bold text-indigo-600">Rp0</h3></div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm"><p class="text-xs text-slate-500">Unrealized Profit / Loss</p><h3 id="inv-total-profit" class="text-xl font-bold text-emerald-600">+Rp0</h3></div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase">
                                    <th class="p-4">Tanggal Masuk</th>
                                    <th class="p-4">Produk Aset</th>
                                    <th class="p-4">Modal Awal</th>
                                    <th class="p-4">Nilai Sekarang</th>
                                    <th class="p-4">Profit / Loss</th>
                                </tr>
                            </thead>
                            <tbody id="invest-table-body" class="divide-y divide-slate-100 text-slate-700"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- UTANG TAB -->
            <section id="tab-utang" class="tab-content space-y-6 hidden">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800">Utang & Cicilan Aktif</h3>
                        <p class="text-xs text-slate-500">Monitor pembayaran cicilan rutin agar tepat waktu</p>
                    </div>
                    <button onclick="openDebtModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Catat Utang Baru
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="debt-container"></div>
            </section>

            <!-- AKUN / DOMPET TAB -->
            <section id="tab-akun" class="tab-content space-y-6 hidden">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800">Daftar Akun Bank & Dompet Digital</h3>
                        <p class="text-xs text-slate-500">Lokasi penyimpanan nyata seluruh dana Anda</p>
                    </div>
                    <button onclick="openAccountModal()" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Akun / Dompet
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="accounts-container"></div>
            </section>

            <!-- LAPORAN BULANAN TAB -->
            <section id="tab-laporan" class="tab-content space-y-6 hidden">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 text-center">
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-xs text-slate-500">Total Pemasukan</p><p id="rep-pemasukan" class="text-base font-bold text-emerald-600">Rp0</p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-xs text-slate-500">Total Pengeluaran</p><p id="rep-pengeluaran" class="text-base font-bold text-rose-600">Rp0</p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-xs text-slate-500">Dialokasikan Tabungan</p><p id="rep-tabungan" class="text-base font-bold text-pink-600">Rp0</p></div>
                        <div class="p-3 bg-slate-50 rounded-xl"><p class="text-xs text-slate-500">Dialokasikan Investasi</p><p id="rep-investasi" class="text-base font-bold text-indigo-600">Rp0</p></div>
                        <div class="p-3 bg-slate-50 rounded-xl col-span-2 sm:col-span-1"><p class="text-xs text-slate-500">Sisa Netto</p><p id="rep-sisa" class="text-base font-bold text-slate-800">Rp0</p></div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- MODALS SECTION -->

    <!-- MODAL CUSTOM ALERT (PENGGANTI POPUP ALERT DEFAULT) -->
    <div id="modal-alert" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6 space-y-4">
            <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg text-slate-800">Pemberitahuan</h3>
                <p id="alert-message-text" class="text-xs text-slate-500 mt-1 leading-relaxed"></p>
            </div>
            <div class="pt-2">
                <button type="button" onclick="closeModal('modal-alert')" class="w-full py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition">Mengerti</button>
            </div>
        </div>
    </div>

    <!-- MODAL LOGOUT CONFIRMATION -->
    <div id="modal-logout" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6 space-y-4">
            <div class="w-14 h-14 bg-slate-100 text-slate-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg text-slate-800">Konfirmasi Logout</h3>
                <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin keluar dari akun ini?</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-logout')" class="w-full py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                <a href="logout.php" class="w-full py-2.5 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md transition flex items-center justify-center">Keluar</a>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP RESET STEP 1 -->
    <div id="modal-reset-step1" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6 space-y-4">
            <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg text-slate-800">Konfirmasi Reset (1/2)</h3>
                <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin menghapus seluruh transaksi, dompet, dan target keuangan di akun ini?</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-reset-step1')" class="w-full py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                <button type="button" onclick="startResetStep2()" class="w-full py-2.5 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-md transition">Lanjut Tahap Akhir</button>
            </div>
        </div>
    </div>

    <!-- MODAL POPUP RESET STEP 2 -->
    <div id="modal-reset-step2" class="fixed inset-0 bg-slate-900/75 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden text-center p-6 space-y-4 border-2 border-rose-500">
            <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto text-3xl animate-bounce">
                <i class="fa-solid fa-radiation"></i>
            </div>
            <div>
                <span class="text-[10px] font-extrabold uppercase bg-rose-100 text-rose-700 px-2.5 py-0.5 rounded-full tracking-wider">Peringatan Terakhir!</span>
                <h3 class="font-extrabold text-xl text-slate-900 mt-2">Yakin Hapus Permanen?</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Tindakan ini **TIDAK DAPAT DIBATALKAN**. Semua catatan keuangan Anda akan hilang dan bersih total kembali ke awal.</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-reset-step2')" class="w-full py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batalkan</button>
                <button type="button" onclick="executeResetData()" class="w-full py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-lg shadow-rose-600/30 transition">YA, RESET SEKARANG</button>
            </div>
        </div>
    </div>

    <!-- Modal Subkategori -->
    <div id="modal-add-category" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-5 bg-emerald-600 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Subkategori</h3>
                <button onclick="closeModal('modal-add-category')" class="text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="saveNewCategoryItem(event)" class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Kelompok Kategori</label>
                    <select id="cat-group-select" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                        <option value="Pemasukan">Pemasukan</option>
                        <option value="Kebutuhan">Kebutuhan</option>
                        <option value="Lifestyle">Lifestyle</option>
                        <option value="Keuangan">Keuangan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Subkategori Baru</label>
                    <input type="text" id="cat-item-name" placeholder="Contoh: Skincare / Parkir / Pulsa" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-add-category')" class="px-4 py-2 text-sm text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Utang -->
    <div id="modal-debt" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-5 bg-red-600 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg" id="debt-modal-title">Catat Utang / Cicilan Baru</h3>
                <button onclick="closeModal('modal-debt')" class="text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="saveDebt(event)" class="p-5 space-y-4">
                <input type="hidden" id="debt-id">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Utang / Cicilan</label>
                    <input type="text" id="debt-title" placeholder="Contoh: Cicilan Laptop / Kredit Motor" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Utang (Rp)</label>
                    <input type="number" id="debt-total" min="1" placeholder="12000000" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Sudah Dibayar (Rp)</label>
                    <input type="number" id="debt-paid" min="0" placeholder="4000000" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Jatuh Tempo</label>
                    <input type="date" id="debt-duedate" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500">
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-debt')" class="px-4 py-2 text-sm text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Bayar Cicilan Cepat -->
    <div id="modal-pay-debt" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-5 bg-red-600 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Bayar Cicilan / Utang</h3>
                <button onclick="closeModal('modal-pay-debt')" class="text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="processPayDebt(event)" class="p-5 space-y-4">
                <input type="hidden" id="pay-debt-id">
                <div>
                    <p class="text-xs text-slate-500">Pembayaran Untuk:</p>
                    <h4 id="pay-debt-name" class="font-bold text-slate-800 text-base"></h4>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nominal Pembayaran (Rp)</label>
                    <input type="number" id="pay-debt-amount" min="1" placeholder="500000" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Bayar Menggunakan Dompet</label>
                    <select id="pay-debt-account" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500"></select>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-pay-debt')" class="px-4 py-2 text-sm text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold shadow-md">Bayar Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tabungan -->
    <div id="modal-savings" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-5 bg-pink-600 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg" id="savings-modal-title">Tambah Target Savings</h3>
                <button onclick="closeModal('modal-savings')" class="text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="saveSavings(event)" class="p-5 space-y-4">
                <input type="hidden" id="savings-id">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Target Impian</label>
                    <input type="text" id="savings-title" placeholder="Contoh: Dana Darurat / Laptop New" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Target Nominal (Rp)</label>
                    <input type="number" id="savings-target" min="1" placeholder="10000000" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Terkumpul Saat Ini (Rp)</label>
                    <input type="number" id="savings-current" min="0" placeholder="5000000" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-pink-500">
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-savings')" class="px-4 py-2 text-sm text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-semibold shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Setor Tabungan Cepat -->
    <div id="modal-deposit-savings" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-5 bg-pink-600 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Setor Alokasi Tabungan</h3>
                <button onclick="closeModal('modal-deposit-savings')" class="text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="processDepositSavings(event)" class="p-5 space-y-4">
                <input type="hidden" id="deposit-savings-id">
                <div>
                    <p class="text-xs text-slate-500">Target Alokasi:</p>
                    <h4 id="deposit-savings-name" class="font-bold text-slate-800 text-base"></h4>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nominal Setoran (Rp)</label>
                    <input type="number" id="deposit-savings-amount" min="1" placeholder="200000" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Ambil Dari Dompet</label>
                    <select id="deposit-savings-account" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-pink-500"></select>
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-deposit-savings')" class="px-4 py-2 text-sm text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-semibold shadow-md">Setor Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Dompet -->
    <div id="modal-account" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-5 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Akun / Dompet</h3>
                <button onclick="closeModal('modal-account')" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form onsubmit="saveAccount(event)" class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Bank / Dompet Digital</label>
                    <select id="acc-name-select" onchange="toggleCustomAccInput()" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500">
                        <option value="Cash">Cash (Uang Tunai)</option>
                        <option value="BCA">BCA</option>
                        <option value="Mandiri">Bank Mandiri</option>
                        <option value="BNI">BNI</option>
                        <option value="BRI">BRI</option>
                        <option value="BSI">BSI (Bank Syariah)</option>
                        <option value="DANA">DANA</option>
                        <option value="OVO">OVO</option>
                        <option value="GoPay">GoPay</option>
                        <option value="ShopeePay">ShopeePay</option>
                        <option value="OTHER">+ Kustom (Ketik Sendiri)</option>
                    </select>
                </div>

                <div id="field-custom-acc" class="hidden">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Bank / Akun Kustom</label>
                    <input type="text" id="acc-name-custom" placeholder="Contoh: Bank DKI / Dompetku" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Saldo Awal (Rp)</label>
                    <input type="number" id="acc-balance" min="0" placeholder="1000000" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-account')" class="px-4 py-2 text-sm text-slate-600">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl font-semibold shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transaction Modal -->
    <div id="modal-transaction" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in">
            <div class="p-5 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg" id="modal-tx-title">Tambah Transaksi</h3>
                <button onclick="closeModal('modal-transaction')" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="form-tx" onsubmit="saveTransaction(event)" class="p-5 space-y-4">
                <input type="hidden" id="tx-id">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Transaksi</label>
                    <select id="tx-type" onchange="handleTxTypeChange()" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                        <option value="Pengeluaran">Pengeluaran</option>
                        <option value="Pemasukan">Pemasukan</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                        <input type="date" id="tx-date" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nominal (Rp)</label>
                        <input type="number" id="tx-amount" min="1" placeholder="35000" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div id="field-account">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Akun / Dompet</label>
                    <select id="tx-account" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500"></select>
                </div>

                <div id="field-transfer-dest" class="hidden">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tujuan Transfer</label>
                    <select id="tx-account-to" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500"></select>
                </div>

                <div id="field-category-wrapper" class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
                        <select id="tx-category" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500"></select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Subkategori</label>
                        <input type="text" id="tx-subcategory" placeholder="Makan Siang / Skincare" class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan Catatan</label>
                    <input type="text" id="tx-note" placeholder="Contoh: Makan siang nasi padang" required class="w-full p-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-transaction')" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="modal-delete" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden text-center p-6 space-y-4">
            <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg text-slate-800">Hapus Item Ini?</h3>
                <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-delete')" class="w-full py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                <button type="button" id="confirm-delete-btn" class="w-full py-2.5 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md transition">Hapus</button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const CURRENT_USER = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";

        function getStorageKey() {
            return CURRENT_USER ? 'financial_manager_db_' + CURRENT_USER : 'financial_manager_db_guest';
        }

        const DEFAULT_DATA = {
            accounts: [],
            categories: {
                Pemasukan: ['Gaji', 'Freelance', 'Bisnis', 'Bonus', 'Hadiah', 'Lainnya'],
                Kebutuhan: ['Makanan', 'Transportasi', 'Listrik', 'Air', 'Internet', 'Pulsa', 'Kos/Sewa', 'Kesehatan'],
                Lifestyle: ['Nongkrong', 'Hiburan', 'Gaming', 'Belanja', 'Langganan', 'Traveling'],
                Keuangan: ['Tabungan', 'Investasi', 'Cicilan', 'Pembayaran utang']
            },
            budgets: [],
            savings: [],
            investments: [],
            debts: [],
            transactions: []
        };

        let state = {};
        let pendingDeleteId = null;
        let pendingDeleteType = 'tx';

        function showAlertModal(msg) {
            document.getElementById('alert-message-text').innerText = msg;
            document.getElementById('modal-alert').classList.remove('hidden');
        }

        function openLogoutModal() {
            document.getElementById('modal-logout').classList.remove('hidden');
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
        }

        function initMonthSelector() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            document.getElementById('selected-month').value = `${yyyy}-${mm}`;
        }

        function getSelectedMonthFilter() {
            return document.getElementById('selected-month').value;
        }

        function handleMonthChange() {
            refreshAllViews();
        }

        function recalculateAccountBalances() {
            state.accounts.forEach(acc => {
                let currentBal = acc.initialBalance !== undefined ? acc.initialBalance : (acc.balance || 0);
                acc.initialBalance = currentBal; 

                state.transactions.forEach(tx => {
                    if (tx.type === 'Pemasukan' && tx.account === acc.name) {
                        currentBal += tx.amount;
                    } else if (tx.type === 'Pengeluaran' && tx.account === acc.name) {
                        currentBal -= tx.amount;
                    } else if (tx.type === 'Transfer') {
                        if (tx.account === acc.name) currentBal -= tx.amount;
                        if (tx.accountTo === acc.name) currentBal += tx.amount;
                    }
                });
                acc.balance = currentBal;
            });
        }

        function initData() {
            initMonthSelector();
            const storageKey = getStorageKey();
            const localData = localStorage.getItem(storageKey);
            if (localData) {
                try { state = JSON.parse(localData); } catch (e) { state = JSON.parse(JSON.stringify(DEFAULT_DATA)); }
            } else {
                state = JSON.parse(JSON.stringify(DEFAULT_DATA));
                saveStateToStorage();
            }
            recalculateAccountBalances();
            refreshAllViews();
        }

        function saveStateToStorage() {
            const storageKey = getStorageKey();
            localStorage.setItem(storageKey, JSON.stringify(state));
        }

        function startResetStep1() {
            document.getElementById('modal-reset-step1').classList.remove('hidden');
        }

        function startResetStep2() {
            closeModal('modal-reset-step1');
            document.getElementById('modal-reset-step2').classList.remove('hidden');
        }

        function executeResetData() {
            state = JSON.parse(JSON.stringify(DEFAULT_DATA));
            saveStateToStorage();
            refreshAllViews();
            closeModal('modal-reset-step2');
        }

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('bg-slate-800', 'text-white'));
            
            const selectedTab = document.getElementById(`tab-${tabId}`);
            const selectedNav = document.getElementById(`nav-${tabId}`);
            if (selectedTab) selectedTab.classList.remove('hidden');
            if (selectedNav) selectedNav.classList.add('bg-slate-800', 'text-white');

            const titles = {
                dashboard: ['Dashboard', 'Ringkasan kondisi finansial Anda bulan ini'],
                transaksi: ['Catatan Transaksi', 'Kelola semua riwayat arus kas pemasukan & pengeluaran'],
                kategori: ['Kategori Keuangan', 'Pengelompokan jenis transaksi Anda'],
                budget: ['Batas Budget', 'Monitoring limit pengeluaran bulanan'],
                tabungan: ['Target Tabungan', 'Monitoring alokasi impian dan dana darurat'],
                investasi: ['Portofolio Investasi', 'Pantau nilai modal dan keuntungan investasi'],
                utang: ['Utang & Cicilan', 'Catatan kewajiban tagihan dan tanggal jatuh tempo'],
                akun: ['Akun & Dompet', 'Monitoring saldo di setiap rekening bank dan e-wallet'],
                laporan: ['Laporan Bulanan', 'Ringkasan performa dan komparasi bulanan']
            };
            if (titles[tabId]) {
                document.getElementById('page-title').innerText = titles[tabId][0];
                document.getElementById('page-subtitle').innerText = titles[tabId][1];
            }

            if (tabId === 'dashboard') renderCharts();
        }

        function refreshAllViews() {
            recalculateAccountBalances();
            renderAccounts();
            renderTransactions();
            renderBudgets();
            renderSavings();
            renderInvestments();
            renderDebts();
            renderCategories();
            renderDashboardCards();
            renderReports();
        }

        function openAddCategoryModal() {
            document.getElementById('cat-item-name').value = '';
            document.getElementById('modal-add-category').classList.remove('hidden');
        }

        function saveNewCategoryItem(e) {
            e.preventDefault();
            const group = document.getElementById('cat-group-select').value;
            const itemName = document.getElementById('cat-item-name').value.trim();

            if (itemName && state.categories[group]) {
                if (!state.categories[group].includes(itemName)) {
                    state.categories[group].push(itemName);
                    saveStateToStorage();
                    refreshAllViews();
                }
            }
            closeModal('modal-add-category');
        }

        function renderCategories() {
            const grid = document.getElementById('category-grid');
            grid.innerHTML = '';
            for (const [group, items] of Object.entries(state.categories)) {
                const card = document.createElement('div');
                card.className = 'bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3';
                let listHtml = items.map(i => `<li class="py-1 px-2.5 bg-slate-50 rounded-lg text-xs font-medium text-slate-700 flex justify-between items-center"><span>${i}</span></li>`).join('');
                card.innerHTML = `<h4 class="font-bold text-slate-800 border-b border-slate-100 pb-2">${group}</h4><ul class="space-y-1.5">${listHtml}</ul>`;
                grid.appendChild(card);
            }
        }

        let incomeExpenseChart = null, categoryPieChart = null;

        function renderDashboardCards() {
            const filterMonth = getSelectedMonthFilter();
            const totalSaldo = state.accounts.reduce((acc, a) => acc + a.balance, 0);
            document.getElementById('dash-saldo').innerText = formatRupiah(totalSaldo);

            const monthIncome = state.transactions.filter(t => t.type === 'Pemasukan' && t.date.startsWith(filterMonth)).reduce((acc, t) => acc + t.amount, 0);
            const monthExpense = state.transactions.filter(t => t.type === 'Pengeluaran' && t.date.startsWith(filterMonth)).reduce((acc, t) => acc + t.amount, 0);

            document.getElementById('dash-pemasukan').innerText = formatRupiah(monthIncome);
            document.getElementById('dash-pengeluaran').innerText = formatRupiah(monthExpense);
            document.getElementById('dash-sisa').innerText = formatRupiah(monthIncome - monthExpense);

            const totalTabungan = state.savings.reduce((acc, s) => acc + s.current, 0);
            const totalInvestasi = state.investments.reduce((acc, i) => acc + i.currentValue, 0);
            const totalUtang = state.debts.reduce((acc, d) => acc + (d.total - d.paid), 0);

            document.getElementById('dash-tabungan').innerText = formatRupiah(totalTabungan);
            document.getElementById('dash-investasi').innerText = formatRupiah(totalInvestasi);
            document.getElementById('dash-utang').innerText = formatRupiah(totalUtang);

            const expenseMap = {};
            state.transactions.filter(t => t.type === 'Pengeluaran' && t.date.startsWith(filterMonth)).forEach(t => {
                expenseMap[t.category] = (expenseMap[t.category] || 0) + t.amount;
            });
            let totalBudgetLimit = 0, totalBudgetSpent = 0;
            state.budgets.forEach(b => {
                totalBudgetLimit += b.limit;
                totalBudgetSpent += (expenseMap[b.category] || 0);
            });
            const avgBudgetPct = totalBudgetLimit > 0 ? Math.round((totalBudgetSpent / totalBudgetLimit) * 100) : 0;
            document.getElementById('dash-budget-percentage').innerText = `${avgBudgetPct}%`;
        }

        function renderCharts() {
            const selectedMonthStr = getSelectedMonthFilter();
            const parts = selectedMonthStr.split('-');
            const year = parseInt(parts[0]);
            const month = parseInt(parts[1]) - 1;

            const monthLabels = [];
            const monthKeys = [];
            
            for (let i = 2; i >= 0; i--) {
                const d = new Date(year, month - i, 1);
                const yKey = d.getFullYear();
                const mKey = String(d.getMonth() + 1).padStart(2, '0');
                const mName = d.toLocaleString('id-ID', { month: 'long' });
                
                monthKeys.push(`${yKey}-${mKey}`);
                monthLabels.push(i === 0 ? `${mName} (Aktif)` : mName);
            }

            const incomeData = monthKeys.map(key => {
                return state.transactions.filter(t => t.type === 'Pemasukan' && t.date.startsWith(key)).reduce((sum, t) => sum + t.amount, 0);
            });

            const expenseData = monthKeys.map(key => {
                return state.transactions.filter(t => t.type === 'Pengeluaran' && t.date.startsWith(key)).reduce((sum, t) => sum + t.amount, 0);
            });

            const ctx1 = document.getElementById('chartIncomeExpense').getContext('2d');
            if (incomeExpenseChart) incomeExpenseChart.destroy();

            incomeExpenseChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [
                        { label: 'Pemasukan', data: incomeData, backgroundColor: '#3b82f6', borderRadius: 6 },
                        { label: 'Pengeluaran', data: expenseData, backgroundColor: '#f43f5e', borderRadius: 6 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
            });

            const ctx2 = document.getElementById('chartCategoryExpense').getContext('2d');
            if (categoryPieChart) categoryPieChart.destroy();

            const catMap = {};
            state.transactions.filter(t => t.type === 'Pengeluaran' && t.date.startsWith(selectedMonthStr)).forEach(t => {
                catMap[t.category] = (catMap[t.category] || 0) + t.amount;
            });

            const labels = Object.keys(catMap);
            const data = Object.values(catMap);

            categoryPieChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: labels.length > 0 ? labels : ['Belum ada pengeluaran'],
                    datasets: [{
                        data: data.length > 0 ? data : [1],
                        backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899', '#64748b']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        }

        function renderDebts() {
            const container = document.getElementById('debt-container');
            container.innerHTML = '';
            if (state.debts.length === 0) {
                container.innerHTML = `<div class="col-span-full p-8 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">Belum ada riwayat utang/cicilan recorded. Klik "Catat Utang Baru" untuk mulai.</div>`;
                return;
            }
            state.debts.forEach(d => {
                const remaining = d.total - d.paid;
                const percent = Math.min(Math.round((d.paid / d.total) * 100), 100);
                const card = document.createElement('div');
                card.className = 'bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3 relative';
                card.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-slate-800 text-base">${d.title}</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Jatuh Tempo: ${d.dueDate}</p>
                        </div>
                        <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-100">Sisa: ${formatRupiah(remaining)}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden my-2">
                        <div class="bg-red-500 h-3 rounded-full transition-all duration-500" style="width: ${percent}%"></div>
                    </div>
                    <div class="flex justify-between items-center text-xs text-slate-500 pt-1">
                        <span>Total: ${formatRupiah(d.total)}</span>
                        <span>Sudah Dibayar: <strong class="text-emerald-600 font-bold">${formatRupiah(d.paid)}</strong></span>
                    </div>
                    <div class="pt-2 flex justify-between items-center border-t border-slate-100">
                        <div class="flex gap-2">
                            <button onclick="editDebt('${d.id}')" class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-lg"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                            <button onclick="deleteDebt('${d.id}')" class="text-xs text-rose-500 hover:text-rose-700 flex items-center gap-1 bg-rose-50 px-2.5 py-1 rounded-lg"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </div>
                        <button onclick="openPayDebtModal('${d.id}')" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded-lg font-semibold shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-hand-holding-dollar"></i> + Bayar Cicilan
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function openDebtModal() {
            document.getElementById('debt-modal-title').innerText = 'Catat Utang / Cicilan Baru';
            document.getElementById('debt-id').value = '';
            document.getElementById('debt-title').value = '';
            document.getElementById('debt-total').value = '';
            document.getElementById('debt-paid').value = '0';
            document.getElementById('debt-duedate').value = new Date().toISOString().split('T')[0];
            document.getElementById('modal-debt').classList.remove('hidden');
        }

        function editDebt(id) {
            const d = state.debts.find(item => item.id === id);
            if (d) {
                document.getElementById('debt-modal-title').innerText = 'Edit Data Utang';
                document.getElementById('debt-id').value = d.id;
                document.getElementById('debt-title').value = d.title;
                document.getElementById('debt-total').value = d.total;
                document.getElementById('debt-paid').value = d.paid;
                document.getElementById('debt-duedate').value = d.dueDate;
                document.getElementById('modal-debt').classList.remove('hidden');
            }
        }

        function saveDebt(e) {
            e.preventDefault();
            const id = document.getElementById('debt-id').value;
            const title = document.getElementById('debt-title').value;
            const total = parseFloat(document.getElementById('debt-total').value) || 0;
            const paid = parseFloat(document.getElementById('debt-paid').value) || 0;
            const dueDate = document.getElementById('debt-duedate').value;

            if (id) {
                const d = state.debts.find(item => item.id === id);
                if (d) { d.title = title; d.total = total; d.paid = paid; d.dueDate = dueDate; }
            } else {
                state.debts.push({ id: 'd-' + Date.now(), title, total, paid, dueDate });
            }
            saveStateToStorage();
            refreshAllViews();
            closeModal('modal-debt');
        }

        function openPayDebtModal(id) {
            if (state.accounts.length === 0) { showAlertModal('Silakan buat Akun / Dompet terlebih dahulu!'); switchTab('akun'); return; }
            const d = state.debts.find(item => item.id === id);
            if (d) {
                document.getElementById('pay-debt-id').value = d.id;
                document.getElementById('pay-debt-name').innerText = d.title;
                document.getElementById('pay-debt-amount').value = '';
                const selectAcc = document.getElementById('pay-debt-account');
                selectAcc.innerHTML = '';
                state.accounts.forEach(a => { selectAcc.innerHTML += `<option value="${a.name}">${a.name} (${formatRupiah(a.balance)})</option>`; });
                document.getElementById('modal-pay-debt').classList.remove('hidden');
            }
        }

        function processPayDebt(e) {
            e.preventDefault();
            const id = document.getElementById('pay-debt-id').value;
            const amount = parseFloat(document.getElementById('pay-debt-amount').value) || 0;
            const account = document.getElementById('pay-debt-account').value;
            const d = state.debts.find(item => item.id === id);
            if (d) {
                d.paid += amount;
                state.transactions.push({
                    id: 'tx-' + Date.now(),
                    date: new Date().toISOString().split('T')[0],
                    type: 'Pengeluaran', category: 'Keuangan', subcategory: 'Pembayaran utang',
                    note: 'Bayar cicilan: ' + d.title, account: account, amount: amount
                });
                saveStateToStorage();
                refreshAllViews();
                closeModal('modal-pay-debt');
            }
        }

        function deleteDebt(id) { pendingDeleteId = id; pendingDeleteType = 'debt'; document.getElementById('modal-delete').classList.remove('hidden'); }

        function renderSavings() {
            const container = document.getElementById('savings-container');
            container.innerHTML = '';
            if (state.savings.length === 0) {
                container.innerHTML = `<div class="col-span-full p-8 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">Belum ada target tabungan. Klik "Tambah Target Savings" untuk mulai.</div>`;
                return;
            }
            state.savings.forEach(s => {
                const remaining = s.target - s.current;
                const percent = Math.min(Math.round((s.current / s.target) * 100), 100);
                const card = document.createElement('div');
                card.className = 'bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between';
                card.innerHTML = `
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-800 text-base">${s.title}</h4>
                            <span class="text-xs bg-pink-100 text-pink-700 px-2 py-0.5 rounded-full font-semibold">${percent}%</span>
                        </div>
                        <p class="text-xs text-slate-400">Target: ${formatRupiah(s.target)}</p>
                    </div>
                    <div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden mb-2">
                            <div class="bg-pink-500 h-2.5 rounded-full transition-all duration-500" style="width: ${percent}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Terkumpul: <strong class="text-slate-800 font-bold">${formatRupiah(s.current)}</strong></span>
                            <span>Kekurangan: <strong class="text-rose-600 font-bold">${formatRupiah(remaining < 0 ? 0 : remaining)}</strong></span>
                        </div>
                    </div>
                    <div class="pt-2 flex justify-between items-center border-t border-slate-100">
                        <div class="flex gap-2">
                            <button onclick="editSavings('${s.id}')" class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1 bg-slate-100 px-2 py-1 rounded-lg"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                            <button onclick="deleteSavings('${s.id}')" class="text-xs text-rose-500 hover:text-rose-700 flex items-center gap-1 bg-rose-50 px-2 py-1 rounded-lg"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </div>
                        <button onclick="openDepositSavingsModal('${s.id}')" class="bg-pink-600 hover:bg-pink-700 text-white text-xs px-3 py-1.5 rounded-lg font-semibold shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-piggy-bank"></i> + Setor Tabungan
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function openSavingsModal() {
            document.getElementById('savings-modal-title').innerText = 'Tambah Target Savings';
            document.getElementById('savings-id').value = '';
            document.getElementById('savings-title').value = '';
            document.getElementById('savings-target').value = '';
            document.getElementById('savings-current').value = '0';
            document.getElementById('modal-savings').classList.remove('hidden');
        }

        function editSavings(id) {
            const s = state.savings.find(item => item.id === id);
            if (s) {
                document.getElementById('savings-modal-title').innerText = 'Edit Target Tabungan';
                document.getElementById('savings-id').value = s.id;
                document.getElementById('savings-title').value = s.title;
                document.getElementById('savings-target').value = s.target;
                document.getElementById('savings-current').value = s.current;
                document.getElementById('modal-savings').classList.remove('hidden');
            }
        }

        function saveSavings(e) {
            e.preventDefault();
            const id = document.getElementById('savings-id').value;
            const title = document.getElementById('savings-title').value;
            const target = parseFloat(document.getElementById('savings-target').value) || 0;
            const current = parseFloat(document.getElementById('savings-current').value) || 0;

            if (id) {
                const s = state.savings.find(item => item.id === id);
                if (s) { s.title = title; s.target = target; s.current = current; }
            } else {
                state.savings.push({ id: 's-' + Date.now(), title, target, current });
            }
            saveStateToStorage();
            refreshAllViews();
            closeModal('modal-savings');
        }

        function openDepositSavingsModal(id) {
            if (state.accounts.length === 0) { showAlertModal('Silakan buat Akun / Dompet terlebih dahulu!'); switchTab('akun'); return; }
            const s = state.savings.find(item => item.id === id);
            if (s) {
                document.getElementById('deposit-savings-id').value = s.id;
                document.getElementById('deposit-savings-name').innerText = s.title;
                document.getElementById('deposit-savings-amount').value = '';
                const selectAcc = document.getElementById('deposit-savings-account');
                selectAcc.innerHTML = '';
                state.accounts.forEach(a => { selectAcc.innerHTML += `<option value="${a.name}">${a.name} (${formatRupiah(a.balance)})</option>`; });
                document.getElementById('modal-deposit-savings').classList.remove('hidden');
            }
        }

        function processDepositSavings(e) {
            e.preventDefault();
            const id = document.getElementById('deposit-savings-id').value;
            const amount = parseFloat(document.getElementById('deposit-savings-amount').value) || 0;
            const account = document.getElementById('deposit-savings-account').value;
            const s = state.savings.find(item => item.id === id);
            if (s) {
                s.current += amount;
                state.transactions.push({
                    id: 'tx-' + Date.now(),
                    date: new Date().toISOString().split('T')[0],
                    type: 'Pengeluaran', category: 'Keuangan', subcategory: 'Tabungan',
                    note: 'Setor tabungan: ' + s.title, account: account, amount: amount
                });
                saveStateToStorage();
                refreshAllViews();
                closeModal('modal-deposit-savings');
            }
        }

        function deleteSavings(id) { pendingDeleteId = id; pendingDeleteType = 'savings'; document.getElementById('modal-delete').classList.remove('hidden'); }

        function renderAccounts() {
            const container = document.getElementById('accounts-container');
            const selectTxAcc = document.getElementById('tx-account');
            const selectTxAccTo = document.getElementById('tx-account-to');
            const filterTxAcc = document.getElementById('tx-filter-account');

            container.innerHTML = '';
            selectTxAcc.innerHTML = '';
            selectTxAccTo.innerHTML = '';
            filterTxAcc.innerHTML = '<option value="ALL">Semua Akun</option>';

            let totalSaldoAll = 0;

            if (state.accounts.length === 0) {
                container.innerHTML = `<div class="col-span-full p-8 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">Belum ada akun / dompet. Klik "Tambah Akun / Dompet" untuk mulai.</div>`;
                return;
            }

            const bankMeta = {
                'Cash': { icon: 'fa-money-bill-wave', color: 'bg-emerald-500' },
                'BCA': { icon: 'fa-building-columns', color: 'bg-blue-600' },
                'Mandiri': { icon: 'fa-building-columns', color: 'bg-amber-600' },
                'BNI': { icon: 'fa-building-columns', color: 'bg-orange-600' },
                'BRI': { icon: 'fa-building-columns', color: 'bg-blue-700' },
                'BSI': { icon: 'fa-building-columns', color: 'bg-teal-600' },
                'DANA': { icon: 'fa-mobile-screen-button', color: 'bg-sky-500' },
                'OVO': { icon: 'fa-wallet', color: 'bg-purple-600' },
                'GoPay': { icon: 'fa-wallet', color: 'bg-green-600' },
                'ShopeePay': { icon: 'fa-wallet', color: 'bg-orange-500' }
            };

            state.accounts.forEach(acc => {
                totalSaldoAll += acc.balance;
                const meta = bankMeta[acc.name] || { icon: 'fa-wallet', color: 'bg-slate-700' };
                const card = document.createElement('div');
                card.className = 'bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between relative group';
                card.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl ${meta.color} text-white flex items-center justify-center text-xl shadow-md">
                            <i class="fa-solid ${meta.icon}"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">${acc.name}</h4>
                            <p class="text-xs text-slate-400">Saldo Riil</p>
                            <p class="text-lg font-bold ${acc.balance < 0 ? 'text-rose-600' : 'text-emerald-600'} mt-0.5">${formatRupiah(acc.balance)}</p>
                        </div>
                    </div>
                    <button onclick="deleteAccount('${acc.id}')" class="text-slate-300 hover:text-rose-600 p-2 transition" title="Hapus Dompet Ini">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                `;
                container.appendChild(card);
                selectTxAcc.innerHTML += `<option value="${acc.name}">${acc.name}</option>`;
                selectTxAccTo.innerHTML += `<option value="${acc.name}">${acc.name}</option>`;
                filterTxAcc.innerHTML += `<option value="${acc.name}">${acc.name}</option>`;
            });

            const totalCard = document.createElement('div');
            totalCard.className = 'bg-slate-900 text-white p-5 rounded-2xl shadow-md flex items-center justify-between sm:col-span-2 lg:col-span-3';
            totalCard.innerHTML = `
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase">Total Saldo Tergabung</span>
                    <h3 class="text-2xl font-bold text-emerald-400">${formatRupiah(totalSaldoAll)}</h3>
                </div>
                <i class="fa-solid fa-sack-dollar text-3xl text-emerald-400"></i>
            `;
            container.appendChild(totalCard);
        }

        function toggleCustomAccInput() {
            const selectVal = document.getElementById('acc-name-select').value;
            const customWrapper = document.getElementById('field-custom-acc');
            if (selectVal === 'OTHER') customWrapper.classList.remove('hidden');
            else customWrapper.classList.add('hidden');
        }

        function openAccountModal() {
            document.getElementById('acc-name-select').value = 'Cash';
            document.getElementById('acc-name-custom').value = '';
            document.getElementById('acc-balance').value = '';
            toggleCustomAccInput();
            document.getElementById('modal-account').classList.remove('hidden');
        }

        function saveAccount(e) {
            e.preventDefault();
            const selectVal = document.getElementById('acc-name-select').value;
            let name = selectVal;
            if (selectVal === 'OTHER') {
                name = document.getElementById('acc-name-custom').value.trim();
                if (!name) { showAlertModal('Harap isi nama bank / akun kustom Anda!'); return; }
            }
            const balance = parseFloat(document.getElementById('acc-balance').value) || 0;
            state.accounts.push({ id: 'acc-' + Date.now(), name, initialBalance: balance, balance: balance });
            saveStateToStorage();
            refreshAllViews();
            closeModal('modal-account');
        }

        function deleteAccount(id) { pendingDeleteId = id; pendingDeleteType = 'acc'; document.getElementById('modal-delete').classList.remove('hidden'); }

        function renderTransactions() {
            const tbody = document.getElementById('tx-table-body');
            const searchVal = document.getElementById('tx-search').value.toLowerCase();
            const filterType = document.getElementById('tx-filter-type').value;
            const filterAcc = document.getElementById('tx-filter-account').value;
            const filterMonth = getSelectedMonthFilter();

            tbody.innerHTML = '';

            const filtered = state.transactions.filter(tx => {
                const matchSearch = tx.note.toLowerCase().includes(searchVal) || tx.category.toLowerCase().includes(searchVal);
                const matchType = filterType === 'ALL' || tx.type === filterType;
                const matchAcc = filterAcc === 'ALL' || tx.account === filterAcc || tx.accountTo === filterAcc;
                const matchMonth = tx.date.startsWith(filterMonth);
                return matchSearch && matchType && matchAcc && matchMonth;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-slate-400">Belum ada transaksi recorded di bulan ini.</td></tr>`;
                return;
            }

            filtered.sort((a,b) => new Date(b.date) - new Date(a.date)).forEach(tx => {
                let badgeClass = tx.type === 'Pemasukan' ? 'bg-blue-100 text-blue-700' : (tx.type === 'Pengeluaran' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700');
                let amountClass = tx.type === 'Pemasukan' ? 'text-blue-600 font-bold' : (tx.type === 'Pengeluaran' ? 'text-rose-600 font-bold' : 'text-slate-700 font-semibold');

                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/80 transition";
                tr.innerHTML = `
                    <td class="p-4 text-slate-500 text-xs">${tx.date}</td>
                    <td class="p-4"><span class="px-2.5 py-1 rounded-md text-xs font-semibold ${badgeClass}">${tx.type}</span></td>
                    <td class="p-4">
                        <div class="font-medium text-slate-800">${tx.category}</div>
                        <div class="text-xs text-slate-400">${tx.subcategory || '-'}</div>
                    </td>
                    <td class="p-4 text-slate-600">${tx.note}</td>
                    <td class="p-4 font-medium text-slate-700">${tx.type === 'Transfer' ? `${tx.account} →${tx.accountTo}` : tx.account}</td>
                    <td class="p-4 text-right ${amountClass}">${tx.type === 'Pengeluaran' ? '-' : (tx.type === 'Pemasukan' ? '+' : '')}${formatRupiah(tx.amount)}</td>
                    <td class="p-4 text-center">
                        <button onclick="deleteTransaction('${tx.id}')" class="text-slate-400 hover:text-rose-600 p-1 transition"><i class="fa-solid fa-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function openTransactionModal() {
            if (state.accounts.length === 0) { showAlertModal('Silakan buat Akun / Dompet terlebih dahulu sebelum menambah transaksi!'); switchTab('akun'); return; }
            document.getElementById('tx-id').value = '';
            document.getElementById('tx-date').value = new Date().toISOString().split('T')[0];
            document.getElementById('tx-amount').value = '';
            document.getElementById('tx-note').value = '';
            document.getElementById('modal-transaction').classList.remove('hidden');
            handleTxTypeChange();
        }

        function handleTxTypeChange() {
            const type = document.getElementById('tx-type').value;
            const transferDest = document.getElementById('field-transfer-dest');
            const categoryWrapper = document.getElementById('field-category-wrapper');

            if (type === 'Transfer') {
                transferDest.classList.remove('hidden');
                categoryWrapper.classList.add('hidden');
            } else {
                transferDest.classList.add('hidden');
                categoryWrapper.classList.remove('hidden');
                updateCategoryDropdowns(type);
            }
        }

        function updateCategoryDropdowns(type) {
            const catSelect = document.getElementById('tx-category');
            catSelect.innerHTML = '';
            let categories = type === 'Pemasukan' ? state.categories.Pemasukan : [...state.categories.Kebutuhan, ...state.categories.Lifestyle, ...state.categories.Keuangan];
            categories.forEach(c => { catSelect.innerHTML += `<option value="${c}">${c}</option>`; });
        }

        function saveTransaction(e) {
            e.preventDefault();
            const type = document.getElementById('tx-type').value;
            const date = document.getElementById('tx-date').value;
            const amount = parseFloat(document.getElementById('tx-amount').value) || 0;
            const account = document.getElementById('tx-account').value;
            const note = document.getElementById('tx-note').value;

            let category = 'Transfer';
            let subcategory = 'Antar Akun';
            let accountTo = '';

            if (type === 'Transfer') {
                accountTo = document.getElementById('tx-account-to').value;
                if (account === accountTo) { showAlertModal('Akun asal dan akun tujuan transfer tidak boleh sama!'); return; }
            } else {
                category = document.getElementById('tx-category').value;
                subcategory = document.getElementById('tx-subcategory').value;
            }

            state.transactions.push({ id: 'tx-' + Date.now(), date, type, category, subcategory, note, account, accountTo, amount });
            saveStateToStorage();
            refreshAllViews();
            closeModal('modal-transaction');
        }

        function deleteTransaction(id) { pendingDeleteId = id; pendingDeleteType = 'tx'; document.getElementById('modal-delete').classList.remove('hidden'); }

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (pendingDeleteId) {
                if (pendingDeleteType === 'tx') state.transactions = state.transactions.filter(t => t.id !== pendingDeleteId);
                else if (pendingDeleteType === 'acc') state.accounts = state.accounts.filter(a => a.id !== pendingDeleteId);
                else if (pendingDeleteType === 'debt') state.debts = state.debts.filter(d => d.id !== pendingDeleteId);
                else if (pendingDeleteType === 'savings') state.savings = state.savings.filter(s => s.id !== pendingDeleteId);
                saveStateToStorage();
                refreshAllViews();
                pendingDeleteId = null;
            }
            closeModal('modal-delete');
        });

        function renderBudgets() {
            const container = document.getElementById('budget-container');
            container.innerHTML = '';
            if (state.budgets.length === 0) {
                container.innerHTML = `<div class="col-span-full p-8 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">Belum ada target anggaran.</div>`;
                return;
            }
            const filterMonth = getSelectedMonthFilter();
            const expenseMap = {};
            state.transactions.filter(t => t.type === 'Pengeluaran' && t.date.startsWith(filterMonth)).forEach(t => {
                expenseMap[t.category] = (expenseMap[t.category] || 0) + t.amount;
            });

            state.budgets.forEach(b => {
                const spent = expenseMap[b.category] || 0;
                const remaining = b.limit - spent;
                const percent = Math.min(Math.round((spent / b.limit) * 100), 100);
                let barColor = percent > 75 ? (percent >= 100 ? 'bg-rose-500' : 'bg-amber-500') : 'bg-emerald-500';

                const card = document.createElement('div');
                card.className = 'bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3';
                card.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div><h4 class="font-bold text-slate-800">${b.category}</h4><p class="text-xs text-slate-400">Budget: ${formatRupiah(b.limit)}</p></div>
                        <span class="text-sm font-bold ${percent >= 100 ? 'text-rose-600' : 'text-slate-700'}">${percent}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="${barColor} h-3 rounded-full transition-all duration-500" style="width: ${percent}%"></div>
                    </div>
                    <div class="flex justify-between text-xs pt-1 border-t border-slate-100">
                        <span class="text-slate-500">Terpakai: <strong class="text-slate-700">${formatRupiah(spent)}</strong></span>
                        <span class="text-slate-500">Sisa: <strong class="${remaining < 0 ? 'text-rose-600' : 'text-emerald-600'}">${formatRupiah(remaining)}</strong></span>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function renderInvestments() {
            const tbody = document.getElementById('invest-table-body');
            tbody.innerHTML = '';
            let totalModal = 0, totalNilai = 0;

            if (state.investments.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-400">Belum ada aset investasi.</td></tr>`;
            } else {
                state.investments.forEach(inv => {
                    totalModal += inv.modal;
                    totalNilai += inv.currentValue;
                    const diff = inv.currentValue - inv.modal;
                    const isProfit = diff >= 0;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="p-4 text-xs text-slate-500">${inv.date}</td>
                        <td class="p-4 font-bold text-slate-800">${inv.product}</td>
                        <td class="p-4 text-slate-600">${formatRupiah(inv.modal)}</td>
                        <td class="p-4 font-semibold text-slate-800">${formatRupiah(inv.currentValue)}</td>
                        <td class="p-4 font-bold ${isProfit ? 'text-emerald-600' : 'text-rose-600'}">${isProfit ? '+' : ''}${formatRupiah(diff)}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            const totalProfit = totalNilai - totalModal;
            document.getElementById('inv-total-modal').innerText = formatRupiah(totalModal);
            document.getElementById('inv-total-nilai').innerText = formatRupiah(totalNilai);
            document.getElementById('inv-total-profit').innerText = (totalProfit >= 0 ? '+' : '') + formatRupiah(totalProfit);
            document.getElementById('inv-total-profit').className = `text-xl font-bold ${totalProfit >= 0 ? 'text-emerald-600' : 'text-rose-600'}`;
        }

        function renderReports() {
            const filterMonth = getSelectedMonthFilter();
            const monthIncome = state.transactions.filter(t => t.type === 'Pemasukan' && t.date.startsWith(filterMonth)).reduce((acc, t) => acc + t.amount, 0);
            const monthExpense = state.transactions.filter(t => t.type === 'Pengeluaran' && t.date.startsWith(filterMonth)).reduce((acc, t) => acc + t.amount, 0);

            document.getElementById('rep-pemasukan').innerText = formatRupiah(monthIncome);
            document.getElementById('rep-pengeluaran').innerText = formatRupiah(monthExpense);
            document.getElementById('rep-tabungan').innerText = formatRupiah(state.savings.reduce((acc, s) => acc + s.current, 0));
            document.getElementById('rep-investasi').innerText = formatRupiah(state.investments.reduce((acc, i) => acc + i.currentValue, 0));
            document.getElementById('rep-sisa').innerText = formatRupiah(monthIncome - monthExpense);
        }

        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        window.onload = function() {
            initData();
            switchTab('dashboard');
        };
    </script>
</body>
</html>