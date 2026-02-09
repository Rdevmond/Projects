@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl dark:shadow-none border border-slate-100 dark:border-slate-700 w-full max-w-md transition-all duration-300">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-netriders.png') }}" class="h-16 mx-auto mb-4 drop-shadow-md brightness-100" alt="Cisco NetRiders">
            <h2 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight transition-colors">Welcome Back</h2>
            <p class="text-slate-400 dark:text-slate-500 font-medium transition-colors">Exam Portal Access</p>
        </div>

        {{-- Error Messaging --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 rounded-xl transition-colors">
                @foreach ($errors->all() as $error)
                    <p class="text-red-700 dark:text-red-400 text-xs font-bold uppercase tracking-wide transition-colors">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-5 text-left">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Username / ID</label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition font-medium text-slate-700 dark:text-slate-200"
                    placeholder="Enter your username" required autofocus>
            </div>

            <div class="mb-4 text-left">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Password</label>
                <input type="password" name="password"
                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-50 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition font-medium text-slate-700 dark:text-slate-200"
                    placeholder="••••••••" required>
            </div>

            <div class="flex items-center justify-between mb-8 px-1">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500 transition-colors">
                    <span class="ml-2 text-xs font-bold text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Keep me signed in</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-slate-900 dark:bg-[#00bceb] hover:bg-blue-600 dark:hover:bg-[#005073] text-white font-black py-5 rounded-2xl shadow-lg shadow-slate-200 dark:shadow-none transition transform active:scale-95 uppercase tracking-widest text-sm">
                Login
            </button>
        </form>
    </div>
</div>
@endsection
