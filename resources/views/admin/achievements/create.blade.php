@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 transition-colors">
 <div class="mb-10 transition-colors">
 <a href="{{ route('admin.achievements.index') }}" class="text-xs font-black text-slate-400 dark:text-slate-500 hover:text-[#005073] dark:hover:text-[#00bceb] uppercase tracking-widest mb-4 inline-flex items-center gap-2 transition">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
 Back to List
 </a>
 <h1 class="text-4xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight transition-colors">Create Achievement</h1>
 </div>

 <form action="{{ route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-100 dark:border-slate-800 shadow-xl dark:shadow-none space-y-8 transition-colors">
 @csrf
 
 <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
 <div class="md:col-span-2 transition-colors">
 <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Achievement Title</label>
 <input type="text" name="title" required
 class="w-full px-6 py-4 rounded-2xl border-2 border-slate-50 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] outline-none transition font-bold text-slate-700 dark:text-slate-200"
 placeholder="e.g. Winner of Regional NetRider's 2025">
 </div>

 <div class="transition-colors">
 <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Event Date</label>
 <input type="date" name="date"
 class="w-full px-6 py-4 rounded-2xl border-2 border-slate-50 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] outline-none transition font-bold text-slate-700 dark:text-slate-200">
 </div>

 <div class="transition-colors">
 <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Cover Image</label>
 <input type="file" name="image"
 class="w-full px-6 py-3.5 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 transition text-sm text-slate-500 dark:text-slate-400 font-bold">
 </div>

 <div class="md:col-span-2 transition-colors">
 <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Content / Description</label>
 <textarea name="content" rows="6" required
 class="w-full px-6 py-4 rounded-2xl border-2 border-slate-50 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] outline-none transition font-bold text-slate-700 dark:text-slate-200"
 placeholder="Describe the achievement in detail..."></textarea>
 </div>

 <div class="flex items-center gap-3 transition-colors">
 <input type="checkbox" name="is_active" value="1" id="is_active" checked class="w-5 h-5 rounded-lg text-[#005073] dark:text-[#00bceb] transition">
 <label for="is_active" class="text-sm font-bold text-slate-600 dark:text-slate-300 transition-colors">Show on Home Page</label>
 </div>
 </div>

 <div class="pt-4 flex justify-end transition-colors">
 <button type="submit" class="px-12 py-5 bg-[#005073] dark:bg-[#00bceb] hover:bg-[#003e5c] dark:hover:bg-[#009aba] text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-2xl shadow-blue-900/20 dark:shadow-none transition transform hover:-translate-y-1 active:scale-95">
 Save Achievement
 </button>
 </div>
 </form>
</div>
@endsection
