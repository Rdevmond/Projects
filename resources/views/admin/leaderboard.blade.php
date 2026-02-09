@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-colors">
    <div>
        <h1 class="text-3xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight transition-colors">Leaderboard</h1>
        <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">Rankings for <strong class="dark:text-white">{{ $exam->title }}</strong></p>
    </div>
    <div class="flex gap-3 transition-colors">
        <a href="{{ route('admin.dashboard') }}" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[#005073] dark:text-[#00bceb] px-6 py-3 rounded-2xl font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition flex items-center gap-2 transition-colors">
            ⬅️ Back to Dashboard
        </a>
    </div>
</div>

{{-- Leaderboard Table --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/30 transition-colors">
        <h2 class="font-black text-[#005073] dark:text-[#00bceb] uppercase text-xs tracking-widest transition-colors">Student Rankings</h2>
    </div>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/30 transition-colors">
                <th class="px-6 py-4 text-[#005073] dark:text-[#00bceb] transition-colors">Rank</th>
                <th class="px-6 py-4 text-[#005073] dark:text-[#00bceb] transition-colors">Student Name</th>
                <th class="px-6 py-4 text-[#005073] dark:text-[#00bceb] transition-colors">School / Organization</th>
                <th class="px-6 py-4 text-[#005073] dark:text-[#00bceb] text-center transition-colors">Score</th>
                <th class="px-6 py-4 text-right text-[#005073] dark:text-[#00bceb] transition-colors">Completed At</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
            @forelse($submissions as $index => $submission)
            <tr class="hover:bg-[#eefbff]/50 dark:hover:bg-[#00bceb]/5 transition group {{ $index < 3 ? 'bg-amber-50/30 dark:bg-amber-500/5' : '' }} transition-colors">
                <td class="px-6 py-5 transition-colors">
                    @if($index == 0) <span class="text-xl">🥇</span>
                    @elseif($index == 1) <span class="text-xl">🥈</span>
                    @elseif($index == 2) <span class="text-xl">🥉</span>
                    @else <span class="font-bold text-slate-400 dark:text-slate-600 transition-colors">#{{ $index + 1 }}</span>
                    @endif
                </td>
                <td class="px-6 py-5 transition-colors">
                    <div class="font-bold text-slate-700 dark:text-slate-200 transition-colors">{{ $submission->user->name }}</div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-widest transition-colors">{{ $submission->user->school ?? 'General' }}</div>
                </td>
                <td class="px-6 py-5 transition-colors">
                    <span class="px-3 py-1 bg-[#005073]/10 dark:bg-[#00bceb]/10 text-[#005073] dark:text-[#00bceb] rounded-lg text-xs font-bold border border-[#005073]/10 dark:border-[#00bceb]/10 transition-colors">
                        {{ $submission->user->school ?? 'General' }}
                    </span>
                </td>
                <td class="px-6 py-5 text-center transition-colors">
                    <div class="font-black text-lg text-[#005073] dark:text-[#00bceb] transition-colors">{{ $submission->score }}/{{ $submission->total_questions }}</div>
                </td>
                <td class="px-6 py-5 text-right font-medium text-slate-500 dark:text-slate-400 text-sm transition-colors">
                    {{ $submission->created_at->format('M d, Y - H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-20 text-center text-slate-400 dark:text-slate-600 font-medium transition-colors">
                    No submissions found for this leaderboard.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    nav svg { width: 20px; display: inline; }
</style>
@endsection
