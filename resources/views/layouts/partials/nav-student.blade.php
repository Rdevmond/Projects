<header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-sm sticky top-0 z-50 transition-colors duration-300" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                {{-- Logo Section --}}
                <a href="{{ route('student.exams') }}" class="flex items-center group">
                    <div class="bg-white p-0.5 rounded-2xl shadow-sm border border-slate-200 dark:border-white/10 group-hover:shadow-md group-hover:scale-105 transition-all duration-300 flex items-center justify-center">
                        <img src="{{ asset('images/logo-netriders.png') }}" alt="Cisco NetRiders"
                             class="h-14 md:h-12 w-auto block">
                    </div>
                </a>

                {{-- Student Navigation --}}
                <nav class="hidden md:ml-12 md:flex space-x-1">
                    <a href="{{ route('student.exams') }}"
                       class="inline-flex items-center px-4 pt-1 border-b-[3px] h-20 text-[13px] font-black uppercase tracking-widest transition-all duration-200
                       {{ request()->routeIs('student.exams')
                            ? 'border-[#005073] text-[#005073] dark:border-[#00bceb] dark:text-[#00bceb]'
                            : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-[#00bceb] hover:border-[#00bceb]/30' }}">
                        My Exams
                    </a>
                </nav>
            </div>

            {{-- User Section (Desktop) --}}
            <div class="hidden md:flex items-center gap-4">
                {{-- Theme Switcher --}}
                <button @click="$dispatch('theme-toggle')" 
                        class="h-11 w-11 rounded-xl bg-slate-50 dark:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 flex items-center justify-center hover:border-[#00bceb] dark:hover:border-[#00bceb] transition-all group">
                    <svg x-show="theme === 'light'" class="w-5 h-5 text-amber-500 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="theme === 'dark'" class="w-5 h-5 text-blue-400 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
                
                <a href="{{ route('profile') }}" class="text-right border-r border-slate-200 dark:border-slate-600 pr-4 hover:opacity-80 transition-opacity">
                    <p class="text-[10px] font-black text-[#00bceb] uppercase tracking-[0.15em] leading-none mb-1.5">Student</p>
                    <p class="text-sm text-[#005073] dark:text-white font-extrabold leading-none">{{ Auth::user()->name }}</p>
                </a>

                {{-- Student Avatar --}}
                <a href="{{ route('profile') }}" class="h-11 w-11 rounded-xl bg-[#eefbff] dark:bg-slate-700 border-2 border-[#00bceb]/20 dark:border-[#00bceb]/40 flex items-center justify-center text-[#005073] dark:text-[#00bceb] font-black text-sm shadow-sm hover:border-[#00bceb]/50 transition-all group">
                    <span class="group-hover:scale-110 transition-transform">{{ substr(Auth::user()->name, 0, 2) }}</span>
                </a>

                {{-- Logout Button --}}
                <form action="{{ route('logout') }}" method="POST" class="ml-2">
                    @csrf
                    <button type="submit" class="group flex items-center gap-2 text-[11px] font-black text-slate-500 dark:text-slate-400 hover:text-[#E2231A] transition-colors uppercase tracking-widest">
                        <span>Logout</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Mobile Button & Actions --}}
            <div class="flex items-center md:hidden gap-3">
                {{-- Theme Switcher Mobile --}}
                <button @click="$dispatch('theme-toggle')" 
                        class="h-10 w-10 rounded-xl bg-slate-50 dark:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 flex items-center justify-center hover:border-[#00bceb] transition-all">
                    <svg x-show="theme === 'light'" class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="theme === 'dark'" class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>

                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-500 dark:text-slate-400 hover:text-[#005073] dark:hover:text-[#00bceb] p-2 transition-colors">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div x-show="mobileMenuOpen" class="md:hidden bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 transition-colors duration-300"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         x-cloak>
        <div class="px-4 pt-4 pb-4 space-y-2">
            <a href="{{ route('student.exams') }}"
               class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest transition-colors duration-200
               {{ request()->routeIs('student.exams') ? 'bg-[#eefbff] dark:bg-slate-700 text-[#005073] dark:text-[#00bceb]' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-[#00bceb]' }}">
                My Exams
            </a>
            
            <div class="border-t border-slate-200 dark:border-slate-600 my-2 pt-2">
                <a href="{{ route('profile') }}" class="px-4 py-2 block hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-colors">
                     <p class="text-[10px] font-black text-[#00bceb] uppercase tracking-[0.15em] mb-1">Signed in as Student</p>
                    <p class="text-sm text-[#005073] dark:text-white font-extrabold">{{ Auth::user()->name }}</p>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
