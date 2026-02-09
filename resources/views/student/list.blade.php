@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12" x-data="{ tab: 'available' }">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-4xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight mb-2 transition-colors">Student Dashboard</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Welcome back, {{ Auth::user()->name }}! Ready to challenge yourself?</p>
        </div>
        
        <div class="flex bg-white dark:bg-slate-800 p-1.5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors">
            <button @click="tab = 'available'" 
                :class="tab === 'available' ? 'bg-[#005073] dark:bg-[#00bceb] text-white shadow-md' : 'text-slate-400 hover:text-[#005073] dark:hover:text-[#00bceb]'"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                Available Exams
            </button>
            <button @click="tab = 'history'" 
                :class="tab === 'history' ? 'bg-[#005073] dark:bg-[#00bceb] text-white shadow-md' : 'text-slate-400 hover:text-[#005073] dark:hover:text-[#00bceb]'"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                Exam History
            </button>
        </div>
    </div>

    {{-- TAB: AVAILABLE EXAMS --}}
    <div x-show="tab === 'available'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        
        @if($exams->isEmpty())
            <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-5xl border border-dashed border-slate-300 dark:border-slate-600 transition-colors">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white transition-colors">No exams available yet.</h3>
                <p class="text-slate-400 dark:text-slate-500 mt-2">Check back later for new assignments.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($exams as $exam)
                <div class="group bg-white dark:bg-slate-800 rounded-5xl p-8 border border-slate-100 dark:border-slate-700 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                         <svg class="w-24 h-24 text-[#00bceb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>

                    <div class="relative z-10">
                        @php
                            $isAssigned = $exam->assignedUsers->contains(Auth::id());
                            $hasAssignments = $exam->assignedUsers->count() > 0;
                            $alreadyCompleted = $exam->submissions->where('user_id', Auth::id())->first();
                        @endphp

                        @if($alreadyCompleted)
                            <span class="inline-block px-3 py-1 rounded-lg bg-[#6CC04A] text-white text-[10px] font-black uppercase tracking-widest mb-4">
                                ✓ Completed
                            </span>
                        @elseif($hasAssignments && $isAssigned)
                            <span class="inline-block px-3 py-1 rounded-lg bg-[#049FD9] text-white text-[10px] font-black uppercase tracking-widest mb-4">
                                📌 Assigned to You
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest mb-4">
                                Active
                            </span>
                        @endif

                        <h3 class="text-2xl font-black text-[#005073] dark:text-white mb-3 leading-tight group-hover:text-[#00bceb] transition-colors">
                            {{ $exam->title }}
                        </h3>
                        <p class="text-slate-400 dark:text-slate-500 text-sm font-medium line-clamp-3 mb-8">
                            {{ $exam->description ?? 'No description provided.' }}
                        </p>

                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-xs font-bold">{{ $exam->questions_count ?? $exam->questions->count() }} Questions</span>
                            </div>
                            @if($alreadyCompleted)
                                <a href="{{ route('student.result', $alreadyCompleted) }}" 
                                   class="px-6 py-3 bg-[#6CC04A] text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#5ab039] shadow-lg transition-all">
                                    View Result
                                </a>
                            @else
                                <a href="{{ route('exam.confirm', $exam) }}" 
                                   class="px-6 py-3 bg-[#E2231A] text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-[#049FD9] shadow-lg transition-all">
                                    Start Exam
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $exams->links() }}
            </div>
        @endif
    </div>

    {{-- TAB: HISTORY --}}
    <div x-show="tab === 'history'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
        @if($history->isEmpty())
             <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-5xl border border-dashed border-slate-300 dark:border-slate-600 transition-colors">
                <div class="text-6xl mb-4">🕰️</div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white transition-colors">No exam history found.</h3>
                <p class="text-slate-400 dark:text-slate-500 mt-2">Go take some exams first!</p>
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-5xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700 transition-colors">
                        <tr>
                            <th class="px-8 py-6 text-xs font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-widest">Exam Title</th>
                            <th class="px-8 py-6 text-xs font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-widest">Date Taken</th>
                            <th class="px-8 py-6 text-xs font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-widest text-center">Score</th>
                            <th class="px-8 py-6 text-xs font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-widest text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        @foreach($history as $sub)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-700 dark:text-slate-200 transition-colors">{{ $sub->examForm->title }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500 mt-1 transition-colors">{{ Str::limit($sub->examForm->description, 50) }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-slate-600 dark:text-slate-300 transition-colors">{{ $sub->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500 transition-colors">{{ $sub->created_at->format('H:i A') }}</div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-black transition-colors {{ $sub->score >= 70 ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400' }}">
                                    {{ $sub->score }} / {{ $sub->total_questions }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('student.result', $sub) }}" class="text-xs font-black text-[#005073] dark:text-[#00bceb] hover:text-[#00bceb] dark:hover:text-white uppercase tracking-widest transition-colors">
                                    View Result ➔
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
