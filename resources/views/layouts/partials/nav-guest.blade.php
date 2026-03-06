<header class="bg-white/95 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 h-20 flex items-center sticky top-0 z-9999 shadow-sm transition-colors duration-300" 
        x-data="{ scrolled: false, mobileMenuOpen: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex justify-between items-center">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center group">
            <div class="bg-white p-0.5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 group-hover:shadow-md group-hover:scale-105 transition-all duration-300 flex items-center justify-center">
                <img src="{{ asset('images/logo-netriders.png') }}" alt="Cisco NetRiders Logo" 
                     class="h-14 md:h-12 w-auto block">
            </div>
            <div class="ml-4 border-l border-slate-200 dark:border-slate-700 pl-4 hidden md:block">
                <span class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-super-wide leading-none">Cisco Networking Academy</span>
                <span class="block text-sm font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-tighter">NetRiders Assessment</span>
            </div>
        </a>

        {{-- Desktop Navigation and CTA --}}
        <div class="flex items-center gap-6">
            <nav class="hidden lg:flex items-center gap-8 mr-8">
                <a href="{{ route('home') }}#about" class="text-xs font-black text-slate-500 dark:text-slate-400 hover:text-[#005073] dark:hover:text-[#00bceb] uppercase tracking-widest transition-colors">About</a>
                <a href="{{ route('home') }}#achievements" class="text-xs font-black text-slate-500 dark:text-slate-400 hover:text-[#005073] dark:hover:text-[#00bceb] uppercase tracking-widest transition-colors">Achievements</a>
                <a href="{{ route('home') }}#activities" class="text-xs font-black text-slate-500 dark:text-slate-400 hover:text-[#005073] dark:hover:text-[#00bceb] uppercase tracking-widest transition-colors">Activities</a>
            </nav>

            {{-- Theme Switcher --}}
            <button @click="$dispatch('theme-toggle')" 
                    class="hidden sm:flex h-11 w-11 rounded-xl bg-slate-50 dark:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 items-center justify-center hover:border-[#00bceb] dark:hover:border-[#00bceb] transition-all group">
                <svg x-show="theme === 'light'" class="w-5 h-5 text-amber-500 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                </svg>
                <svg x-show="theme === 'dark'" class="w-5 h-5 text-blue-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
            </button>

            <a href="{{ route('login') }}"
               class="hidden sm:flex bg-[#005073] hover:bg-[#003e5c] dark:bg-[#00bceb] dark:hover:bg-[#005073] text-white px-8 py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 shadow-xl shadow-blue-900/20 dark:shadow-blue-500/20 items-center gap-2 group transform hover:-translate-y-0.5">
                Portal Login
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l4-4m0 0l-4-4m4 4H9" />
                </svg>
            </a>

            {{-- Mobile Toggle --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-500 dark:text-slate-400 hover:text-[#005073] dark:hover:text-[#00bceb] transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-[-10px]"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-[-10px]"
         class="absolute top-20 left-0 w-full bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-xl lg:hidden z-50 overflow-hidden" 
         x-cloak>
        <div class="px-4 py-6 space-y-4">
            <a href="{{ route('home') }}#about" class="block text-xs font-black text-slate-600 dark:text-slate-300 hover:text-[#00bceb] uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">About</a>
            <a href="{{ route('home') }}#achievements" class="block text-xs font-black text-slate-600 dark:text-slate-300 hover:text-[#00bceb] uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Achievements</a>
            <a href="{{ route('home') }}#activities" class="block text-xs font-black text-slate-600 dark:text-slate-300 hover:text-[#00bceb] uppercase tracking-widest px-4 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Activities</a>
            
            <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex flex-col gap-4">
                {{-- Mobile Theme Switcher --}}
                <button @click="$dispatch('theme-toggle')" 
                        class="flex items-center justify-between w-full px-4 py-3 bg-slate-50 dark:bg-slate-700 rounded-xl text-xs font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest">
                    <span>Appearance</span>
                    <div class="flex items-center gap-2">
                        <span x-text="theme === 'light' ? 'Light' : 'Dark'"></span>
                        <svg x-show="theme === 'light'" class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                        </svg>
                        <svg x-show="theme === 'dark'" class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                        </svg>
                    </div>
                </button>

                <a href="{{ route('login') }}" class="block w-full text-center bg-[#005073] dark:bg-[#00bceb] text-white px-8 py-4 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">
                    Portal Login
                </a>
            </div>
        </div>
    </div>
</header>
