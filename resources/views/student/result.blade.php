@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 pt-12 pb-32">
    
    {{-- Score Hero Section --}}
    <div class="relative mb-20">
        <div class="absolute inset-0 bg-gradient-to-br from-[#005073] to-[#049FD9] rounded-[4rem] transform -rotate-1 shadow-2xl opacity-20"></div>
        <div class="relative bg-slate-900 border border-slate-800 shadow-2xl rounded-[4rem] p-12 md:p-16 overflow-hidden">
            {{-- Decorative pattern --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-[#00bceb]/10 rounded-full -translate-x-1/2 translate-y-1/2"></div>

            <div class="flex flex-col md:flex-row items-center gap-12 relative z-10 text-white">
                {{-- Score Circle --}}
                <div class="relative group flex justify-center">
                    <div class="absolute inset-0 bg-[#00bceb] rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <div class="relative w-56 h-56 rounded-full border-10 border-white/5 shadow-2xl flex flex-col items-center justify-center bg-slate-800/50 backdrop-blur-xl">
                        {{-- Circular Progress SVG --}}
                        @php
                            $percentage = ($submission->total_questions > 0) ? round(($submission->score / $submission->total_questions) * 100) : 0;
                            $radius = 85;
                            $circumference = 2 * pi() * $radius;
                            $offset = $circumference - ($percentage / 100 * $circumference);
                        @endphp
                        <svg class="absolute inset-0 w-full h-full -rotate-90 pointer-events-none" viewBox="0 0 224 224">
                            <circle cx="112" cy="112" r="{{ $radius }}" stroke="currentColor" stroke-width="10" fill="transparent" class="text-white/5" />
                            <circle cx="112" cy="112" r="{{ $radius }}" stroke="currentColor" stroke-width="12" fill="transparent" class="text-[#00bceb] transition-all duration-1000"
                                    style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $offset }}; stroke-linecap: round;" />
                        </svg>
                        
                        <div class="flex flex-col items-center z-10">
                            <span class="text-7xl font-black text-white tracking-tighter">{{ $submission->score }}</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-super-wide mt-1">/ {{ $submission->total_questions }} Points</span>
                        </div>
                    </div>
                </div>

                {{-- Feedback Info --}}
                <div class="flex-1 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#E2231A]/10 text-[#E2231A] rounded-full text-[10px] font-black uppercase tracking-widest mb-4 border border-[#E2231A]/20">
                        ✨ Official Assessment Result
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight tracking-tight">{{ $submission->examForm->title }}</h1>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-4">
                        <div class="bg-white/5 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/10">
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Performance</span>
                            <span class="text-2xl font-black text-[#00bceb]">{{ $percentage }}%</span>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/10 text-[#E2231A]">
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</span>
                            <span class="text-2xl font-black">CERTIFIED</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Results Sections --}}
    <div class="space-y-12">
        {{-- Results Info / Release Status --}}
        @if(!$isReleased)
            <div class="bg-amber-50 dark:bg-amber-900/10 rounded-[3rem] p-12 border border-amber-100 dark:border-amber-900/30 text-center transition-colors">
                <div class="w-20 h-20 bg-amber-100 dark:bg-amber-900/20 text-amber-600 dark:text-amber-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-8 animate-pulse">
                    ⏳
                </div>
                <h3 class="text-3xl font-black text-[#005073] dark:text-amber-100 mb-4 transition-colors">Detailed Results Pending</h3>
                <p class="text-slate-600 dark:text-slate-400 font-medium max-w-lg mx-auto leading-relaxed mb-10 transition-colors">
                    We are verifying and grading all student responses. Detailed question-by-question performance will be visible here once assessment is finalized.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
                    @php
                        $status = $submission->examForm->getCompletionStatus();
                        $pendingEssays = $submission->examForm->submissions()->where('status', 'pending')->count();
                    @endphp
                    
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-amber-200/50 dark:border-amber-900/30 shadow-sm flex items-center justify-between transition-colors">
                        <span class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Students Done</span>
                        <span class="text-xl font-black text-[#005073] dark:text-white transition-colors">{{ $status['completed'] }} / {{ $status['total'] }}</span>
                    </div>

                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-amber-200/50 dark:border-amber-900/30 shadow-sm flex items-center justify-between transition-colors">
                        <span class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Pending Essays</span>
                        <span class="text-xl font-black text-[#E2231A] transition-colors">{{ $pendingEssays }}</span>
                    </div>
                </div>
            </div>
        @else
            {{-- CATEGORIZED QUESTION REVIEW --}}
            <div class="space-y-16">
                {{-- Analysis Header --}}
                <div class="flex items-center justify-between px-4">
                    <h3 class="text-2xl font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-wide transition-colors">Performance Summary</h3>
                    <div class="flex gap-2">
                         <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-900/30 transition-colors">Correct</div>
                         <div class="flex items-center gap-2 px-3 py-1 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-lg text-[10px] font-black uppercase tracking-widest border border-rose-100 dark:border-rose-900/30 transition-colors">Wrong</div>
                    </div>
                </div>

                <div class="space-y-8">
                    @foreach($submission->examForm->questions as $index => $q)
                        @php
                            $snap = collect($submission->answers_snapshot)->firstWhere('question_id', $q->id);
                            $isCorrect = $snap['is_correct'] ?? false;
                            $userAnswer = $snap['answer'] ?? null;
                        @endphp

                        <div class="group bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 md:p-10 border border-slate-100 dark:border-slate-700 shadow-xl shadow-slate-200/30 dark:shadow-none hover:border-[#00bceb]/30 transition-all duration-300">
                            <div class="flex flex-col md:flex-row gap-8">
                                <div class="shrink-0 flex md:flex-col items-center gap-4">
                                     <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl shadow-inner border transition-colors {{ $isCorrect ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/30' : 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/30' }}">
                                        {{ $isCorrect ? '✓' : '✗' }}
                                     </div>
                                     <span class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] transform md:-rotate-90 md:mt-12 transition-colors">Q-{{ $index + 1 }}</span>
                                </div>

                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-4">
                                        <h4 class="text-xl font-black text-slate-800 leading-snug">{{ $q->question_text }}</h4>
                                        <span class="text-[9px] font-black px-3 py-1 bg-slate-100 text-slate-400 rounded-full uppercase tracking-widest">{{ $q->type }}</span>
                                    </div>

                                    @if($q->context_image_path)
                                        <div class="my-6">
                                            <img src="{{ asset('storage/' . $q->context_image_path) }}" class="max-h-64 object-cover rounded-2xl border-2 border-slate-50">
                                        </div>
                                    @endif

                                    <div class="mt-8">
                                        @if($q->type == 'option')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @foreach($q->answer_details['options'] as $opt)
                                                    @php
                                                        $isSelected = in_array($opt['id'], (array)($userAnswer ?? []));
                                                        $isRight = $opt['is_correct'] ?? false;
                                                    @endphp
                                                    <div class="flex items-center gap-3 p-4 rounded-2xl border transition-colors {{ $isSelected ? ($isRight ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-900/30' : 'bg-rose-50 dark:bg-rose-900/20 border-rose-100 dark:border-rose-900/30') : ($isRight ? 'bg-slate-50 dark:bg-emerald-900/10 border-emerald-200 dark:border-emerald-700 ring-2 ring-emerald-100 dark:ring-emerald-900/50' : 'bg-slate-50 dark:bg-slate-900/30 border-slate-100 dark:border-slate-700 opacity-60') }}">
                                                        @if($isSelected)
                                                            <div class="w-2 h-2 rounded-full transition-colors {{ $isRight ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                                        @elseif($isRight)
                                                            <span class="text-xs">💡</span>
                                                        @else
                                                            <div class="w-2 h-2 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors"></div>
                                                        @endif
                                                        <span class="text-xs font-bold transition-colors {{ $isRight || $isSelected ? 'text-slate-800 dark:text-slate-200' : 'text-slate-500 dark:text-slate-500' }}">{{ $opt['text'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($q->type == 'connect')
                                             <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pairing Analysis</p>
                                                <div class="grid gap-2">
                                                    @foreach($q->answer_details['pairs'] as $pair)
                                                        @php
                                                            $currentAnswer = $userAnswer[$pair['left']] ?? 'Not Answered';
                                                            $isSpecificCorrect = $currentAnswer === $pair['right'];
                                                        @endphp
                                                        <div class="flex items-center gap-3 text-xs font-bold">
                                                            <div class="flex-1 p-3 bg-white rounded-xl border border-slate-100">{{ $pair['left'] }}</div>
                                                            <span class="{{ $isSpecificCorrect ? 'text-emerald-500' : 'text-rose-500' }}">➔</span>
                                                            <div class="flex-1 p-3 rounded-xl border {{ $isSpecificCorrect ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-rose-50 border-rose-100 text-rose-700' }}">
                                                                {{ $currentAnswer }}
                                                                @if(!$isSpecificCorrect)
                                                                    <div class="text-[9px] mt-1 text-slate-400 font-black uppercase tracking-tighter">Correct: {{ $pair['right'] }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                             </div>
                                        @elseif($q->type == 'essay')
                                             <div class="relative">
                                                 <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border-2 border-slate-100 dark:border-slate-700 italic text-slate-600 dark:text-slate-400 text-sm font-medium transition-colors">
                                                     "{{ $userAnswer ?? 'No answer provided.' }}"
                                                 </div>
                                                 <div class="absolute -top-3 -right-3 px-4 py-1.5 bg-[#005073] dark:bg-[#00bceb] text-white rounded-full text-[9px] font-black uppercase tracking-widest transition-colors shadow-lg">Essay Review</div>
                                             </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Footer Actions --}}
    <div class="fixed bottom-0 left-0 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-t border-slate-100 dark:border-slate-800 p-6 flex justify-center z-50 transition-colors">
        <div class="max-w-4xl w-full flex justify-between gap-4">
             <a href="{{ route('student.exams') }}" class="flex-1 px-8 py-4 bg-slate-900 dark:bg-[#00bceb] text-white rounded-2xl font-black uppercase tracking-widest text-xs text-center shadow-xl hover:bg-slate-800 dark:hover:bg-[#005073] transition-all transform active:scale-95">
                Dashboard
            </a>
             <button onclick="window.print()" class="px-8 py-4 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-slate-50 dark:hover:bg-slate-700 transition-all active:scale-95">
                Print Report
            </button>
        </div>
    </div>
</div>
@endsection
