@extends('layouts.app')

@section('content')
@php
 $allStudents = \App\Models\User::where('role', 0)->orderBy('name')->get()->map(function($s) {
 return [
 'id' => $s->id,
 'name' => $s->name,
 'school' => $s->school ?? 'General'
 ];
 });
 $assignedUserIds = isset($exam) ? $exam->assignedUsers->pluck('id')->toArray() : [];
@endphp

<div x-data="examBuilder({{ $allStudents->toJson() }}, {{ json_encode($assignedUserIds) }}, '{{ $exam->exam_mode ?? 'normal' }}', '{{ $exam->duration_mode ?? 'global' }}')" class="max-w-5xl mx-auto py-12 px-4 transition-colors">
 <form action="{{ isset($exam) ? route('admin.exams.update', $exam) : route('admin.exams.store') }}"
 method="POST"
 enctype="multipart/form-data">
 @csrf
 @if(isset($exam)) @method('PUT') @endif

 {{-- Validation Errors --}}
 @if ($errors->any())
 <div class="bg-rose-100 dark:bg-rose-900/30 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 p-4 mb-10 rounded-xl shadow-sm animate-bounce">
 <p class="font-black uppercase text-xs tracking-widest mb-2">Validation Errors Found:</p>
 <ul class="text-sm font-medium">
 @foreach ($errors->all() as $error)
  <li>• {{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 {{-- HEADER: Exam Info --}}
  <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 border-t-4 border-t-[#005073] dark:border-t-[#00bceb] shadow-sm p-6 md:p-10 mb-6 md:mb-10 transition-colors">
  <input type="text" name="exam_title" required
  value="{{ $exam->title ?? '' }}"
  class="text-2xl md:text-4xl font-black text-[#005073] dark:text-[#00bceb] w-full border-b-2 border-slate-100 dark:border-slate-700 bg-transparent focus:border-[#E2231A] dark:focus:border-rose-400 focus:outline-none transition-all mb-4 placeholder-slate-400 dark:placeholder-slate-500"
  placeholder="Enter Exam Title...">

 <input type="text" name="exam_description"
 value="{{ $exam->description ?? '' }}"
 class="text-lg text-slate-500 dark:text-slate-400 w-full border-none bg-transparent focus:ring-0 placeholder-slate-400 dark:placeholder-slate-500 transition-colors"
 placeholder="Add a detailed description for this exam...">

    <div class="mt-4 flex flex-wrap items-center gap-6">
        {{-- Styled Exam Mode Selection --}}
        <div class="flex items-center p-1 bg-slate-100 dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 transition-colors">
            <input type="hidden" name="exam_mode" :value="mode">
            <button type="button" @click="mode = 'normal'" 
                    :class="mode === 'normal' ? 'bg-white dark:bg-slate-800 text-[#005073] dark:text-[#00bceb] shadow-sm ring-1 ring-slate-200 dark:ring-slate-700' : 'text-slate-400 dark:text-slate-500 hover:text-slate-600'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                Normal
            </button>
            <button type="button" @click="mode = 'sequential'" 
                    :class="mode === 'sequential' ? 'bg-[#E2231A] text-white shadow-lg shadow-[#E2231A]/20' : 'text-slate-400 dark:text-slate-500 hover:text-slate-600'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                Sequential
            </button>
        </div>

        {{-- Duration Mode Selection --}}
        <div class="flex items-center p-1 bg-slate-100 dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 transition-colors">
            <input type="hidden" name="duration_mode" :value="durMode">
            <button type="button" @click="durMode = 'global'" 
                    :class="durMode === 'global' ? 'bg-white dark:bg-slate-800 text-[#005073] dark:text-[#00bceb] shadow-sm ring-1 ring-slate-200 dark:ring-slate-700' : 'text-slate-400 dark:text-slate-500 hover:text-slate-600'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Global Time
            </button>
            <button type="button" @click="durMode = 'per_question'" 
                    :class="durMode === 'per_question' ? 'bg-[#005073] dark:bg-[#00bceb] text-white dark:text-slate-900 shadow-lg' : 'text-slate-400 dark:text-slate-500 hover:text-slate-600'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Per-Question
            </button>
        </div>

        {{-- Duration Input (Contextual) --}}
        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 transition-colors">
            <input type="number" name="duration" value="{{ $exam->duration ?? '' }}" min="0.01" step="0.01"
                   class="bg-transparent border-none p-0 w-20 text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 placeholder-slate-400 dark:placeholder-slate-500"
                   placeholder="Value">
            <div class="relative h-4 w-24 overflow-hidden">
                <span class="absolute inset-0 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-all duration-300"
                      x-show="durMode === 'global'"
                      x-transition:enter="transition ease-out duration-300 transform"
                      x-transition:enter-start="translate-y-4 opacity-0"
                      x-transition:enter-end="translate-y-0 opacity-100"
                      x-transition:leave="transition ease-in duration-300 transform"
                      x-transition:leave-start="translate-y-0 opacity-100"
                      x-transition:leave-end="-translate-y-4 opacity-0">
                    Minutes
                </span>
                <span class="absolute inset-0 text-[10px] uppercase tracking-widest transition-all duration-300 font-black italic text-[#E2231A] dark:text-rose-500"
                      x-show="durMode === 'per_question'"
                      x-transition:enter="transition ease-out duration-300 transform"
                      x-transition:enter-start="translate-y-4 opacity-0"
                      x-transition:enter-end="translate-y-0 opacity-100"
                      x-transition:leave="transition ease-in duration-300 transform"
                      x-transition:leave-start="translate-y-0 opacity-100"
                      x-transition:leave-end="-translate-y-4 opacity-0">
                    Minutes/Question
                </span>
            </div>
        </div>

 {{-- Randomize Toggle --}}
 <label class="flex items-center gap-3 cursor-pointer group bg-slate-50 dark:bg-slate-900 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 transition-colors">
 <div class="relative w-10 h-5 flex items-center">
 <input type="checkbox" name="randomize_questions" value="1" class="sr-only peer">
 <div class="w-full h-full bg-slate-200 dark:bg-slate-700 rounded-full peer peer-checked:bg-[#00bceb] transition-colors"></div>
 <div class="absolute left-1 w-3 h-3 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
 </div>
 <span class="text-xs font-bold text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-200 uppercase tracking-widest transition-colors">Randomize Order</span>
 </label>
 </div>

 {{-- User Assignment Section (DYNAMIC) --}}
 <div class="mt-8 p-6 md:p-8 bg-[#eefbff]/30 dark:bg-slate-900/50 rounded-3xl md:rounded-4xl border-2 border-dashed border-[#00bceb]/20 dark:border-[#005073]/40 transition-colors">
 <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
 <div>
 <label class="text-[10px] font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-[0.2em] flex items-center gap-2 mb-1 transition-colors">
 <div class="p-1.5 bg-[#005073] dark:bg-[#00bceb] text-white dark:text-slate-900 rounded-lg shadow-md transition-colors">
 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
 </div>
 Target Audience
 </label>
 <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest italic transition-colors">Optional: Leave empty for all students</span>
 </div>

 {{-- SEARCH & SORT CONTROLS --}}
 <div class="flex flex-col sm:flex-row sm:items-center gap-3">
 <div class="relative w-full sm:w-auto">
 <input type="text" x-model="studentSearch" placeholder="Search..." 
 class="pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/5 transition-all w-full md:w-60 placeholder-slate-400 dark:placeholder-slate-500">
 <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
 </div>
 
 <div class="flex bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-1 transition-colors w-fit">
 <button type="button" @click="studentSort = 'name_asc'" 
 :class="studentSort === 'name_asc' ? 'bg-[#005073] dark:bg-[#00bceb] text-white dark:text-slate-900 border-[#005073] dark:border-[#00bceb]' : 'text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'"
 class="px-3 py-1 rounded-lg text-[9px] font-black uppercase transition-all">A-Z</button>
 <button type="button" @click="studentSort = 'school'" 
 :class="studentSort === 'school' ? 'bg-[#005073] dark:bg-[#00bceb] text-white dark:text-slate-900 border-[#005073] dark:border-[#00bceb]' : 'text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'"
 class="px-3 py-1 rounded-lg text-[9px] font-black uppercase transition-all">School</button>
 </div>
 </div>
 </div>
 
 {{-- SELECTABLE LIST --}}
 <div class="bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-inner transition-colors">
 <div class="max-h-60 overflow-y-auto custom-scrollbar p-2 grid grid-cols-1 md:grid-cols-2 gap-2">
 <template x-for="student in filteredStudents" :key="student.id">
 <div @click="toggleStudent(student.id)" 
 :class="selectedStudentIds.includes(student.id) ? 'bg-[#005073] dark:bg-[#00bceb] border-[#005073] dark:border-[#00bceb] text-white dark:text-slate-900' : 'bg-slate-50 dark:bg-slate-800 border-transparent text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'"
 class="flex items-center justify-between px-4 py-3 rounded-2xl border-2 cursor-pointer transition-all group">
 <div class="flex flex-col">
 <span class="text-xs font-black" x-text="student.name"></span>
 <span :class="selectedStudentIds.includes(student.id) ? 'text-white dark:text-slate-900' : 'text-slate-500 dark:text-slate-400'" 
 class="text-[9px] font-bold uppercase tracking-widest transition-colors" x-text="student.school"></span>
 </div>
 <div :class="selectedStudentIds.includes(student.id) ? 'bg-white dark:bg-slate-900 text-[#005073] dark:text-[#00bceb]' : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-600'"
 class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
 <svg x-show="selectedStudentIds.includes(student.id)" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
 </div>
 </div>
 </template>
 <div x-show="filteredStudents.length === 0" class="col-span-full py-10 text-center text-slate-300 dark:text-slate-700 italic text-xs font-bold transition-colors">
 No students found matching your criteria.
 </div>
 </div>
 </div>

 {{-- HIDDEN INPUTS FOR FORM SUBMISSION --}}
 <template x-for="id in selectedStudentIds">
 <input type="hidden" name="assigned_users[]" :value="id">
 </template>
 
 <div class="mt-6 flex items-center justify-between px-2">
 <div class="flex items-center gap-2">
 <span class="text-[10px] font-black text-[#00bceb]" x-text="selectedStudentIds.length"></span>
 <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Students Targeted</span>
 </div>
 <button type="button" x-show="selectedStudentIds.length > 0" @click="selectedStudentIds = []" 
 class="text-[9px] font-black text-[#E2231A] uppercase tracking-widest hover:underline">Clear Selection</button>
 </div>
 </div>
 </div>

 {{-- Question Loop --}}
 <template x-for="(question, index) in questions" :key="question.id">
  <div class="bg-white dark:bg-slate-800 rounded-3xl md:rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-slate-700 mb-6 md:mb-8 overflow-hidden transition-all hover:shadow-xl dark:hover:shadow-none duration-300 ease-in-out"
 x-transition:enter="transition ease-out duration-300 transform"
 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
 x-transition:leave="transition ease-in duration-200 transform"
 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
 x-transition:leave-end="opacity-0 -translate-y-4 scale-95">

 <div class="p-6 md:p-8 pb-4">
 {{-- Row 1: Number, Title, Type --}}
 <div class="grid grid-cols-1 md:grid-cols-12 gap-5 md:gap-6 items-end">
 <div class="md:col-span-8">
 <div class="flex items-center gap-3 mb-3">
 <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#E2231A] text-white text-xs font-black shadow-lg shadow-[#E2231A]/20 transition-colors" x-text="index + 1"></span>
 <span class="text-xs font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-[0.2em] transition-colors">Question Text</span>
 </div>
 <input type="text" :name="`questions[${index}][text]`" required x-model="question.text"
 class="w-full bg-slate-100 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 rounded-2xl p-4 text-lg font-bold text-slate-800 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:border-[#E2231A] dark:focus:border-[#00bceb] focus:ring-0 transition-all placeholder-slate-400 dark:placeholder-slate-500"
 placeholder="Type your question here...">
 </div>

 <div class="md:col-span-4">
 <div class="mb-3">
 <span class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] transition-colors">Question Type</span>
 </div>
 <div class="relative group">
 <select x-model="question.type" :name="`questions[${index}][type]`"
 class="w-full bg-[#005073] dark:bg-[#00bceb] border-2 border-[#005073] dark:border-[#00bceb] rounded-2xl p-4 font-black text-white dark:text-slate-900 focus:bg-[#E2231A] dark:focus:bg-[#008ebb] focus:border-[#E2231A] dark:focus:border-[#008ebb] focus:ring-0 cursor-pointer appearance-none transition-all pr-12 shadow-lg dark:shadow-none">
 <option value="option" class="text-slate-900 bg-white dark:bg-slate-800 dark:text-white">Multiple Choice</option>
 <option value="connect" class="text-slate-900 bg-white dark:bg-slate-800 dark:text-white">Matching Pairs</option>
 <option value="essay" class="text-slate-900 bg-white dark:bg-slate-800 dark:text-white">Essay / Manual</option>
 </select>
 <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-white/70 dark:text-slate-900/70">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
 </div>
 </div>
 </div>
 </div>

  {{-- Row 2: Context Image & Duration --}}
  <div class="mt-6 flex flex-wrap items-center gap-4" x-data="{ preview: null }">
    <label class="inline-flex items-center gap-3 px-4 py-2 bg-slate-100 dark:bg-slate-900 hover:bg-slate-200 dark:hover:bg-slate-700 text-[#005073] dark:text-[#00bceb] rounded-xl text-[10px] font-black cursor-pointer transition-all border border-slate-200 dark:border-slate-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
        <span x-text="preview || (question.context_image_path) ? 'CHANGE IMAGE' : 'ADD CONTEXT IMAGE'"></span>
        <input type="file" :name="`questions[${index}][context_image]`" class="hidden"
               @change="preview = URL.createObjectURL($event.target.files[0])">
        <input type="hidden" :name="`questions[${index}][context_image_path]`" x-model="question.context_image_path">
    </label>

    <div class="flex items-center gap-3 px-4 py-2 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700"
         x-show="durMode === 'per_question'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <input type="number" :name="`questions[${index}][duration]`" x-model="question.duration" placeholder="Default" step="0.01" min="0.01"
               class="bg-transparent border-none p-0 w-16 text-[10px] font-black uppercase text-slate-600 dark:text-slate-400 focus:ring-0 placeholder-slate-300 dark:placeholder-slate-700 transition-colors">
        <span class="text-[8px] font-black text-slate-300 dark:text-slate-700 uppercase tracking-widest">Min/Quest</span>
    </div>

    <div class="relative" x-show="preview || question.context_image_path">
        <img :src="preview || '/storage/' + question.context_image_path"
             class="h-20 w-32 rounded-xl border-2 border-[#005073] dark:border-[#00bceb] shadow-md object-cover bg-slate-50 dark:bg-slate-900 transition-colors">
        <button type="button" @click="preview = null; question.context_image_path = null"
                class="absolute -top-2 -right-2 bg-[#E2231A] text-white rounded-full p-1 shadow-lg hover:scale-110 transition">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3"></path></svg>
        </button>
    </div>
  </div>
 </div>

 {{-- Dynamic Answer Section --}}
 <div class="px-8 pb-8 pt-2">
 <div class="bg-slate-50 dark:bg-slate-900/50 rounded-4xl border-2 border-slate-100 dark:border-slate-700/50 p-6 shadow-inner transition-colors">

 {{-- Multiple Choice --}}
 <div x-show="question.type === 'option'" class="space-y-3">
 <template x-for="(opt, optIndex) in question.options" :key="optIndex">
 <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-3 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm group hover:border-[#00bceb]/50 transition-colors" x-data="{ optPreview: null }">
 
 {{-- OPTION KEY BADGE --}}
 <label class="relative flex flex-col items-center justify-center w-12 h-12 rounded-xl cursor-pointer transition-all duration-300"
 :class="opt.is_correct ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 ring-2 ring-emerald-500 ring-offset-2 dark:ring-offset-slate-800' : 'bg-slate-100 dark:bg-slate-900 text-slate-300 dark:text-slate-700 hover:bg-slate-200 dark:hover:bg-slate-800 hover:text-slate-400 dark:hover:text-slate-600'">
 <input type="checkbox" :name="`questions[${index}][options][${optIndex}][is_correct]`" value="1"
 :checked="opt.is_correct"
 x-model="opt.is_correct"
 class="absolute opacity-0 w-full h-full cursor-pointer">
 
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
 </label>

 <div class="grow">
 <input type="text" :name="`questions[${index}][options][${optIndex}][text]`" x-model="opt.text"
 class="w-full border-none bg-transparent focus:ring-0 p-0 text-sm font-black text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 transition-colors"
 placeholder="Option content...">
 </div>

 <div class="flex items-center gap-3 pr-2">
 <template x-if="optPreview || opt.image">
 <div class="relative">
 <img :src="optPreview || '/storage/' + opt.image" class="h-10 w-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700 transition-colors">
 </div>
 </template>

 <label class="p-2 text-slate-400 dark:text-slate-600 hover:text-[#005073] dark:hover:text-[#00bceb] cursor-pointer transition-all bg-slate-50 dark:bg-slate-900 rounded-lg">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
 <input type="file" :name="`questions[${index}][options][${optIndex}][file]`" class="hidden"
 @change="optPreview = URL.createObjectURL($event.target.files[0])">
 <input type="hidden" :name="`questions[${index}][options][${optIndex}][image]`" x-model="opt.image">
 </label>

 <button type="button" @click="removeOption(index, optIndex)" class="p-2 text-slate-300 hover:text-[#E2231A] transition-colors">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
 </button>
 </div>
 </div>
 </template>

 <button type="button" @click="addOption(index)" class="flex items-center gap-2 text-[10px] font-black text-[#E2231A] uppercase tracking-[0.2em] pt-2 ml-4 hover:text-[#005073] transition-colors">
 <span class="bg-[#E2231A] text-white rounded-md w-5 h-5 flex items-center justify-center shadow-sm text-lg leading-none pb-0.5 transition-colors">+</span>
 Add Choice
 </button>
 </div>

 {{-- Connect Pairs --}}
 <div x-show="question.type === 'connect'" class="space-y-3">
 <template x-for="(pair, pairIndex) in question.pairs" :key="pairIndex">
 <div class="flex items-center gap-4">
 <input type="text" :name="`questions[${index}][pairs][${pairIndex}][left]`" x-model="pair.left"
 class="flex-1 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm font-bold shadow-sm focus:border-[#005073] dark:focus:border-[#00bceb] dark:text-slate-200 transition-colors" placeholder="Key Side">
 <div class="text-[#E2231A] dark:text-rose-400 animate-pulse transition-colors">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
 </div>
 <input type="text" :name="`questions[${index}][pairs][${pairIndex}][right]`" x-model="pair.right"
 class="flex-1 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm font-bold shadow-sm focus:border-[#005073] dark:focus:border-[#00bceb] dark:text-slate-200 transition-colors" placeholder="Value Side">
 <button type="button" @click="removePair(index, pairIndex)" class="text-slate-300 hover:text-[#E2231A] p-1">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
 </button>
 </div>
 </template>
 <button type="button" @click="addPair(index)" class="text-[10px] font-black text-[#005073] uppercase tracking-[0.2em] mt-2 hover:text-[#E2231A] transition-colors">+ Add New Pair</button>
 </div>

 {{-- Essay --}}
 <div x-show="question.type === 'essay'" class="text-center py-6 bg-white/50 dark:bg-slate-800/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
 <p class="text-slate-400 dark:text-slate-500 text-xs font-bold mb-4 transition-colors">Instructor manual grading required.</p>
 <label class="inline-flex items-center gap-3 px-6 py-3 bg-[#005073] dark:bg-[#00bceb] text-white dark:text-slate-900 rounded-xl shadow-lg dark:shadow-none cursor-pointer hover:bg-[#E2231A] dark:hover:bg-[#008ebb] transition-all">
 <input type="checkbox" :name="`questions[${index}][essay_allow_attachment]`"
 :checked="question.answer_details && question.answer_details.allow_attachments"
 class="w-4 h-4 rounded border-none bg-white/20 dark:bg-slate-900/20">
 <span class="text-[10px] font-black uppercase tracking-widest">Enable file upload for students</span>
 </label>
 </div>
 </div>
 </div>

 {{-- Card Actions --}}
 <div class="bg-slate-50 dark:bg-slate-900 px-8 py-4 flex justify-between items-center border-t border-slate-100 dark:border-slate-800/50 transition-colors duration-300">
 <div class="flex items-center gap-4">
  <label class="flex items-center gap-3 cursor-pointer group" x-show="durMode !== 'per_question' && mode !== 'sequential'">
  <div class="relative w-10 h-5 flex items-center">
  <input type="checkbox" :name="`questions[${index}][required]`" :checked="question.is_required" class="sr-only peer">
  <div class="w-full h-full bg-[#002e44] dark:bg-slate-800 rounded-full peer peer-checked:bg-[#E2231A] dark:peer-checked:bg-rose-500 transition-colors"></div>
  <div class="absolute left-1 w-3 h-3 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
  </div>
  <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 group-hover:text-[#005073] dark:group-hover:text-white uppercase tracking-widest transition-colors">Required</span>
  </label>

 {{-- Reorder Buttons --}}
 <div class="flex items-center bg-white dark:bg-slate-800 rounded-lg p-1 border border-slate-200 dark:border-slate-700 transition-colors">
 <button type="button" @click="moveUp(index)" :disabled="index === 0"
 class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-[#005073] dark:hover:text-[#00bceb] hover:bg-slate-100 dark:hover:bg-slate-700 rounded-md transition disabled:opacity-30 disabled:hover:bg-transparent">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
 </button>
 <button type="button" @click="moveDown(index)" :disabled="index === questions.length - 1"
 class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-[#005073] dark:hover:text-[#00bceb] hover:bg-slate-100 dark:hover:bg-slate-700 rounded-md transition disabled:opacity-30 disabled:hover:bg-transparent">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
 </button>
 </div>
 </div>

 <button type="button" @click="removeQuestion(index)" class="flex items-center gap-2 text-[10px] font-black text-rose-500 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 uppercase tracking-widest transition-colors">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
 Discard
 </button>
 </div>
 </div>
 </template>

 {{-- Add Question Button --}}
 {{-- Add Question Button --}}
 <div class="mb-12">
 <button type="button" @click="addQuestion()"
 class="w-full py-12 bg-white dark:bg-slate-800 border-4 border-dashed border-slate-200 dark:border-slate-700 rounded-[3rem] text-slate-300 dark:text-slate-600 font-black uppercase tracking-[0.4em] hover:bg-[#f8fafc] dark:hover:bg-slate-800/50 hover:border-[#E2231A] dark:hover:border-[#00bceb] hover:text-[#E2231A] dark:hover:text-[#00bceb] transition-all shadow-sm group">
 <span class="inline-block group-hover:animate-bounce">+ Add Question Node</span>
 </button>
 </div>

 {{-- Action Bar (Static) --}}
 <div class="w-full">
 <div class="bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-800 border-b-8 border-b-[#E2231A] dark:border-b-[#00bceb] rounded-full p-4 flex items-center justify-between shadow-2xl transition-colors duration-300">
 <div class="pl-6 text-slate-400 dark:text-slate-500">
 <span class="block text-[8px] font-black uppercase tracking-widest leading-none mb-1">Total nodes</span>
 <span class="text-xl font-black leading-none text-slate-800 dark:text-white" x-text="questions.length"></span>
 </div>
 <button type="submit"
 class="bg-[#E2231A] dark:bg-[#00bceb] hover:bg-[#be1e16] dark:hover:bg-[#008ebb] text-white dark:text-slate-900 px-10 py-4 rounded-full font-black uppercase tracking-widest text-xs shadow-xl transition-all active:scale-95">
 {{ isset($exam) ? 'Update Records' : 'Save & Publish' }}
 </button>
 </div>
 </div>
 </form>
</div>

{{-- Script & Style tetap sama --}}
<script>
function examBuilder(allStudents, assignedIds, initialMode, initialDurMode) {
 let initialQuestions = @json(isset($exam) ? $exam->questions : []);
 if (initialQuestions.length > 0) {
 initialQuestions = initialQuestions.map(q => ({
  id: q.id,
  text: q.question_text,
  type: q.type,
  is_required: q.is_required,
  duration: q.duration ? q.duration : null,
  context_image_path: q.context_image_path,
  options: (q.answer_details && q.answer_details.options) ? q.answer_details.options : [{text: ''}, {text: ''}],
  pairs: (q.answer_details && q.answer_details.pairs) ? q.answer_details.pairs : [{left: '', right: ''}],
  answer_details: q.answer_details
 }));
 } else {
  initialQuestions = [{
  id: Date.now(),
  type: 'option',
  text: '',
  options: [{text: '', is_correct: false, image: null}, {text: '', is_correct: false, image: null}],
  pairs: [{left: '', right: ''}],
  is_required: true,
  duration: null,
  context_image_path: null
  }];
 }

 return {
 questions: initialQuestions,
 allStudents: allStudents,
 selectedStudentIds: assignedIds,
 mode: initialMode || 'normal',
 durMode: initialDurMode || 'global',
 studentSearch: '',
 studentSort: 'name_asc', // 'name_asc', 'school'
 
 get filteredStudents() {
 let filtered = this.allStudents.filter(s => 
 s.name.toLowerCase().includes(this.studentSearch.toLowerCase()) ||
 s.school.toLowerCase().includes(this.studentSearch.toLowerCase())
 );
 
 if (this.studentSort === 'name_asc') {
 filtered.sort((a, b) => a.name.localeCompare(b.name));
 } else if (this.studentSort === 'school') {
 filtered.sort((a, b) => a.school.localeCompare(b.school) || a.name.localeCompare(b.name));
 }
 
 return filtered;
 },

 toggleStudent(id) {
 if (this.selectedStudentIds.includes(id)) {
 this.selectedStudentIds = this.selectedStudentIds.filter(sid => sid !== id);
 } else {
 this.selectedStudentIds.push(id);
 }
 },

 addQuestion() {
 this.questions.push({
  id: Date.now(),
  type: 'option',
  text: '',
  options: [{text: '', is_correct: false, image: null}, {text: '', is_correct: false, image: null}],
  pairs: [{left: '', right: ''}],
  is_required: true,
  duration: null,
  context_image_path: null
 });
 setTimeout(() => window.scrollTo({ top: document.body.scrollHeight + 200, behavior: 'smooth' }), 100);
 },
 removeQuestion(index) { if (this.questions.length > 1) this.questions.splice(index, 1); },
 moveUp(index) {
 if (index > 0) {
 const temp = this.questions[index];
 this.questions[index] = this.questions[index - 1];
 this.questions[index - 1] = temp;
 }
 },
 moveDown(index) {
 if (index < this.questions.length - 1) {
 const temp = this.questions[index];
 this.questions[index] = this.questions[index + 1];
 this.questions[index + 1] = temp;
 }
 },
 addOption(qIndex) { this.questions[qIndex].options.push({text: '', is_correct: false, image: null}); },
 removeOption(qIndex, oIndex) { this.questions[qIndex].options.splice(oIndex, 1); },
 addPair(qIndex) { this.questions[qIndex].pairs.push({left: '', right: ''}); },
 removePair(qIndex, pIndex) { this.questions[qIndex].pairs.splice(pIndex, 1); }
 }
}
</script>

<style>
 input:focus { outline: none !important; }
 input::placeholder { color: #64748b; font-weight: 700; opacity: 1; }
 .dark input::placeholder { color: #94a3b8; opacity: 1; }
 [x-cloak] { display: none !important; }
 .custom-scrollbar::-webkit-scrollbar { width: 6px; }
 .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
 .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
 .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection
