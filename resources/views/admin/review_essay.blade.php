@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
 {{-- Header Section --}}
 <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
 <div>
 <a href="{{ url()->previous() }}" class="text-[#00bceb] font-bold text-sm hover:text-[#005073] dark:hover:text-white transition flex items-center gap-2">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
 Back to List
 </a>
 <h1 class="text-3xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight mt-2 transition-colors">Manual Grading</h1>
 <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">Reviewing: <span class="text-[#E2231A] dark:text-rose-400 font-bold">{{ $submission->user->name }}</span></p>
 </div>
 <div class="text-left md:text-right bg-[#eefbff] dark:bg-[#00bceb]/10 p-4 rounded-2xl border border-[#00bceb]/20 dark:border-[#00bceb]/30">
 <span class="text-[10px] font-black uppercase text-[#005073] dark:text-[#00bceb] block tracking-widest transition-colors">Auto-Score Result</span>
 <span class="text-2xl font-black text-[#005073] dark:text-white transition-colors">{{ $submission->score }} <span class="text-slate-400 dark:text-slate-600 text-lg">/ {{ $submission->total_questions }}</span></span>
 </div>
 </div>

 <form action="{{ route('submissions.grade', $submission) }}" method="POST">
 @csrf
 <div class="space-y-6">
 @foreach($submission->examForm->questions->where('type', 'essay') as $index => $question)
 <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden border-l-[6px] border-l-[#00bceb] transition-colors">
 <div class="p-6 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center transition-colors">
 <h3 class="font-bold text-[#005073] dark:text-[#00bceb] transition-colors">Essay Question #{{ $loop->iteration }}</h3>
 <span class="bg-[#E2231A]/10 dark:bg-rose-500/20 text-[#E2231A] dark:text-rose-400 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-tighter animate-pulse transition-colors">
 Needs Review
 </span>
 </div>

 <div class="p-8">
 {{-- Question Text --}}
 <div class="mb-6">
 <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest block mb-2 transition-colors">The Question</span>
 <p class="text-lg font-bold text-slate-700 dark:text-slate-200 leading-snug mb-4 transition-colors">{{ $question->question_text }}</p>
 
 @if($question->context_image_path)
 <div class="mb-4">
 <img src="{{ asset('storage/' . $question->context_image_path) }}" 
 alt="Question Illustration" 
 class="max-w-xs h-auto rounded-2xl border-2 border-slate-50 dark:border-slate-800 shadow-sm transition-colors">
 </div>
 @endif
 </div>

 {{-- Student Answer Box --}}
 <div class="mb-8">
 <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest block mb-2 transition-colors">Student Response</span>
 <div class="p-6 bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-slate-200 dark:border-slate-700 text-[#005073] dark:text-[#00bceb] font-medium leading-relaxed whitespace-pre-wrap shadow-inner relative transition-colors">
 {{ $studentAns[$question->id] ?? 'No response provided.' }}
 </div>
 </div>

 {{-- Scoring Input --}}
 <div class="flex items-center gap-4 p-5 bg-[#eefbff] dark:bg-[#00bceb]/10 rounded-2xl border border-[#00bceb]/20 dark:border-[#00bceb]/30 transition-colors">
 <div class="grow">
 <span class="text-sm font-black text-[#005073] dark:text-[#00bceb] transition-colors">Assign Points</span>
 <p class="text-xs text-[#00bceb] dark:text-slate-400 font-bold transition-colors">Score: 1 (Correct) or 0 (Incorrect)</p>
 </div>
 <div class="relative">
 <input type="number" name="manual_points" value="0" min="0" max="1"
 class="w-24 p-4 rounded-xl border-2 border-[#00bceb]/30 dark:border-[#00bceb]/50 focus:border-[#005073] dark:focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/10 bg-white dark:bg-slate-800 text-center text-xl font-black text-[#005073] dark:text-white outline-none transition-all">
 </div>
 </div>
 </div>
 </div>
 @endforeach

 {{-- Final Submission Box --}}
 <div class="bg-slate-50 dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-2xl shadow-slate-200/50 dark:shadow-black/40 border border-slate-200 dark:border-slate-700 relative overflow-hidden transition-colors duration-300">
 {{-- Decorative Element --}}
 <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#00bceb] opacity-10 rounded-full"></div>

 <div class="mb-6 relative z-10 transition-colors">
 <label class="block text-[10px] font-black uppercase tracking-widest text-[#005073] dark:text-[#00bceb] mb-2 ml-1 transition-colors">Overall Feedback</label>
 <textarea name="feedback" rows="3"
 class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl p-4 text-slate-700 dark:text-white outline-none focus:border-[#00bceb] focus:bg-white dark:focus:bg-white/10 transition placeholder-slate-400 dark:placeholder-white/20 font-medium"
 placeholder="Write a professional feedback note here..."></textarea>
 </div>

 <button type="submit" class="w-full bg-[#E2231A] hover:bg-[#E2231A] text-white font-black py-5 rounded-2xl shadow-xl shadow-[#E2231A]/30 dark:shadow-none transition transform hover:scale-[1.02] active:scale-95 uppercase tracking-[0.2em] text-sm relative z-10">
 Finalize & Submit Score
 </button>
 </div>
 </div>
 </form>
</div>

<style>
 input::-webkit-outer-spin-button,
 input::-webkit-inner-spin-button {
 -webkit-appearance: none;
 margin: 0;
 }
</style>
@endsection
