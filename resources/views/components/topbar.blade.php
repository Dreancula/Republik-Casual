
<div id="topbar"
     class="sticky top-0 z-40
            bg-[#f4f4f4]/70 dark:bg-[#050505]/70
            backdrop-blur-2xl
            border-b border-transparent
            px-6 md:px-10
            py-6
            mb-8
            flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5
            transition-all duration-300">
    <!-- LEFT -->
    <div>

        <h2 id="topbarTitle"
    class="text-4xl font-bold text-[#111111] dark:text-white transition-all duration-300">

            {{ $title ?? 'Dashboard' }}

        </h2>

        <p class="mt-3 text-black/50 dark:text-white/40">

            {{ $subtitle ?? 'Republik Casual Admin Panel' }}

        </p>

    </div>

    <!-- RIGHT -->
    <div class="flex items-center gap-3">

        <!-- SEARCH -->
        <div class="hidden md:flex items-center gap-3 px-5 py-3 rounded-2xl bg-white dark:bg-[#0d0d0d] border border-black/5 dark:border-white/10 shadow-sm">

            <span>🔍</span>

            <input type="text"
                   placeholder="{{ $search ?? 'Search...' }}"
                   class="bg-transparent outline-none text-sm w-56 text-[#111111] dark:text-white placeholder:text-black/30 dark:placeholder:text-white/30">

        </div>

        <!-- PROFILE DROPDOWN -->
<div class="relative">

    <button id="profileButton"
            type="button"
            class="flex items-center gap-3 px-3 py-2 rounded-2xl bg-white dark:bg-[#0d0d0d] border border-black/5 dark:border-white/10 shadow-sm hover:scale-[1.02] transition-all duration-300">

        <img src="https://i.pravatar.cc/100"
             class="w-11 h-11 rounded-xl object-cover">

        <div class="hidden md:block text-left leading-tight">

            <h4 class="font-semibold text-sm text-[#111111] dark:text-white">
                {{ Auth::user()->name ?? 'Admin' }}
            </h4>

            <p class="text-xs text-black/40 dark:text-white/40">
                Administrator
            </p>

        </div>

        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-4 h-4 text-black/40 dark:text-white/40"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"/>

        </svg>

    </button>

    <!-- DROPDOWN -->
    <div id="profileDropdown"
         class="absolute right-0 top-[72px] w-[240px] p-2 rounded-3xl bg-white dark:bg-[#0d0d0d] border border-black/5 dark:border-white/10 shadow-2xl opacity-0 invisible translate-y-3 transition-all duration-300 z-50">

        <!-- USER -->
        <div class="px-4 py-3 border-b border-black/5 dark:border-white/10">

            <h4 class="font-semibold text-[#111111] dark:text-white">
                {{ Auth::user()->name ?? 'Admin' }}
            </h4>

            <p class="text-sm text-black/40 dark:text-white/40">
                {{ Auth::user()->email ?? 'admin@email.com' }}
            </p>

        </div>

        <!-- MENU -->
        <div class="py-2">

            <a href="{{ route('settings') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-black/60 dark:text-white/60 hover:bg-black/5 dark:hover:bg-white/5 transition-all duration-300">

                ⚙️ Settings

            </a>

            <form action="{{ route('logout') }}" method="POST">

                @csrf

                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-red-500 hover:bg-red-500/10 transition-all duration-300">

                    🚪 Logout

                </button>

            </form>

        </div>

    </div>

</div>

        <!-- THEME BUTTON -->
        <button id="themeToggle"
                type="button"
                class="group relative w-14 h-14 rounded-2xl bg-white dark:bg-[#101010] border border-black/5 dark:border-white/10 shadow-lg flex items-center justify-center hover:scale-105 active:scale-95 overflow-hidden transition-all duration-300">

            <!-- SUN -->
            <svg id="sunIcon"
                 xmlns="http://www.w3.org/2000/svg"
                 class="absolute w-6 h-6 text-yellow-400 scale-0 rotate-90 opacity-0 transition-all duration-500"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 3v1m0 16v1m8-9h1M3 12H2m15.364 6.364l.707.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>

            </svg>

            <!-- MOON -->
            <svg id="moonIcon"
                 xmlns="http://www.w3.org/2000/svg"
                 class="absolute w-6 h-6 text-[#111111] dark:text-white scale-100 rotate-0 opacity-100 transition-all duration-500"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646A9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>

            </svg>

        </button>

        @if(isset($button))

            <!-- ACTION BUTTON -->
            <button
                class="px-5 py-3 rounded-2xl
                       bg-[#111111] dark:bg-white
                       text-white dark:text-black
                       font-medium
                       hover:scale-105
                       active:scale-95
                       transition-all duration-300">

                {{ $button }}

            </button>

        @endif

    </div>

</div>

<script>

    // DARK MODE SYSTEM

    const html = document.documentElement;

    const themeToggle = document.getElementById('themeToggle');

    const sunIcon = document.getElementById('sunIcon');

    const moonIcon = document.getElementById('moonIcon');

    function applyTheme(isDark){

        html.classList.toggle('dark', isDark);

        if(isDark){

            sunIcon.classList.remove('scale-0', 'rotate-90', 'opacity-0');
            sunIcon.classList.add('scale-100', 'rotate-0', 'opacity-100');

            moonIcon.classList.remove('scale-100', 'rotate-0', 'opacity-100');
            moonIcon.classList.add('scale-0', '-rotate-90', 'opacity-0');

        }else{

            sunIcon.classList.remove('scale-100', 'rotate-0', 'opacity-100');
            sunIcon.classList.add('scale-0', 'rotate-90', 'opacity-0');

            moonIcon.classList.remove('scale-0', '-rotate-90', 'opacity-0');
            moonIcon.classList.add('scale-100', 'rotate-0', 'opacity-100');

        }

        localStorage.setItem('theme', isDark ? 'dark' : 'light');

    }

    const savedTheme = localStorage.getItem('theme');

    const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    applyTheme(savedTheme === 'dark' || (!savedTheme && systemDark));

    themeToggle.addEventListener('click', () => {

        applyTheme(!html.classList.contains('dark'));

    });

</script>

<script>

    // PROFILE DROPDOWN

    const profileButton =
        document.getElementById('profileButton');

    const profileDropdown =
        document.getElementById('profileDropdown');

    profileButton?.addEventListener('click', () => {

        profileDropdown.classList.toggle('opacity-0');
        profileDropdown.classList.toggle('invisible');
        profileDropdown.classList.toggle('translate-y-3');

    });

    window.addEventListener('click', (e) => {

        if(
            !profileButton.contains(e.target) &&
            !profileDropdown.contains(e.target)
        ){

            profileDropdown.classList.add('opacity-0');
            profileDropdown.classList.add('invisible');
            profileDropdown.classList.add('translate-y-3');

        }

    });

</script>

<script>

    // TOPBAR SCROLL EFFECT

    const topbar =
        document.getElementById('topbar');

    const topbarTitle =
        document.getElementById('topbarTitle');

    const mainContent =
        document.querySelector('main');

    mainContent?.addEventListener('scroll', () => {

        if(mainContent.scrollTop > 40){

            topbar.classList.add(
                'py-4',
                'shadow-2xl',
                'border-black/5',
                'dark:border-white/10'
            );

            topbar.classList.remove(
                'py-6'
            );

            topbarTitle.classList.remove(
                'text-4xl'
            );

            topbarTitle.classList.add(
                'text-3xl'
            );

        }else{

            topbar.classList.remove(
                'py-4',
                'shadow-2xl',
                'border-black/5',
                'dark:border-white/10'
            );

            topbar.classList.add(
                'py-6'
            );

            topbarTitle.classList.remove(
                'text-3xl'
            );

            topbarTitle.classList.add(
                'text-4xl'
            );

        }

    });

</script>