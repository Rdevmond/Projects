@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-950 py-12 px-6 transition-colors duration-500" x-data="{ showPassword: false }">
    <div class="max-w-5xl mx-auto">
        
        {{-- Header with Avatar & Breadcrumb --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                {{-- Avatar with Inline Gradient --}}
                <div class="relative group">
                    <div class="h-24 w-24 rounded-3xl flex items-center justify-center text-white font-black text-3xl shadow-2xl relative overflow-hidden" 
                         style="background: linear-gradient(135deg, #005073 0%, #00bceb 100%);">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <span class="relative z-10">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 h-8 w-8 bg-emerald-500 rounded-full border-4 border-white dark:border-slate-800 shadow-lg flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </div>

                <div>
                    <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#00bceb]/10 dark:bg-[#00bceb]/20 text-[#005073] dark:text-[#00bceb] text-[10px] font-black uppercase tracking-widest mb-2 border border-[#00bceb]/30 dark:border-[#00bceb]/40 transition-colors">
                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
                        Cisco NetRiders Student
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-[#005073] dark:text-white tracking-tight leading-none mb-1 transition-colors">{{ $user->name }}</h1>
                    <p class="text-slate-500 dark:text-slate-400 font-semibold text-sm transition-colors">Manage academic records and access credentials</p>
                </div>
            </div>
            
            <a href="{{ route('admin.users.index') }}" 
               class="group inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-2xl font-bold text-sm hover:text-[#005073] dark:hover:text-white hover:shadow-xl transition-all border-2 border-slate-200 dark:border-slate-700 hover:border-[#00bceb]/30">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Return to Directory
            </a>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-white/60 dark:border-slate-700 overflow-hidden relative transition-colors">
            {{-- Decorative Background Elements --}}
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-3xl opacity-10" 
                 style="background: linear-gradient(135deg, #00bceb 0%, #005073 100%);"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full blur-3xl opacity-5" 
                 style="background: linear-gradient(135deg, #E2231A 0%, #be1e16 100%);"></div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="relative z-10 p-10 md:p-14">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-12">
                    
                    {{-- Left Column: Academic Identity --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-6 transition-colors">
                            <div class="h-1.5 w-1.5 rounded-full bg-[#E2231A] transition-colors"></div>
                            <h3 class="text-xl font-black text-[#005073] dark:text-white uppercase tracking-tight transition-colors">Academic Identity</h3>
                            <div class="h-px flex-1 bg-gradient-to-r from-slate-200 dark:from-slate-700 to-transparent transition-colors"></div>
                        </div>

                        <div class="space-y-5">
                            <div class="group">
                                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2.5 ml-1 tracking-widest transition-colors">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full bg-slate-50/80 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-base font-bold text-slate-800 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 transition-all placeholder-slate-300 group-hover:border-slate-300 dark:group-hover:border-slate-600" 
                                    required>
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2.5 ml-1 tracking-widest transition-colors">School / Institution</label>
                                <input type="text" name="school" value="{{ old('school', $user->school) }}" 
                                    placeholder="e.g. High School A"
                                    class="w-full bg-slate-50/80 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-base font-bold text-slate-800 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 transition-all placeholder-slate-300 group-hover:border-slate-300 dark:group-hover:border-slate-600">
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2.5 ml-1 tracking-widest transition-colors">Login ID (Username)</label>
                                <div class="relative">
                                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                        class="w-full bg-slate-50/80 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-base font-bold text-slate-800 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 transition-all placeholder-slate-300 pl-12 group-hover:border-slate-300 dark:group-hover:border-slate-600" 
                                        required>
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[#00bceb] font-black text-lg transition-colors">@</span>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2.5 ml-1 tracking-widest transition-colors">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full bg-slate-50/80 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-base font-bold text-slate-800 dark:text-slate-200 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 transition-all placeholder-slate-300 group-hover:border-slate-300 dark:group-hover:border-slate-600" 
                                    required>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Security & Access --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-6 transition-colors">
                            <div class="h-1.5 w-1.5 rounded-full bg-[#00bceb] transition-colors"></div>
                            <h3 class="text-xl font-black text-[#005073] dark:text-white uppercase tracking-tight transition-colors">Security & Access</h3>
                            <div class="h-px flex-1 bg-gradient-to-r from-slate-200 dark:from-slate-700 to-transparent transition-colors"></div>
                        </div>

                        {{-- Sticky Save Bar --}}
                        <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-full max-w-lg px-6">
                            <div class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-2xl p-4 shadow-2xl border border-slate-200 dark:border-white/10 flex items-center justify-between transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <span class="text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-widest">Unsaved Changes Detected</span>
                                </div>
                                <button type="submit" 
                                        class="relative group overflow-hidden bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-lg shadow-emerald-500/20">
                                    <span class="relative z-10 font-black">Commit Changes</span>
                                    <div class="absolute inset-0 bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-700 font-black"></div>
                                </button>
                            </div>
                        </div>
                        {{-- Current Password Display Card --}}
                        <div class="relative rounded-3xl p-8 text-white text-center overflow-hidden shadow-2xl border-2 border-white/20 group hover:scale-[1.02] transition-transform duration-300" 
                             style="background: linear-gradient(135deg, #005073 0%, #004060 50%, #003049 100%);">
                            {{-- Noise Texture --}}
                            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 400 400%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
                            
                            {{-- Animated Gradient Overlay --}}
                            <div class="absolute inset-0 opacity-30 group-hover:opacity-50 transition-opacity" 
                                 style="background: linear-gradient(45deg, transparent 30%, #00bceb 50%, transparent 70%); background-size: 200% 200%; animation: shimmer 3s infinite;">
                            </div>

                            <div class="relative z-10">
                                <p class="text-[11px] font-black uppercase tracking-[0.2em] text-[#00bceb] mb-3 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                    Current Access Key
                                </p>
                                
                                <div class="relative inline-block">
                                    <code class="block text-4xl font-mono font-black tracking-widest mb-3 select-all" 
                                          x-show="!showPassword">••••••••</code>
                                    <code class="block text-4xl font-mono font-black tracking-wider mb-3 select-all text-[#00bceb]" 
                                          x-show="showPassword" 
                                          x-cloak>{{ $user->plain_password ? Crypt::decryptString($user->plain_password) : '--------' }}</code>
                                </div>

                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        class="inline-flex items-center gap-2 px-5 py-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl text-xs font-bold uppercase tracking-widest transition-all border border-white/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showPassword">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="showPassword" x-cloak>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                    <span x-show="!showPassword">Reveal Key</span>
                                    <span x-show="showPassword" x-cloak>Hide Key</span>
                                </button>

                                <p class="text-[10px] text-white/40 mt-3 font-medium">🔒 Encrypted & Secure Storage</p>
                            </div>
                        </div>

                        {{-- Reset Password Section --}}
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100/50 dark:from-slate-900 dark:to-slate-800/50 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-7 hover:border-[#E2231A]/30 dark:hover:border-rose-500/30 transition-colors">
                            <div class="flex items-start gap-4 mb-5 transition-colors">
                                <div class="p-3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm text-[#E2231A] dark:text-rose-400 border border-[#E2231A]/10 dark:border-rose-400/10 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 transition-colors">
                                    <h4 class="text-base font-black text-slate-800 dark:text-slate-200 mb-1 transition-colors">Reset Access Key</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold transition-colors">Leave blank to keep the current key unchanged.</p>
                                </div>
                            </div>
                            
                            <div class="relative group transition-colors">
                                <input type="text" 
                                       name="password" 
                                       placeholder="Enter new access key..."
                                       class="w-full bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 dark:text-slate-200 focus:border-[#E2231A] dark:focus:border-rose-500 focus:ring-4 focus:ring-[#E2231A]/10 dark:focus:ring-rose-500/10 transition-all placeholder-slate-300 dark:placeholder-slate-600 pr-12 group-hover:border-slate-300 dark:group-hover:border-slate-600 mb-0">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 dark:text-slate-600 group-hover:text-[#E2231A] dark:group-hover:text-rose-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="border-t-2 border-slate-100 dark:border-slate-800 pt-10 flex flex-col sm:flex-row items-center justify-between gap-5 transition-colors">
                    <button type="button" 
                            onclick="history.back()" 
                            class="px-8 py-4 text-slate-500 dark:text-slate-400 font-bold text-sm uppercase tracking-widest hover:text-[#005073] dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-900 rounded-2xl transition-colors">
                        ← Cancel Changes
                    </button>
                    
                    <button type="submit" 
                            class="relative px-12 py-5 rounded-2xl font-black uppercase tracking-widest text-sm text-white shadow-2xl transition-all transform hover:scale-[1.02] active:scale-95 flex items-center gap-3 overflow-hidden group"
                            style="background: linear-gradient(135deg, #E2231A 0%, #be1e16 100%);">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        <span class="relative z-10">Save Update</span>
                        <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- Info Footer --}}
        <div class="mt-8 text-center">
            <p class="text-xs text-slate-400 font-semibold">
                <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                All changes are logged and encrypted for security compliance
            </p>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    input:focus { outline: none !important; }
    input::placeholder { color: #cbd5e1; font-weight: 500; }
    
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
</style>
@endsection
