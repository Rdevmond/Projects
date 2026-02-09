@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <div class="mb-10 transition-colors">
        <h1 class="text-4xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight transition-colors">Account Settings</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">Manage your personal information and security.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Profile Info --}}
        <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 flex flex-col justify-between transition-colors">
            <div>
                <div class="mb-8 flex items-center gap-4">
                    <div class="h-16 w-16 bg-[#eefbff] dark:bg-slate-900 rounded-2xl border-2 border-[#00bceb]/20 dark:border-[#00bceb]/40 flex items-center justify-center text-[#005073] dark:text-[#00bceb] font-black text-xl transition-colors">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white transition-colors">{{ Auth::user()->name }}</h3>
                        <p class="text-xs font-black text-[#00bceb] uppercase tracking-widest transition-colors">{{ Auth::user()->role == 1 ? 'Administrator' : 'Student' }}</p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition font-bold text-slate-700 dark:text-slate-200">
                        @error('name') <p class="text-[#E2231A] text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Username / ID</label>
                        <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}"
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition font-bold text-slate-700 dark:text-slate-200">
                        @error('username') <p class="text-[#E2231A] text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">School / Organization</label>
                        <input type="text" name="school" value="{{ old('school', Auth::user()->school) }}"
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition font-bold text-slate-700 dark:text-slate-200">
                        @error('school') <p class="text-[#E2231A] text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Email Address (Read-only)</label>
                        <input type="email" value="{{ Auth::user()->email }}" disabled
                            class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 opacity-60 cursor-not-allowed font-bold text-slate-400 dark:text-slate-500 transition-colors">
                    </div>

                    <button type="submit" class="w-full bg-[#005073] hover:bg-[#003e5c] text-white font-black py-4 rounded-2xl shadow-lg transition transform active:scale-95 uppercase tracking-widest text-xs">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

        {{-- Security / Password --}}
        <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 transition-colors">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-8 flex items-center gap-2 transition-colors">
                <span class="text-2xl text-[#E2231A]">🔐</span> Security
            </h3>

            <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Current Password</label>
                    <input type="password" name="current_password"
                        class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition font-bold text-slate-700 dark:text-slate-200">
                    @error('current_password') <p class="text-[#E2231A] text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">New Password</label>
                    <input type="password" name="password"
                        class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition font-bold text-slate-700 dark:text-slate-200"
                        placeholder="Min. 8 characters">
                    @error('password') <p class="text-[#E2231A] text-[10px] font-bold mt-2 ml-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-5 py-3.5 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition font-bold text-slate-700 dark:text-slate-200">
                </div>

                <div class="p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl transition-colors">
                    <p class="text-[10px] font-bold text-amber-700 dark:text-amber-500 leading-relaxed uppercase tracking-wide transition-colors">
                        ⚠️ Changing your password will update your access credentials. Make sure to remember it!
                    </p>
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-[#E2231A] text-white font-black py-4 rounded-2xl shadow-lg transition transform active:scale-95 uppercase tracking-widest text-xs">
                    Change Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
