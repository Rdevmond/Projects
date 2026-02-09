@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 relative bg-[#F8FAFC] dark:bg-slate-900 transition-colors duration-500">
    {{-- High-End Architectural Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-gradient-to-b from-[#049fd9]/10 to-transparent rounded-full blur-[120px] -mr-20 -mt-20 opacity-50 dark:opacity-20"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-[#005073]/10 rounded-full blur-[100px] -ml-20 -mb-20 opacity-50 dark:opacity-20"></div>
        {{-- Subtle Grid Pattern --}}
        <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style="background-image: radial-gradient(var(--text-primary) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="max-w-6xl w-full relative z-10">
        {{-- Elegant Breadcrumb/Status --}}
        <div class="flex items-center justify-center gap-3 mb-6 transition-colors">
            <span class="h-px w-8 bg-slate-300 dark:bg-slate-700"></span>
            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.5em]">System Readiness Check</span>
            <span class="h-px w-8 bg-slate-300 dark:bg-slate-700"></span>
        </div>

        <div class="grid lg:grid-cols-12 gap-10">
            {{-- Main Examination Console --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white dark:bg-slate-800 rounded-4xl border border-slate-200/60 dark:border-slate-700 shadow-[0_32px_64px_-16px_rgba(0,80,115,0.08)] dark:shadow-none overflow-hidden transition-all duration-700 hover:shadow-[0_40px_80px_-16px_rgba(0,80,115,0.12)]">
                    <div class="p-10 md:p-12">
                        {{-- Identity Header --}}
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 transition-colors">
                            <div class="space-y-2">
                                <div class="inline-flex items-center px-3 py-1 rounded-full bg-[#E2231A]/10 dark:bg-[#E2231A]/20 border border-[#E2231A]/20 dark:border-[#E2231A]/40 transition-colors">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#E2231A] mr-2 animate-pulse"></span>
                                    <span class="text-[10px] font-black text-[#E2231A] uppercase tracking-wider">Cisco NetRiders Authorized</span>
                                </div>
                                <h1 class="text-4xl md:text-5xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight leading-none transition-colors">
                                    {{ $exam->title }}
                                </h1>
                            </div>
                            <div class="hidden md:block text-right transition-colors">
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Assessment ID</p>
                                <p class="text-sm font-mono text-slate-600 dark:text-slate-400 font-bold transition-colors">NR-{{ str_pad($exam->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>

                        {{-- Description Area with Stylized Quote --}}
                        <div class="relative mb-12 group">
                            <div class="absolute -left-6 top-0 bottom-0 w-1 bg-gradient-to-b from-[#049fd9] to-transparent rounded-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                            <p class="text-xl text-slate-500 font-medium leading-relaxed font-serif italic">
                                "{{ $exam->description }}"
                            </p>
                        </div>

                        {{-- Data Grid --}}
                        <div class="grid sm:grid-cols-2 gap-8">
                            <div class="relative group">
                                <div class="absolute inset-0 bg-slate-50 dark:bg-slate-900/50 rounded-3xl transition-transform group-hover:scale-[1.02] duration-300"></div>
                                <div class="relative p-8 flex items-center gap-6">
                                    <div class="w-14 h-14 bg-white dark:bg-slate-800 rounded-2xl shadow-sm flex items-center justify-center text-[#049fd9] transition-colors">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-1 transition-colors">Total Items</p>
                                        <p class="text-3xl font-black text-[#005073] dark:text-white transition-colors">{{ $exam->questions->count() }} <span class="text-sm font-bold text-slate-400">Questions</span></p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative group">
                                <div class="absolute inset-0 bg-slate-50 dark:bg-slate-900/50 rounded-3xl transition-transform group-hover:scale-[1.02] duration-300"></div>
                                <div class="relative p-8 flex items-center gap-6">
                                    <div class="w-14 h-14 bg-white dark:bg-slate-800 rounded-2xl shadow-sm flex items-center justify-center text-[#E2231A] transition-colors">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-1 transition-colors">Duration</p>
                                        <p class="text-3xl font-black text-[#005073] dark:text-white transition-colors">{{ $exam->duration }} <span class="text-sm font-bold text-slate-400">Minutes</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Action Bar --}}
                    <div class="bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-sm border-t border-slate-100 dark:border-slate-700 p-8 flex flex-col sm:flex-row gap-4 transition-colors">
                        <a href="{{ route('student.exams') }}" 
                           class="px-8 py-4 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-[#E2231A] dark:hover:text-[#ff3c3c] transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to Dashboard
                        </a>
                        <form action="{{ route('exam.start', $exam) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-[#E2231A] hover:bg-[#be1e16] text-white py-4 px-10 rounded-xl font-bold text-xs uppercase tracking-[0.2em] shadow-lg shadow-[#E2231A]/20 transition-all duration-300 flex items-center justify-center gap-3 group active:scale-[0.98]">
                                Start Official Assessment
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Professional Sidebar: Rules & Meta --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-[#002b3d] rounded-4xl p-10 text-white relative overflow-hidden shadow-2xl">
                    {{-- Decorative Cisco Wave --}}
                    <div class="absolute top-0 right-0 opacity-10 translate-x-1/4 -translate-y-1/4">
                        <svg width="200" height="200" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" stroke="white" stroke-width="0.5" fill="none"/></svg>
                    </div>

                    <h4 class="text-lg font-bold mb-8 flex items-center gap-3 relative z-10">
                        <span class="w-1 h-6 bg-[#049fd9] rounded-full"></span>
                        Exam Protocol
                    </h4>

                    <div class="space-y-8 relative z-10">
                        @php
                            $rules = [
                                ['Secure Environment', 'Ensure you are in a quiet room with no external assistance.', 'shield-check'],
                                ['System Lock', 'The exam will terminate if you switch tabs or minimize the window.', 'lock-closed'],
                                ['Final Submission', 'Unsaved progress will be auto-submitted when the timer expires.', 'cloud-upload']
                            ];
                        @endphp

                        @foreach($rules as $rule)
                        <div class="flex gap-4 group">
                            <div class="w-2 h-2 rounded-full bg-[#049fd9] mt-1.5 shadow-[0_0_8px_#049fd9]"></div>
                            <div>
                                <h5 class="text-[11px] font-bold text-slate-300 uppercase tracking-widest mb-1 group-hover:text-white transition-colors">{{ $rule[0] }}</h5>
                                <p class="text-xs text-slate-400 leading-relaxed font-medium">{{ $rule[1] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-12 pt-8 border-t border-white/5 space-y-4">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Question Breakdown</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($exam->questions->groupBy('type') as $type => $questions)
                                <span class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-[10px] font-bold text-[#049fd9] uppercase">
                                    {{ $type }} <span class="text-white ml-1">{{ $questions->count() }}</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Support Card --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 flex items-center gap-4 shadow-sm transition-colors">
                    <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900 rounded-2xl flex items-center justify-center text-slate-400 dark:text-slate-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-[#005073] dark:text-[#00bceb] uppercase tracking-wider transition-colors">Technical Help?</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium transition-colors">Contact your instructor immediately.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection