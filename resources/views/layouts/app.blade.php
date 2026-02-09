<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IT Exam Portal | Cisco NetRider's</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        theme: {
                            primary: 'var(--bg-primary)',
                            secondary: 'var(--bg-secondary)',
                            tertiary: 'var(--bg-tertiary)',
                            text: 'var(--text-primary)',
                            muted: 'var(--text-secondary)',
                            border: 'var(--border-color)',
                        }
                    },
                    borderRadius: {
                        '4xl': '2rem',
                        '5xl': '2.5rem',
                        '6xl': '3rem',
                    },
                    borderWidth: {
                        '6': '6px',
                        '10': '10px',
                        '12': '12px',
                    },
                    letterSpacing: {
                        'super-wide': '0.3em',
                    },
                    zIndex: {
                        '100': '100',
                        '9999': '9999',
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --scrollbar-track: #f1f5f9;
        }

        .dark {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border-color: #334155;
            --scrollbar-track: #0f172a;
        }

        html {
            scroll-behavior: smooth;
            transition: background-color 0.3s ease;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [x-cloak] { display: none !important; }

        /* Loading Indicator Styles */
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #page-loader.active {
            opacity: 1;
            visibility: visible;
        }

        .loader-bar-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #f1f5f9;
            z-index: 10000;
            overflow: hidden;
            display: none;
        }

        #loader-progress {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #005073, #00bceb);
            transition: width 0.3s ease;
        }

        .cisco-pulse {
            width: 60px;
            height: 60px;
            background-color: #E2231A;
            border-radius: 50%;
            animation: pulse-cisco 1.5s infinite ease-in-out;
        }

        @keyframes pulse-cisco {
            0% { transform: scale(0.1); opacity: 0; }
            50% { opacity: 0.5; }
            100% { transform: scale(1.2); opacity: 0; }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-tertiary); }
        ::-webkit-scrollbar-thumb { background: #005073; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #00bceb; }
        
        .dark ::-webkit-scrollbar-track { background: #1e293b; }
        .dark ::-webkit-scrollbar-thumb { background: #00bceb; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #005073; }

        /* Toast Animations */
        @keyframes toast-in {
            from { transform: translateY(100%) scale(0.9); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }
        .animate-toast-in { animation: toast-in 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
</style>

    <script>
        // Initialize theme before page renders to prevent flash
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body class="flex flex-col min-h-screen" id="appBody" x-data="{ theme: localStorage.getItem('theme') || 'light' }" 
      x-init="$watch('theme', value => { 
          localStorage.setItem('theme', value); 
          document.documentElement.classList.toggle('dark', value === 'dark');
      })"
      @theme-toggle.window="theme = theme === 'dark' ? 'light' : 'dark'">

    <!-- Loading Indicators -->
    <div class="loader-bar-container" id="loader-bar-container">
        <div id="loader-progress"></div>
    </div>
    <div id="page-loader">
        <div class="cisco-pulse"></div>
        <div class="mt-4 text-slate-600 font-bold tracking-widest text-[10px] uppercase">Please wait...</div>
    </div>

    {{-- Global Toast System --}}
    <div x-data="toastSystem()" 
         x-init="
            @if(session('success')) add({ title: 'Success', message: '{{ session('success') }}', type: 'success' }); @endif
            @if(session('error')) add({ title: 'Error Occurred', message: '{{ session('error') }}', type: 'error' }); @endif
            @if(session('warning')) add({ title: 'Caution', message: '{{ session('warning') }}', type: 'warning' }); @endif
            @if(session('status')) add({ title: 'Status Update', message: '{{ session('status') }}', type: 'primary' }); @endif
         "
         @notify.window="add($event.detail)"
         class="fixed bottom-8 right-8 z-9999 flex flex-col gap-3 max-w-md w-full pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="pointer-events-auto bg-white dark:bg-slate-900 border-l-4 rounded-2xl shadow-2xl p-4 flex items-center gap-4 animate-toast-in border-slate-200 dark:border-slate-800 transition-colors duration-300"
                 :class="{
                    'border-[#00bceb]': toast.type === 'primary',
                    'border-[#E2231A]': toast.type === 'error',
                    'border-[#FF9E18]': toast.type === 'warning',
                    'border-emerald-500': toast.type === 'success'
                 }">
                <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-white/10">
                    <template x-if="toast.type === 'primary'"><span class="text-blue-400">ℹ️</span></template>
                    <template x-if="toast.type === 'error'"><span class="text-red-400">⚠️</span></template>
                    <template x-if="toast.type === 'warning'"><span class="text-orange-400">🔔</span></template>
                    <template x-if="toast.type === 'success'"><span class="text-emerald-400">✅</span></template>
                </div>
                <div class="flex-1">
                    <p class="text-slate-800 dark:text-white text-[13px] font-black uppercase tracking-widest" x-text="toast.title"></p>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-bold" x-text="toast.message"></p>
                </div>
                <button @click="remove(toast.id)" class="text-slate-400 dark:text-white/30 hover:text-slate-600 dark:hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    <div x-data="modalSystem()" 
         @modal.window="show($event.detail)"
         x-show="isOpen" 
         class="fixed inset-0 z-10000 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
             <div x-show="isOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 transition-opacity bg-slate-500/50 dark:bg-slate-900/80 backdrop-blur-sm" 
                 @click="close()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="isOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-slate-900/95 backdrop-blur-xl rounded-[3rem] shadow-3xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-10 border border-slate-200 dark:border-white/10 border-l-8 duration-300"
                 :class="{
                    'border-l-[#00bceb]': type === 'primary',
                    'border-l-[#E2231A]': type === 'error' || type === 'danger',
                    'border-l-[#FF9E18]': type === 'warning',
                    'border-l-emerald-500': type === 'success'
                 }">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center shrink-0 w-12 h-12 mx-auto bg-white/10 rounded-2xl sm:mx-0 sm:h-16 sm:w-16">
                        <span class="text-3xl" x-text="icon"></span>
                    </div>
                    <div class="mt-4 text-center sm:mt-0 sm:ml-8 sm:text-left">
                        <h3 class="text-2xl font-black leading-6 text-slate-800 dark:text-white uppercase tracking-tight" x-text="title"></h3>
                        <div class="mt-4">
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 leading-relaxed" x-text="message"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-10 sm:flex sm:flex-row-reverse gap-4">
                    <button @click="confirm()" type="button" 
                            class="inline-flex justify-center w-full px-10 py-5 text-xs font-black text-white uppercase tracking-widest transition-all bg-[#00bceb] border border-transparent rounded-2xl shadow-xl sm:ml-0 sm:w-auto hover:bg-[#003e5c] active:scale-95"
                            x-text="confirmText"></button>
                    <button @click="close()" type="button" 
                            class="inline-flex justify-center w-full px-10 py-5 mt-3 text-xs font-black text-slate-400 uppercase tracking-widest transition-all bg-white/5 border border-white/10 rounded-2xl sm:mt-0 sm:w-auto hover:bg-white/10 active:scale-95"
                            x-text="cancelText"></button>
                </div>
            </div>

        </div>
    </div>

    @auth
        @if (Auth::user()->role == 1)
            @include('layouts.partials.nav-admin')
        @else
            @include('layouts.partials.nav-student')
        @endif
    @else
        @include('layouts.partials.nav-guest')
    @endauth

    <main class="grow">
        <div class="max-w-7xl mx-auto py-10 px-6">
            @yield('content')
        </div>
    </main>

    {{-- Footer yang diselaraskan --}}
    <footer class="bg-slate-900 dark:bg-slate-950 text-slate-400 mt-auto border-t-4 border-[#E2231A] transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-16 px-6 sm:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- Sisi Kiri: Branding & Info --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-1.5 rounded-lg shadow-sm">
                            <img src="{{ asset('images/logo-netriders.png') }}" alt="Cisco NetRider's" class="h-8 w-auto">
                        </div>
                        <span class="text-[#E2231A] text-xl font-bold">CISCO <span class="text-white font-black tracking-tighter text-xl uppercase">NetRider's</span></span>
                    </div>
                    <p class="text-sm leading-relaxed">
                        Official student organization of <span class="text-white">Politeknik Caltex Riau</span>. 
                        We specialize in Cisco networking and are a certified <span class="text-[#00bceb] font-bold">Cisco Networking Academy</span> partner.
                    </p>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[9px] font-black uppercase tracking-widest text-slate-300">
                        🛡️ Cisco Academy Certified
                    </div>
                </div>

                {{-- Tengah: Contact & Address --}}
                <div class="space-y-6">
                    <h4 class="text-white font-black uppercase tracking-widest text-xs">Contact Information</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-4">
                            <span class="text-[#00bceb]">📍</span>
                            <span>Jl. Umban Sari (Patin) No. 1 Rumbai,<br>Pekanbaru, Riau 28265</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <span class="text-[#00bceb]">📧</span>
                            <a href="mailto:netriders@pcr.ac.id" class="hover:text-white transition">pcr@pcr.ac.id</a>
                        </li>
                    </ul>
                </div>

                {{-- Sisi Kanan: Quick Links & Copyright --}}
                <div class="text-left md:text-right space-y-6">
                    <h4 class="text-white font-black uppercase tracking-widest text-xs">Portal Quick Links</h4>
                    <div class="flex flex-col md:items-end gap-3 text-xs font-black uppercase tracking-[0.2em]">
                        <a href="{{ route('home') }}#about" class="hover:text-white transition">About Us</a>
                        <a href="{{ route('home') }}#activities" class="hover:text-white transition">Activities</a>
                        <a href="{{ route('login') }}" class="hover:text-white transition">Member Portal</a>
                    </div>
                    <div class="pt-6 border-t border-slate-800">
                        <p class="text-[9px] font-bold uppercase tracking-widest leading-loose">
                            &copy; {{ date('Y') }} <span class="text-white">CISCO NetRider's.</span><br>
                            Excellence in Networking Education.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Script untuk Loading Indicator Logic --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loaderContainer = document.getElementById('loader-bar-container');
            const loaderProgress = document.getElementById('loader-progress');
            const pageLoader = document.getElementById('page-loader');
            
            function startLoading() {
                loaderContainer.style.display = 'block';
                pageLoader.classList.add('active');
                
                // Simulate initial progress
                setTimeout(() => { loaderProgress.style.width = '30%'; }, 50);
                setTimeout(() => { loaderProgress.style.width = '70%'; }, 300);
            }

            function stopLoading() {
                loaderProgress.style.width = '100%';
                setTimeout(() => {
                    pageLoader.classList.remove('active');
                    setTimeout(() => {
                        loaderContainer.style.display = 'none';
                        loaderProgress.style.width = '0%';
                    }, 300);
                }, 100);
            }

            // Hide loading on page show (for back/forward cache)
            window.addEventListener('pageshow', function(event) {
                stopLoading();
            });

            // Handle link clicks
            document.querySelectorAll('a').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    const target = this.getAttribute('target');
                    
                    if (!href || href.startsWith('javascript:') || href.startsWith('#') || target === '_blank') return;

                    try {
                        const url = new URL(href, window.location.origin + window.location.pathname);
                        const current = new URL(window.location.href);

                        // Only show loading for internal page-to-page navigation
                        if (url.origin === current.origin && url.pathname !== current.pathname) {
                            startLoading();
                        }
                    } catch (err) {
                        // Fallback for relative paths or invalid URLs
                        if (!href.startsWith('http') && !href.startsWith('//')) {
                            startLoading();
                        }
                    }
                });
            });
        });

        function toastSystem() {
            return {
                toasts: [],
                add(detail) {
                    const id = Date.now();
                    this.toasts.push({
                        id: id,
                        title: detail.title || 'Notification',
                        message: detail.message || '',
                        type: detail.type || 'primary'
                    });
                    setTimeout(() => { this.remove(id); }, 5000);
                },
                remove(id) {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
            }
        }

        // Global function to trigger toasts from JS
        window.notify = function(message, title = 'System Update', type = 'primary') {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { title, message, type }
            }));
        };

        function modalSystem() {
            return {
                isOpen: false,
                title: '',
                message: '',
                icon: '❓',
                type: 'primary',
                confirmText: 'Confirm',
                cancelText: 'Cancel',
                onConfirm: null,
                show(detail) {
                    this.title = detail.title || 'Are you sure?';
                    this.message = detail.message || '';
                    this.icon = detail.icon || '❓';
                    this.confirmText = detail.confirmText || 'Confirm';
                    this.cancelText = detail.cancelText || 'Cancel';
                    this.onConfirm = detail.onConfirm;
                    this.type = detail.type || 'primary';
                    this.isOpen = true;
                },
                confirm() {
                    if (this.onConfirm) {
                        if (typeof window[this.onConfirm] === 'function') {
                            window[this.onConfirm]();
                        } else {
                            // Try to find as an element/form and submit it
                            const form = document.querySelector(this.onConfirm);
                            if (form && typeof form.submit === 'function') {
                                form.submit();
                            }
                        }
                    }
                    this.close();
                },
                close() {
                    this.isOpen = false;
                }
            }
        }

        window.confirmAction = function(options) {
            window.dispatchEvent(new CustomEvent('modal', {
                detail: options
            }));
        };
    </script>
</body>

</html>
