@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 transition-colors">
    <div>
        <a href="{{ route('admin.dashboard') }}" class="text-[#005073] dark:text-[#00bceb] font-bold flex items-center gap-2 mb-2 hover:underline text-sm transition-colors">
            ← Back to Dashboard
        </a>
        <h1 class="text-3xl font-black text-[#005073] dark:text-white tracking-tight transition-colors">{{ $exam->title }}</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">Exam Insights & Student Leaderboard</p>
    </div>

    {{-- TOMBOL DOWNLOAD REPORT --}}
    <div class="w-full md:w-auto">
        <a href="{{ route('admin.exams.report', $exam) }}" data-no-loader="true"
            class="flex items-center justify-center gap-2 px-6 py-3 bg-[#005073] dark:bg-[#00bceb] text-white dark:text-slate-900 rounded-2xl font-bold text-xs hover:bg-[#003d58] dark:hover:bg-[#008ebb] transition shadow-lg shadow-blue-100 dark:shadow-none uppercase tracking-widest w-full md:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download CSV Report
        </a>
    </div>
</div>

{{-- Indikator Gembok Nilai --}}
<div class="mb-6 p-4 rounded-2xl border transition-colors {{ $exam->isResultsReleased() ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-900/20 border-amber-100 dark:border-amber-800' }}">
    <div class="flex items-center gap-3">
        <div class="shrink-0 text-xl">
            {{ $exam->isResultsReleased() ? '🔓' : '🔒' }}
        </div>
        <div>
            <h4 class="text-sm font-black uppercase tracking-wide transition-colors {{ $exam->isResultsReleased() ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }}">
                {{ $exam->isResultsReleased() ? 'Results Released' : 'Results Locked' }}
            </h4>
            <p class="text-xs transition-colors {{ $exam->isResultsReleased() ? 'text-emerald-600 dark:text-emerald-500' : 'text-amber-600 dark:text-amber-500' }}">
                {{ $exam->isResultsReleased() 
                    ? 'Semua peserta sudah dinilai. Nilai sekarang terlihat di dashboard mahasiswa.' 
                    : 'Masih ada peserta yang belum dinilai (Pending). Nilai belum muncul di sisi mahasiswa.' }}
            </p>
        </div>
    </div>
</div>

{{-- 1. ANALYTICS CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 transition-colors">
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden transition-colors">
        <div class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2 transition-colors">Participants Completed</div>
        <div class="text-3xl font-black text-[#005073] dark:text-white transition-colors">
            {{ $submissions->whereIn('status', ['completed', 'pending'])->count() }}/{{ $exam->assignedUsers->count() > 0 ? $exam->assignedUsers->count() : \App\Models\User::where('role', 0)->count() }}
        </div>
        <div class="absolute -right-2 -bottom-2 text-slate-50 dark:text-slate-900 opacity-10 dark:opacity-20 font-black text-6xl transition-colors">01</div>
    </div>
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden transition-colors">
        <div class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2 transition-colors">Avg. Score</div>
        <div class="text-3xl font-black text-blue-600 dark:text-blue-400 transition-colors">{{ number_format($submissions->avg('score') ?? 0, 1) }}</div>
        <div class="absolute -right-2 -bottom-2 text-slate-50 dark:text-slate-900 opacity-10 dark:opacity-20 font-black text-6xl transition-colors">02</div>
    </div>
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden transition-colors">
        <div class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2 transition-colors">High Score</div>
        <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $submissions->max('score') ?? 0 }}</div>
        <div class="absolute -right-2 -bottom-2 text-slate-50 dark:text-slate-900 opacity-10 dark:opacity-20 font-black text-6xl transition-colors">03</div>
    </div>
    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden border-t-4 border-t-[#E2231A] dark:border-t-rose-500 transition-colors">
        <div class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2 transition-colors">Need Grading</div>
        <div class="text-3xl font-black text-[#E2231A] dark:text-rose-400 transition-colors">{{ $submissions->where('status', 'pending')->count() }}</div>
        <div class="absolute -right-2 -bottom-2 opacity-10 dark:opacity-20 font-black text-6xl text-red-100 dark:text-rose-900 transition-colors">!</div>
    </div>
</div>

{{-- 2. LEADERBOARD TABLE --}}
<div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
    <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 transition-colors">
        <h3 class="font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-wider flex items-center gap-2 transition-colors">
            🏆 Student Rankings
        </h3>
    </div>
    
    {{-- DESKTOP VIEW --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 transition-colors">
                    <th class="px-8 py-4 text-center">Rank</th>
                    <th class="px-8 py-4">Student</th>
                    <th class="px-8 py-4 text-center">Score</th>
                    <th class="px-8 py-4 text-center">Time Spent</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-right transition-colors">Submitted At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700 transition-colors">
                @forelse($submissions->sortByDesc('score')->values() as $index => $sub)
                <tr class="hover:bg-[#eefbff]/30 transition group">
                    <td class="px-8 py-5 text-center transition-colors">
                        @if($index == 0) <span class="text-xl">🥇</span>
                        @elseif($index == 1) <span class="text-xl">🥈</span>
                        @elseif($index == 2) <span class="text-xl">🥉</span>
                        @else <span class="font-bold text-slate-300 dark:text-slate-700 transition-colors">#{{ $index + 1 }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        <div class="font-bold text-slate-700 dark:text-slate-200 leading-tight transition-colors">{{ $sub->user->name }}</div>
                        <div class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-widest transition-colors">{{ $sub->user->school ?? 'General' }}</div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <div class="flex flex-col">
                            <span class="text-lg font-black text-[#005073] dark:text-[#00bceb] leading-none transition-colors">{{ $sub->score ?? 0 }}</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold tracking-tighter uppercase transition-colors">Points</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-900 rounded-full text-slate-600 dark:text-slate-400 text-xs font-bold transition-colors">
                            <svg class="w-3 h-3 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $sub->duration }}
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-wider transition-colors {{ $sub->status == 'completed' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' }}">
                            {{ $sub->status }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-right text-xs text-slate-400 dark:text-slate-500 font-medium transition-colors">
                        {{ $sub->created_at->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center text-slate-300 dark:text-slate-700 italic transition-colors">Belum ada submission.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE VIEW --}}
    <div class="md:hidden divide-y divide-slate-50 dark:divide-slate-700 transition-colors">
        @forelse($submissions->sortByDesc('score')->values() as $index => $sub)
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center font-black text-xs text-slate-400 dark:text-slate-600">
                        @if($index == 0) 🥇
                        @elseif($index == 1) 🥈
                        @elseif($index == 2) 🥉
                        @else #{{ $index + 1 }}
                        @endif
                    </div>
                    <div>
                        <div class="font-bold text-slate-700 dark:text-slate-200 leading-tight">{{ $sub->user->name }}</div>
                        <div class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-widest">{{ $sub->user->school ?? 'General' }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-black text-[#005073] dark:text-[#00bceb] leading-none">{{ $sub->score ?? 0 }}</div>
                    <div class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-tighter">Points</div>
                </div>
            </div>

            <div class="flex items-center justify-between text-[10px] font-bold">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded-md uppercase tracking-wider {{ $sub->status == 'completed' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' }}">
                        {{ $sub->status == 'completed' ? 'Ready' : 'Pending' }}
                    </span>
                    <div class="flex items-center gap-1 text-slate-400">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $sub->duration }}
                    </div>
                </div>
                <div class="text-slate-400">
                    {{ $sub->created_at->format('M d, H:i') }}
                </div>
            </div>
        </div>
        @empty
        <div class="p-10 text-center text-slate-300 dark:text-slate-700 italic text-xs uppercase tracking-widest">
            No submissions yet.
        </div>
        @endforelse
    </div>
</div>
@endsection
