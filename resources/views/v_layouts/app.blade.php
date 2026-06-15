<!DOCTYPE html>
<html lang="id" x-data="themeApp()" x-init="init()" :class="{ 'dark': darkMode }" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Republik Casual')</title>

    <script>
        window.tailwind = {
            config: {
                darkMode: 'class'
            }
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background:
                radial-gradient(circle at top, rgba(0, 0, 0, .04), transparent 28%),
                linear-gradient(to bottom, #ffffff 0%, #fafafa 100%);
            color: #111111;
            transition: background 300ms ease, color 300ms ease;
        }

        .dark body {
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, .06), transparent 28%),
                linear-gradient(to bottom, #09090b 0%, #111113 100%);
            color: #f4f4f5;
            transition: background 300ms ease, color 300ms ease;
        }

        .glass {
            background: rgba(255, 255, 255, .68);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .7);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .06), 0 2px 10px rgba(0, 0, 0, .04);
        }

        .dark .glass {
            background: rgba(24, 24, 27, .64);
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .34), 0 2px 10px rgba(0, 0, 0, .2);
        }

        .soft-card {
            background: rgba(255, 255, 255, .78);
            border: 1px solid rgba(0, 0, 0, .06);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
        }

        .dark .soft-card {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .28);
        }

        .premium-input {
            background: rgba(255, 255, 255, .82);
            border: 1px solid rgba(0, 0, 0, .08);
            transition: all 300ms ease;
        }

        .dark .premium-input {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .premium-input:focus {
            outline: none;
            border-color: rgba(0, 0, 0, .22);
            box-shadow: 0 0 0 4px rgba(0, 0, 0, .04);
        }

        .dark .premium-input:focus {
            border-color: rgba(255, 255, 255, .2);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, .04);
        }

        .card-hover {
            transition: transform 300ms ease, box-shadow 300ms ease, border-color 300ms ease, opacity 300ms ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
        }

        .noise {
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .03;
            background-image:
                linear-gradient(rgba(0, 0, 0, .18) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, .18) 1px, transparent 1px);
            background-size: 38px 38px;
            mix-blend-mode: multiply;
        }

        .dark .noise {
            opacity: .04;
            background-image:
                linear-gradient(rgba(255, 255, 255, .18) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .18) 1px, transparent 1px);
            mix-blend-mode: screen;
        }

        .dock-btn {
            transition: transform 300ms ease, background 300ms ease, color 300ms ease;
        }

        .dock-btn:hover {
            transform: translateY(-2px);
        }

        .chat-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .dark .chat-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen antialiased transition-colors duration-300">

    <div class="noise"></div>

    <script>
        function themeApp() {
            return {
                darkMode: false,
                searchOpen: false,
                mobileMenu: false,
                init() {
                    const saved = localStorage.getItem('theme');
                    if (saved) {
                        this.darkMode = saved === 'dark';
                    } else {
                        this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                }
            }
        }

        function internalChatSystem() {
            return {
                csOpen: false,
                kategoriChat: 'Umum',
                pesanTeks: '',
                fotoInput: null,
                chats: [],

                init() {
                    this.loadChats();

                    setInterval(() => {
                        if (this.$data.csOpen) {
                            this.loadChats();
                        }
                    }, 5000);
                },

                loadChats() {
                    fetch('/api/chats')
                        .then(res => {
                            if (res.status === 401) {
                                this.chats = [{
                                    id_chat: 0,
                                    is_admin: true,
                                    teks: 'Silakan login terlebih dahulu untuk memulai chat dengan support kami.',
                                    tgl_chat: new Date(),
                                    kategori_chat: 'Sistem'
                                }];
                                throw new Error('Unauthorized');
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (data) {
                                this.chats = data;
                            }
                        })
                        .catch(err => console.log('Sistem Chat:', err.message));
                },

                kirimPesan() {
                    if (this.pesanTeks.trim() === '' && !this.fotoInput) return;

                    let formData = new FormData();
                    formData.append('kategori_chat', this.kategoriChat);
                    formData.append('teks', this.pesanTeks);

                    const fileInput = document.querySelector('input[name="foto_chat_input"]');
                    if (fileInput && fileInput.files[0]) {
                        formData.append('foto_chat', fileInput.files[0]);
                    }

                    fetch('/api/chats', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.chats.push(data.chat);
                                this.pesanTeks = '';
                                this.fotoInput = null;
                                if (fileInput) fileInput.value = '';
                                this.scrollBottom();
                            }
                        })
                        .catch(err => console.error('Gagal mengirim chat:', err));
                },

                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.fotoInput = URL.createObjectURL(file);
                    }
                },

                formatTime(timeString) {
                    if (!timeString) return '--:--';
                    const date = new Date(timeString);
                    return date.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                scrollBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.chatContainer;
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                }
            }
        }
    </script>

    {{-- TOP FLOATING NAV --}}
    <header class="fixed top-0 left-0 right-0 z-50 px-4 sm:px-6 pt-4" x-data="{ prodDropdown: false }">
        <div class="max-w-7xl mx-auto">
            <nav class="glass rounded-[28px] px-5 sm:px-7 py-4">
                <div class="flex items-center justify-between gap-4">

                    <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0">
                        <div
                            class="w-11 h-11 rounded-2xl bg-black dark:bg-white text-white dark:text-black flex items-center justify-center font-black tracking-tight">
                            RC
                        </div>
                        <div class="leading-tight hidden sm:block">
                            <div class="font-bold text-sm tracking-[0.22em] uppercase">Republik Casual</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-300">Premium Fashion</div>
                        </div>
                    </a>

                    {{-- DESKTOP MENU --}}
                    <div class="hidden lg:flex items-center gap-8 text-sm text-neutral-600 dark:text-neutral-300">
                        <a href="{{ url('/home') }}"
                            class="hover:text-black dark:hover:text-white transition font-medium">Home</a>
                        <a href="{{ route('produk.all') }}"
                            class="hover:text-black dark:hover:text-white transition font-medium">Products</a>

                        <a href="{{ url('/news') }}"
                            class="hover:text-black dark:hover:text-white transition flex items-center gap-1.5 font-medium">
                            News
                            <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-pulse"
                                title="Open Batch Info"></span>
                        </a>
                    </div>

                    {{-- ICONS & LOGIN BTN --}}
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button @click="searchOpen = true"
                            class="w-10 h-10 rounded-2xl glass flex items-center justify-center hover:scale-105 transition duration-300"
                            aria-label="Search">
                            ⌕
                        </button>

                        <button @click="toggleTheme()"
                            class="w-10 h-10 rounded-2xl glass flex items-center justify-center hover:scale-105 transition duration-300"
                            aria-label="Toggle Theme">
                            <span x-show="!darkMode">🌙</span>
                            <span x-show="darkMode" x-cloak>☀️</span>
                        </button>

                        <a href="{{ url('/cart') }}"
                            class="w-10 h-10 rounded-2xl bg-black text-white dark:bg-white dark:text-black flex items-center justify-center hover:scale-105 transition duration-300"
                            aria-label="Cart">
                            🛒
                        </a>

                        {{-- AUTH BUTTON (Desktop Only) --}}
                        <div class="hidden sm:block pl-2 ml-2 border-l border-neutral-200 dark:border-neutral-800">
                            @auth
                                {{-- Jika sudah login, tombol menuju halaman akun/profile --}}
                                <a href="{{ url('/profile') }}"
                                    class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-neutral-100 dark:bg-neutral-800 text-sm font-semibold hover:bg-neutral-200 dark:hover:bg-neutral-700 transition duration-300">
                                    <span>👤</span> {{ Auth::user()->name ?? 'Profile' }}
                                </a>
                            @else
                                {{-- Jika belum login, tombol menuju form login --}}
                                <a href="{{ url('login') }}"
                                    class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-black text-white dark:bg-white dark:text-black text-sm font-semibold hover:scale-105 transition duration-300 shadow-lg">
                                    Sign In
                                </a>
                            @endauth
                        </div>

                        <button @click="mobileMenu = !mobileMenu"
                            class="lg:hidden w-10 h-10 rounded-2xl glass flex items-center justify-center"
                            aria-label="Menu">
                            ☰
                        </button>
                    </div>
                </div>

                {{-- MOBILE NAVIGATION LINKS --}}
                <div x-cloak x-show="mobileMenu" x-transition
                    class="lg:hidden mt-5 pt-5 border-t border-neutral-200 dark:border-neutral-800">
                    <div class="grid gap-2 text-sm text-neutral-600 dark:text-neutral-300">
                        <a href="{{ url('/') }}" class="py-2 font-medium">Home</a>

                        <div class="py-1 border-l-2 border-neutral-200 dark:border-neutral-800 pl-3 grid gap-2 my-1">
                            <span
                                class="text-xs uppercase tracking-wider text-neutral-400 font-semibold">Categories</span>
                            <a href="{{ route('products.customer') }}" class="text-xs py-1">All Products</a>
                            <a href="{{ route('products.customer', ['category' => 't-shirt']) }}"
                                class="text-xs py-1">T-Shirt</a>
                            <a href="{{ route('products.customer', ['category' => 'crewneck']) }}"
                                class="text-xs py-1">Crewneck</a>
                            <a href="{{ route('products.customer', ['category' => 'pants']) }}"
                                class="text-xs py-1">Celana</a>
                        </div>

                        <a href="{{ url('/news') }}" class="py-2 font-medium flex items-center justify-between">
                            <span>News (Open Batch)</span>
                            <span
                                class="px-2 py-0.5 text-[10px] bg-red-500 text-white rounded-full font-bold uppercase tracking-wide">Live</span>
                        </a>

                        {{-- AUTH BUTTON (Mobile) --}}
                        <div class="mt-4 pt-4 border-t border-neutral-200 dark:border-neutral-800">
                            @auth
                                <a href="{{ url('/profile') }}"
                                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-neutral-100 dark:bg-neutral-800 font-semibold">
                                    <span>👤</span> Account Profile
                                </a>
                            @else
                                <a href="{{ url('/auth/login') }}"
                                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-black text-white dark:bg-white dark:text-black font-semibold">
                                    Sign In / Register
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    {{-- SEARCH OVERLAY --}}
    <div x-cloak x-show="searchOpen" class="fixed inset-0 z-[60]">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="searchOpen = false"></div>

        <div class="relative max-w-2xl mx-auto mt-24 px-4">
            <div class="glass rounded-[28px] p-6 sm:p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-neutral-600 dark:text-neutral-300">Search</p>
                        <h3 class="text-2xl font-bold mt-2">Find products quickly</h3>
                    </div>
                    <button @click="searchOpen = false" class="w-10 h-10 rounded-2xl glass">✕</button>
                </div>

                <div class="mt-6">
                    <input type="text" placeholder="Search collection..."
                        class="w-full premium-input rounded-2xl px-5 py-4 text-base">
                </div>

                <div class="grid sm:grid-cols-3 gap-3 mt-5 text-sm text-neutral-600 dark:text-neutral-300">
                    <a href="{{ route('products.customer', ['category' => 't-shirt']) }}"
                        class="glass rounded-2xl px-4 py-3">T-Shirt</a>
                    <a href="{{ route('products.customer', ['category' => 'crewneck']) }}"
                        class="glass rounded-2xl px-4 py-3">Crewneck</a>
                    <a href="{{ route('products.customer', ['category' => 'pants']) }}"
                        class="glass rounded-2xl px-4 py-3">Celana</a>
                </div>
            </div>
        </div>
    </div>

    <main class="pt-28">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="mt-24 border-t border-neutral-200 dark:border-neutral-800">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-10">
                <div>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-11 h-11 rounded-2xl bg-black dark:bg-white text-white dark:text-black flex items-center justify-center font-black">
                            RC
                        </div>
                        <div>
                            <p class="font-bold tracking-[0.2em] text-sm uppercase">Republik Casual</p>
                            <p class="text-xs text-neutral-500">Premium Fashion Label</p>
                        </div>
                    </div>
                    <p class="mt-5 text-neutral-500 leading-relaxed max-w-sm">
                        Fashion premium dengan visual minimal, tegas, dan timeless.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Shop</h4>
                    <div class="space-y-3 text-neutral-500">
                        <a href="{{ route('products.customer') }}"
                            class="block hover:text-black dark:hover:text-white">All Products</a>
                        <a href="{{ route('products.customer', ['category' => 't-shirt']) }}"
                            class="block hover:text-black dark:hover:text-white">T-Shirt</a>
                        <a href="{{ route('products.customer', ['category' => 'crewneck']) }}"
                            class="block hover:text-black dark:hover:text-white">Crewneck</a>
                        <a href="{{ route('products.customer', ['category' => 'pants']) }}"
                            class="block hover:text-black dark:hover:text-white">Celana</a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Company</h4>
                    <div class="space-y-3 text-neutral-500">
                        <a href="#" class="block hover:text-black dark:hover:text-white">About</a>
                        <a href="#" class="block hover:text-black dark:hover:text-white">Shipping</a>
                        <a href="#" class="block hover:text-black dark:hover:text-white">FAQ</a>
                        <a href="#" class="block hover:text-black dark:hover:text-white">Contact</a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Newsletter</h4>
                    <p class="text-neutral-500 mb-4">
                        Get early access to new drops.
                    </p>
                    <div class="flex gap-3">
                        <input type="email" placeholder="Email address"
                            class="flex-1 premium-input rounded-2xl px-4 py-3">
                        <button
                            class="px-5 py-3 rounded-2xl bg-black text-white dark:bg-white dark:text-black font-medium">
                            Join
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="mt-14 pt-6 border-t border-neutral-200 dark:border-neutral-800 flex flex-col sm:flex-row gap-4 items-center justify-between text-sm text-neutral-500">
                <p>© {{ date('Y') }} Republik Casual. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-black dark:hover:text-white">Instagram</a>
                    <a href="#" class="hover:text-black dark:hover:text-white">TikTok</a>
                    <a href="#" class="hover:text-black dark:hover:text-white">Pinterest</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- PANGGIL FILE CHAT YANG UDAH DIPISAH DI SINI --}}
    @include('components.chat-widget')

    {{-- MOBILE DOCK --}}
    <div class="lg:hidden fixed bottom-4 left-1/2 -translate-x-1/2 z-40 w-[92%]">
        <div class="glass rounded-[26px] px-3 py-3">
            <div class="grid grid-cols-5 gap-2 text-xs text-center font-medium">
                <a href="{{ url('/') }}" class="dock-btn rounded-2xl py-2">Home</a>
                <a href="{{ route('products.customer') }}" class="dock-btn rounded-2xl py-2">Shop</a>
                <a href="{{ url('/news') }}" class="dock-btn rounded-2xl py-2 text-red-500 dark:text-red-400">News</a>
                
                <button @click="$dispatch('toggle-chat')" class="dock-btn rounded-2xl py-2 text-black dark:text-white font-bold">Chat</button>
                
                <button @click="toggleTheme()" class="dock-btn rounded-2xl py-2">
                    <span x-show="!darkMode">Dark</span>
                    <span x-show="darkMode" x-cloak>Light</span>
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>