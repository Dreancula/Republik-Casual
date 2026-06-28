<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: true }" :class="{ 'dark': darkMode }">

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

                        <a href="#"
                           class="glass px-5 py-2 rounded-full">
                            🛒
                        </a>

                        <a href="#"
                           class="glass px-6 py-2 rounded-full">
                            Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </header>

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

</body>
</html>