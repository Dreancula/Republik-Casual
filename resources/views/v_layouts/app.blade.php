<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: true, searchOpen: false, searchQuery: '', searchResults: [] }" :class="{ 'dark': darkMode }" @keydown.cmd.k.window="searchOpen = true; $nextTick(() => $el.querySelector('#search-input')?.focus())" @keydown.ctrl.k.window="searchOpen = true; $nextTick(() => $el.querySelector('#search-input')?.focus())">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Republik Casual')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *{
            font-family:'Inter',sans-serif;
        }

        body{
            transition:.3s;
        }

        .glass{
            backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
            background:rgba(255,255,255,.05);
            border:1px solid rgba(255,255,255,.08);
        }

        .grid-bg{
            background:
            linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size:40px 40px;
        }

        [x-cloak] { display: none !important; }

        .chat-scroll::-webkit-scrollbar,
        .search-results-scroll::-webkit-scrollbar { width: 3px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 10px; }
        .search-results-scroll::-webkit-scrollbar-thumb { background: #333; }
    </style>
</head>

<body class="bg-black text-white grid-bg min-h-screen">

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-6 pt-6">

            <div class="glass rounded-full px-8 py-5">

                <div class="flex items-center justify-between">

                    {{-- LOGO --}}
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-4">

                        <div class="text-3xl font-black">
                            RC
                        </div>

                        <div>
                            <div class="font-black tracking-[4px]">
                                REPUBLIK CASUAL
                            </div>

                            <div class="text-xs text-neutral-400">
                                Premium Fashion
                            </div>
                        </div>

                    </a>

                    {{-- MENU --}}
                    <nav class="hidden lg:flex items-center gap-10">

                        <a href="{{ route('home') }}"
                           class="hover:text-cyan-400 transition">
                            Home
                        </a>

                        <a href="{{ route('produk.index') }}"
                           class="hover:text-cyan-400 transition">
                            Products
                        </a>

                        <a href="#"
                           class="hover:text-cyan-400 transition">
                            News
                        </a>

                    </nav>

                    {{-- ACTION --}}
                    <div class="flex items-center gap-3">

                        <button
                            @click="darkMode=!darkMode"
                            class="glass rounded-full h-11 w-11 flex items-center justify-center">

                            <span x-show="darkMode">☀️</span>
                            <span x-show="!darkMode">🌙</span>

                        </button>

                        <button
                            @click="searchOpen = true"
                            class="glass rounded-full h-11 w-11 flex items-center justify-center hover:text-cyan-400 transition">
                            ⌕
                        </button>

                        <a href="{{ route('keranjang.index') }}"
                           class="glass px-5 py-2 rounded-full">
                            🛒
                        </a>

                        <a href="{{ url('/login') }}"
                           class="glass px-6 py-2 rounded-full">
                            Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </header>

    {{-- SEARCH OVERLAY --}}
    <div x-cloak x-show="searchOpen" class="fixed inset-0 z-[60]">
        <div class="absolute inset-0 bg-black/80" @click="searchOpen = false"></div>
        <div class="relative max-w-2xl mx-auto mt-24 px-4">
            <div class="bg-black border border-[#1a1a1a] p-6">
                <div class="flex items-center gap-4">
                    <input
                        x-model="searchQuery"
                        @input.debounce.300ms="fetch('/search?q=' + encodeURIComponent(searchQuery)).then(r => r.json()).then(d => searchResults = d)"
                        id="search-input"
                        @keydown.escape.window="searchOpen = false"
                        type="text"
                        placeholder="Cari produk..."
                        class="flex-1 bg-[#141414] border-none px-4 py-3 text-sm text-white outline-none placeholder-[#555] font-mono">
                    <button @click="searchOpen = false" class="text-[#666] hover:text-white text-sm">✕</button>
                </div>

                <div class="mt-4 border-t border-[#1a1a1a] pt-4">
                    <template x-if="searchQuery.length > 0 && searchResults.length === 0">
                        <p class="text-[#555] text-xs font-mono py-8 text-center">No products found</p>
                    </template>
                    <template x-if="searchResults.length > 0">
                        <div class="space-y-2 max-h-[360px] overflow-y-auto search-results-scroll">
                            <template x-for="item in searchResults" :key="item.id">
                                <a :href="item.url" class="flex items-center justify-between px-4 py-3 bg-[#0a0a0a] hover:bg-[#141414] transition group">
                                    <div>
                                        <p class="text-white text-xs font-semibold" x-text="item.nama"></p>
                                        <p class="text-[#555] text-[10px] mt-0.5" x-text="item.kategori"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[#FF0055] text-xs font-bold" x-text="item.harga"></p>
                                        <p class="text-[#555] text-[10px]" x-text="'Stok: ' + item.stok"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>
                    <template x-if="searchQuery.length === 0">
                        <p class="text-[#555] text-xs font-mono py-8 text-center">Ketik nama produk untuk mencari</p>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <main>

        @yield('content')

    </main>

    {{-- FOOTER --}}
    <footer class="mt-32 border-t border-white/10">

        <div class="max-w-7xl mx-auto px-6 py-12">

            <div class="grid md:grid-cols-3 gap-10">

                <div>

                    <h3 class="font-black text-xl">
                        Republik Casual
                    </h3>

                    <p class="mt-4 text-neutral-400">
                        Modern streetwear fashion platform.
                    </p>

                </div>

                <div>

                    <h3 class="font-semibold mb-4">
                        Menu
                    </h3>

                    <div class="space-y-2 text-neutral-400">

                        <div>
                            <a href="{{ route('home') }}">
                                Home
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('produk.index') }}">
                                Produk
                            </a>
                        </div>

                    </div>

                </div>

                <div>

                    <h3 class="font-semibold mb-4">
                        Contact
                    </h3>

                    <p class="text-neutral-400">
                        support@republikcasual.com
                    </p>

                </div>

            </div>

            <div class="border-t border-white/10 mt-10 pt-6 text-center text-neutral-500">

                © {{ date('Y') }} Republik Casual

            </div>

        </div>

    </footer>

    @include('components.chat-widget')

    <script>
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
                        if (this.$data.csOpen) this.loadChats();
                    }, 5000);
                },

                loadChats() {
                    fetch('/api/chats')
                        .then(res => {
                            if (res.status === 401) {
                                this.chats = [{
                                    id_chat: 0,
                                    is_admin: true,
                                    teks: 'Silakan login terlebih dahulu untuk memulai chat.',
                                    tgl_chat: new Date(),
                                    kategori_chat: 'Sistem'
                                }];
                                throw new Error('Unauthorized');
                            }
                            return res.json();
                        })
                        .then(data => { if (data) this.chats = data; })
                        .catch(err => console.log('Chat:', err.message));
                },

                kirimPesan() {
                    if (this.pesanTeks.trim() === '' && !this.fotoInput) return;

                    let formData = new FormData();
                    formData.append('kategori_chat', this.kategoriChat);
                    formData.append('teks', this.pesanTeks);

                    const fileInput = document.querySelector('input[name="foto_chat_input"]');
                    if (fileInput && fileInput.files[0]) formData.append('foto_chat', fileInput.files[0]);

                    fetch('/api/chats', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.chats.push(data.chat);
                            if (data.bot_reply) this.chats.push(data.bot_reply);
                            this.pesanTeks = '';
                            this.fotoInput = null;
                            if (fileInput) fileInput.value = '';
                            this.scrollBottom();
                        }
                    })
                    .catch(err => console.error('Gagal:', err));
                },

                handleFileChange(event) {
                    const file = event.target.files[0];
                    if (file) this.fotoInput = URL.createObjectURL(file);
                },

                formatTime(timeString) {
                    if (!timeString) return '--:--';
                    return new Date(timeString).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                },

                scrollBottom() {
                    this.$nextTick(() => {
                        const c = this.$refs.chatContainer;
                        if (c) c.scrollTop = c.scrollHeight;
                    });
                }
            }
        }
    </script>

</body>
</html>