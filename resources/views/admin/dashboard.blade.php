@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
 <div>
 <h1 class="text-3xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight transition-colors">Admin Dashboard</h1>
 <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">Control panel for exams and student access.</p>
 </div>
 <div class="flex gap-3">
 <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[#005073] dark:text-[#00bceb] px-6 py-3 rounded-2xl font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition flex items-center gap-2">
 👥 Manage Users
 </a>
 <a href="{{ route('admin.soal') }}" class="bg-[#E2231A] hover:bg-[#be1e16] text-white px-8 py-3 rounded-2xl font-black shadow-lg shadow-[#E2231A]/20 dark:shadow-none transition transform hover:scale-105 active:scale-95 flex items-center gap-2">
 <span class="text-xl">+</span> New Exam
 </a>
 </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
 <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm border-t-4 border-t-[#005073] dark:border-t-[#00bceb] transition-colors">
 <div class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1 transition-colors">Total Exams</div>
 <h3 class="text-4xl font-black text-[#005073] dark:text-white transition-colors">{{ $totalExamsCount }}</h3>
 </div>
 <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm border-t-4 border-t-emerald-500 transition-colors">
 <div class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1 transition-colors">Live Exams</div>
 <h3 class="text-4xl font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $liveExamsCount }}</h3>
 </div>
 <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm border-t-4 border-t-[#E2231A] transition-colors">
 <div class="text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1 transition-colors">Total Submissions</div>
 <h3 class="text-4xl font-black text-[#E2231A] dark:text-rose-400 transition-colors">{{ $totalSubmissions }}</h3>
 </div>
</div>

<div class="mb-6 bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
 <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col md:flex-row gap-4">
 <div class="flex-1 relative">
 <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
 <input type="text" name="search" value="{{ request('search') }}"
 class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#005073] dark:focus:border-[#00bceb] focus:ring-0 transition-all placeholder-slate-400 dark:placeholder-slate-600"
 placeholder="Search exams by title...">
 </div>
 <div class="w-full md:w-48">
 <select name="status" onchange="this.form.submit()"
 class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#005073] dark:focus:border-[#00bceb] cursor-pointer transition-colors">
 <option value="all">All Status</option>
 <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
 <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
 </select>
 </div>
 <button type="submit" class="bg-[#005073] dark:bg-[#00bceb] hover:bg-[#003e5c] dark:hover:bg-[#008ebb] text-white px-6 py-3 rounded-xl font-black shadow-lg dark:shadow-none transition-all">
 FILTER
 </button>
 @if(request('search') || request('status') && request('status') != 'all')
 <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center px-4 py-3 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-xl font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Clear</a>
 @endif
 </form>
</div>

{{-- Exam List Table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-colors" x-data="{ deleteModalOpen: false, deleteAction: '' }">
 <table class="w-full text-left border-collapse">
 <thead>
 <tr class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/30 transition-colors">
 <th class="px-6 py-4 text-[#005073] dark:text-[#00bceb]">Title & Description</th>
 <th class="px-6 py-4 text-[#005073] dark:text-[#00bceb] text-center">Current Status</th>
 <th class="px-6 py-4 text-right text-[#005073] dark:text-[#00bceb]">Actions</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-slate-100 dark:divide-slate-700 transition-colors">
 @forelse($exams as $exam)
 <tr class="hover:bg-[#eefbff]/50 dark:hover:bg-slate-700/50 group">
 <td class="px-6 py-5">
 <div class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-[#005073] dark:group-hover:text-[#00bceb] transition-colors">{{ $exam->title }}</div>
 <div class="text-xs text-slate-400 dark:text-slate-500 mt-1 transition-colors">{{ Str::limit($exam->description, 60) }}</div>
 
 {{-- Completion & Essay Status Indicators --}}
 <div class="flex items-center gap-2 mt-3">
 @php
 $status = $exam->getCompletionStatus();
 $pendingEssays = $exam->submissions()->where('status', 'pending')->count();
 @endphp
 
 @if($status['total'] > 0)
 <div class="flex flex-col gap-1.5 min-w-[120px]">
 <div class="flex items-center justify-between">
 <span class="px-2 py-1 bg-[#049FD9]/10 dark:bg-[#049FD9]/20 text-[#049FD9] dark:text-[#00bceb] rounded-lg text-[9px] font-black uppercase tracking-wider border border-[#049FD9]/20 dark:border-[#049FD9]/40 transition-colors">
 {{ $status['completed'] }}/{{ $status['total'] }} Completed
 </span>
 <span class="text-[9px] font-black text-[#049FD9] dark:text-[#00bceb] transition-colors">{{ $status['percentage'] }}%</span>
 </div>
 <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden border border-slate-200/50 dark:border-slate-700/50 transition-colors">
 <div class="h-full bg-[#049FD9] transition-all duration-500" style="width: {{ $status['percentage'] }}%"></div>
 </div>
 </div>
 @endif
 
 @if($pendingEssays > 0)
 <span class="px-2 py-1 bg-[#FF9E18]/10 text-[#FF9E18] rounded-lg text-[9px] font-black uppercase tracking-wider border border-[#FF9E18]/20 animate-pulse self-start mt-0.5">
 {{ $pendingEssays }} Essay{{ $pendingEssays > 1 ? 's' : '' }} Pending
 </span>
 @endif
 </div>
 </td>

 {{-- TOGGLE STATUS COLUMN --}}
 <td class="px-6 py-5 text-center" x-data="{ 
 status: '{{ $exam->status }}', 
 isLoading: false,
 async toggle() {
 this.isLoading = true;
 try {
 const res = await fetch('{{ route('admin.exams.toggle-status', $exam) }}', {
 method: 'PATCH',
 headers: {
 'Content-Type': 'application/json',
 'X-CSRF-TOKEN': '{{ csrf_token() }}',
 'Accept': 'application/json'
 }
 });
 const data = await res.json();
 if (data.success) {
 this.status = data.status;
 }
 } catch (e) {
 console.error('Toggle failed', e);
 } finally {
 this.isLoading = false;
 }
 } 
 }">
 <button @click="toggle()" :disabled="isLoading"
 class="group relative inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border"
 :class="status === 'active' 
 ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/40' 
 : 'bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-600 border-slate-200 dark:border-slate-800 hover:bg-slate-200 dark:hover:bg-slate-800'">
 
 <span class="relative flex h-2 w-2">
 <span x-show="status === 'active'" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
 <span class="relative inline-flex rounded-full h-2 w-2"
 :class="status === 'active' ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700'"></span>
 </span>

 <span x-text="isLoading ? '...' : (status === 'active' ? 'Activated' : 'Deactivated')"></span>
 </button>
 </td>

 <td class="px-6 py-5 text-right">
 <div class="flex justify-end items-center gap-2">
 {{-- SPECS / ANALYTICS BUTTON --}}
 <a href="{{ route('admin.specs', $exam) }}"
 class="p-2.5 bg-slate-50 dark:bg-slate-900 hover:bg-[#005073] dark:hover:bg-[#00bceb] text-slate-500 dark:text-slate-400 hover:text-white rounded-xl transition-all border border-slate-100 dark:border-slate-800"
 title="View Specs & Analytics">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 </a>

 {{-- VIEW SUBMISSIONS --}}
 <a href="{{ route('submissions.index', $exam) }}"
 class="relative p-2.5 bg-slate-50 dark:bg-slate-900 hover:bg-[#005073] dark:hover:bg-[#00bceb] text-slate-500 dark:text-slate-400 hover:text-white rounded-xl transition-all border border-slate-100 dark:border-slate-800"
 title="View Submissions">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
 @php $pendingCount = $exam->submissions->where('status', 'pending')->count(); @endphp
 @if($pendingCount > 0)
 <span class="absolute -top-1 -right-1 flex h-3 w-3">
 <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#E2231A] opacity-75"></span>
 <span class="relative inline-flex rounded-full h-3 w-3 bg-[#E2231A]"></span>
 </span>
 @endif
 </a>

 {{-- EDIT BUTTON --}}
 <a href="{{ route('admin.exams.edit', $exam) }}"
 class="p-2.5 bg-slate-50 dark:bg-slate-900 hover:bg-[#00bceb] text-slate-500 dark:text-slate-400 hover:text-white rounded-xl transition-all border border-slate-100 dark:border-slate-800"
 title="Edit Exam">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
 </a>

 {{-- SOFT DELETE BUTTON (Opens Modal) --}}
 <button type="button" @click="deleteModalOpen = true; deleteAction = '{{ route('admin.exams.destroy', $exam) }}'"
 class="p-2.5 bg-slate-50 dark:bg-slate-900 hover:bg-[#E2231A] text-slate-500 dark:text-slate-400 hover:text-white rounded-xl transition-all border border-slate-100 dark:border-slate-800" title="Delete Exam">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
 </button>
 </div>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="3" class="px-6 py-20 text-center text-slate-400 italic font-medium">
 <div class="flex flex-col items-center gap-2">
 <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 <span>No exams found matching your criteria.</span>
 </div>
 </td>
 </tr>
 @endforelse
</tbody>
 </table>
 
 {{-- Pagination Links --}}
 @if($exams->hasPages())
 <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 transition-colors">
 {{ $exams->links() }}
 </div>
 @endif

 {{-- DELETE MODAL --}}
 <div x-show="deleteModalOpen" class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm transition-colors" x-cloak
 x-transition:enter="transition ease-out duration-300"
 x-transition:enter-start="opacity-0"
 x-transition:enter-end="opacity-100"
 x-transition:leave="transition ease-in duration-200"
 x-transition:leave-start="opacity-100"
 x-transition:leave-end="opacity-0">
 <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 transform transition-all border border-white/20 dark:border-slate-700"
 @click.away="deleteModalOpen = false"
 x-transition:enter="transition ease-out duration-300"
 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
 x-transition:leave="transition ease-in duration-200"
 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
 x-transition:leave-end="opacity-0 scale-90 translate-y-4">
 
 <div class="text-center mb-6">
 <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/30 text-[#E2231A] dark:text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4 transition-colors">
 <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
 </div>
 <h3 class="text-xl font-black text-slate-800 dark:text-white mb-2 transition-colors">Delete this Exam?</h3>
 <p class="text-sm text-slate-500 dark:text-slate-400 transition-colors">This action cannot be undone. All student submissions for this exam will also be deleted.</p>
 </div>

 <div class="flex gap-3">
 <button @click="deleteModalOpen = false" class="flex-1 py-3 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Cancel</button>
 <form :action="deleteAction" method="POST" class="flex-1">
 @csrf
 @method('DELETE')
 <button type="submit" class="w-full py-3 bg-[#E2231A] text-white rounded-xl font-bold hover:bg-rose-700 transition shadow-lg shadow-rose-200">Yes, Delete</button>
 </form>
 </div>
 </div>
 </div>
</div>
@endsection
