<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISPUS | Perpustakaan Digital SMAN 3 Padang</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        dark: '#051024', /* Deep Navy Blue / Biru Sangat Gelap */
                        surface: '#0d2247', /* Biru Surface untuk Card */
                        primary: '#2563eb', /* Blue 600 */
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        /* Efek kaca dengan tint putih dan biru */
        .glass-panel { 
            background: rgba(255, 255, 255, 0.03); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.08); 
        }
    </style>
</head>

<body class="bg-dark text-slate-300 font-sans antialiased overflow-x-hidden selection:bg-blue-500 selection:text-white">

    <!-- Background Glow Effects (Dominan Biru) -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600 rounded-full mix-blend-screen filter blur-[128px] opacity-20 z-0"></div>
    <div class="absolute top-40 right-1/4 w-96 h-96 bg-cyan-400 rounded-full mix-blend-screen filter blur-[128px] opacity-10 z-0"></div>

    <!-- Floating Capsule Navbar -->
    <nav class="fixed top-6 left-1/2 -translate-x-1/2 w-[90%] max-w-5xl glass-panel rounded-full shadow-2xl z-50 transition-all duration-300">
        <div class="px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#" class="flex items-center gap-3 group">
                <div class="bg-gradient-to-tr from-blue-600 to-cyan-400 text-white p-2 rounded-xl group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="text-xl font-extrabold text-white tracking-wide">SISPUS.</span>
            </a>

            <!-- Menu Links -->
            <div class="hidden md:flex space-x-8 text-sm font-semibold text-slate-300">
                <a href="#beranda" class="hover:text-white transition-colors">Beranda</a>
                <a href="#fitur" class="hover:text-white transition-colors">Fitur Sistem</a>
                <a href="#tentang" class="hover:text-white transition-colors">Tentang</a>
            </div>

            <!-- Login CTA (Sesuai Route Bawaan) -->
            <a href="{{ route('web.login') }}" class="relative inline-flex h-10 overflow-hidden rounded-full p-[2px] focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-dark shadow-[0_0_15px_rgba(37,99,235,0.3)]">
                <span class="absolute inset-[-1000%] animate-[spin_2s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,#E2CBFF_0%,#2563EB_50%,#E2CBFF_100%)]"></span>
                <span class="inline-flex h-full w-full cursor-pointer items-center justify-center rounded-full bg-dark px-6 text-sm font-semibold text-white backdrop-blur-3xl hover:bg-surface transition-colors">
                    Masuk Portal
                </span>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-48 pb-20 lg:pt-56 lg:pb-32 px-6 z-10 flex flex-col items-center text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel text-cyan-400 text-xs font-bold uppercase tracking-widest mb-8 border border-cyan-500/20 shadow-sm shadow-cyan-900/50">
            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
            Sistem Informasi Perpustakaan
        </div>

        <h1 class="text-5xl md:text-7xl lg:text-7xl font-extrabold tracking-tight text-white mb-8">
            Eksplorasi Dunia Literasi <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-blue-300 to-white leading-tight">
                Dalam Satu Genggaman.
            </span>
        </h1>

        <p class="text-lg md:text-xl text-blue-100/70 max-w-2xl font-light mb-12 leading-relaxed">
            Akses ribuan koleksi buku digital, pantau riwayat peminjaman, dan nikmati kemudahan manajemen perpustakaan SMAN 3 Padang secara modern, kapan saja dan di mana saja.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 w-full justify-center max-w-md">
            <!-- Tombol Login (Sesuai Route Bawaan) -->
            <a href="{{ route('web.login') }}" class="bg-white text-blue-900 font-bold px-8 py-4 rounded-xl hover:scale-105 hover:shadow-[0_0_40px_-10px_rgba(255,255,255,0.4)] transition-all duration-300 flex items-center justify-center gap-2">
                Mulai Eksplorasi 
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <!-- Abstract Tech Visual / UI Mockup -->
        <div class="mt-20 w-full max-w-5xl relative animate-float">
            <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-transparent z-10 h-full w-full"></div>
            <img src="https://images.unsplash.com/photo-1568667256549-094345857637?q=80&w=2564&auto=format&fit=crop" 
                 class="rounded-3xl border border-white/10 shadow-[0_0_80px_rgba(37,99,235,0.15)] object-cover h-[400px] w-full object-center opacity-70 mix-blend-luminosity" alt="Visual Modern Perpustakaan">
        </div>
    </section>

    <!-- Features (Bento Box Style) - Tema Edukasi & Sistem -->
    <section id="fitur" class="py-24 relative z-10 bg-dark">
        <div class="max-w-6xl mx-auto px-6">
            <div class="mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white">Ekosistem <span class="text-blue-400">Terintegrasi.</span></h2>
                <p class="mt-4 text-blue-100/60 max-w-xl">Mengubah cara tradisional pengelolaan perpustakaan menjadi serba digital, cepat, dan transparan bagi siswa maupun petugas.</p>
            </div>

            <!-- Bento Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Bento 1: Large Box -->
                <div class="md:col-span-2 glass-panel rounded-3xl p-8 overflow-hidden relative group hover:bg-white/[0.05] transition-colors">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-transparent z-0"></div>
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center mb-6 border border-blue-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-3">Katalog Real-time & Akurat</h3>
                            <p class="text-blue-100/70 max-w-md">Cari buku favoritmu, cek status ketersediaan secara langsung, dan ketahui lokasi rak buku tanpa harus bertanya pada petugas.</p>
                        </div>
                        <div class="mt-10 bg-dark/50 p-4 rounded-2xl border border-white/5 flex items-center gap-3 w-full max-w-sm group-hover:border-blue-500/50 transition-colors">
                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span class="text-slate-500 text-sm">Cari judul, pengarang, atau ISBN...</span>
                        </div>
                    </div>
                </div>

                <!-- Bento 2: Tall Box (Diganti jadi Riwayat/History) -->
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group hover:bg-white/[0.05] transition-colors">
                    <div class="absolute inset-0 bg-gradient-to-tr from-cyan-600/10 to-transparent z-0"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center mb-6 border border-cyan-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Riwayat Peminjaman</h3>
                        <p class="text-blue-100/70 text-sm mb-8">Pantau status peminjaman, tanggal pengembalian, dan histori aktivitas literatmu dengan mudah.</p>
                        
                        <div class="flex flex-col gap-3">
                            <div class="bg-dark/50 p-3 rounded-xl border border-white/5 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-300 font-medium">Bumi Manusia</p>
                                    <p class="text-[10px] text-slate-500">Dikembalikan</p>
                                </div>
                            </div>
                            <div class="bg-dark/50 p-3 rounded-xl border border-white/5 flex items-center gap-3 border-l-2 border-l-amber-500">
                                <div class="w-8 h-8 rounded-full bg-amber-500/20 flex items-center justify-center text-amber-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-300 font-medium">Filosofi Teras</p>
                                    <p class="text-[10px] text-amber-500">Sisa 2 Hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bento 3: Wide Box -->
                <div class="md:col-span-3 glass-panel rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-8 border-t border-blue-500/20 hover:bg-white/[0.03] transition-colors">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-blue-500/30 shrink-0">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white mb-2">Manajemen via Smartphone 📱</h3>
                            <p class="text-blue-100/70 max-w-xl">Scan kartu anggota, lakukan peminjaman, dan akses fitur perpustakaan dengan lancar langsung dari perangkat pintar Anda.</p>
                        </div>
                    </div>
                    <!-- Tombol Login (Sesuai Route Bawaan) -->
                    <a href="{{ route('web.login') }}" class="whitespace-nowrap px-8 py-3 rounded-xl bg-white text-blue-900 font-bold hover:shadow-[0_0_20px_rgba(255,255,255,0.2)] transition-all">
                        Masuk Sistem
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="tentang" class="border-t border-white/5 bg-[#030914] pt-16 pb-8 relative z-10 mt-10">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                <div>
                    <span class="text-2xl font-extrabold text-white tracking-wide">SISPUS.</span>
                    <p class="text-blue-100/50 mt-2 max-w-md">
                        Sistem Informasi Perpustakaan Digital SMA Negeri 3 Padang. Didesain untuk memberikan pengalaman manajemen literasi yang efisien, transparan, dan modern.
                    </p>
                </div>
            </div>
            <div class="text-center text-blue-100/40 text-sm border-t border-white/5 pt-8">
                &copy; {{ date('Y') }} SISPUS SMAN 3 Padang. Dikembangkan untuk Keperluan Akademik/Penelitian.
            </div>
        </div>
    </footer>

</body>
</html>