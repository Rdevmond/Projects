@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
 {{-- Header --}}
 <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
 <div class="flex-1">
 <a href="{{ route('admin.dashboard') }}" class="text-[#00bceb] font-bold text-sm hover:text-[#005073] dark:hover:text-white transition flex items-center gap-2">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
 Back to Dashboard
 </a>
 <h1 class="text-3xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight mt-2 transition-colors">Exam Submissions</h1>
 <p class="text-slate-500 dark:text-slate-400 font-medium italic transition-colors leading-tight">{{ $exam->title }}</p>
 </div>
 
 {{-- Search Bar --}}
 <form action="{{ route('submissions.index', $exam) }}" method="GET" class="relative w-full lg:w-80">
     <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or school..."
         class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-800 rounded-2xl text-xs focus:ring-4 focus:ring-[#00bceb]/10 focus:border-[#00bceb]/50 transition shadow-sm font-bold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500">
     <div class="absolute left-4 top-1/2 -translate-y-1/2 text-[#00bceb]/40">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
     </div>
 </form>

 <div class="text-left md:text-right bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors shrink-0">
 <span class="text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 block tracking-widest mb-1 transition-colors">Total Entries</span>
 <span class="text-2xl font-black text-[#005073] dark:text-white transition-colors">{{ $submissions->total() }}</span>
 </div>
 </div>

    {{-- Submissions Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-black/40 overflow-hidden transition-colors">
        {{-- DESKTOP VIEW --}}
        <div class="hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">
                        <th class="px-6 py-5 text-[#005073] dark:text-[#00bceb] transition-colors">Student</th>
                        <th class="px-6 py-5 text-[#005073] dark:text-[#00bceb] transition-colors">Score Performance</th>
                        <th class="px-6 py-5 text-center text-[#005073] dark:text-[#00bceb] transition-colors">Time Spent</th>
                        <th class="px-6 py-5 text-center text-[#005073] dark:text-[#00bceb] transition-colors">Status</th>
                        <th class="px-6 py-5 text-right text-[#005073] dark:text-[#00bceb] transition-colors">Submitted At</th>
                        <th class="px-6 py-5 text-right text-[#005073] dark:text-[#00bceb] transition-colors">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
                    @forelse($submissions as $sub)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 group">
                        <td class="px-6 py-5">
                            <div class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-[#005073] dark:group-hover:text-[#00bceb] transition-colors">{{ $sub->user->name ?? 'Unknown Student' }}</div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-widest transition-colors">{{ $sub->user->school ?? 'General' }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <span class="font-mono font-bold text-[#005073] dark:text-[#00bceb] text-sm transition-colors">{{ $sub->score }}/{{ $sub->total_questions }}</span>
                                <div class="grow max-w-[100px] h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden shadow-inner border border-slate-200/50 dark:border-slate-700 transition-colors">
                                    @php $percent = ($sub->score / max($sub->total_questions, 1)) * 100; @endphp
                                    <div class="bg-[#005073] dark:bg-[#00bceb] h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 dark:bg-slate-800/50 rounded-lg text-slate-500 dark:text-slate-400 text-[10px] font-bold transition-colors">
                                <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $sub->duration }}
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($sub->status === 'in_progress')
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 transition-colors flex items-center justify-center gap-1 mx-auto w-fit">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                </span>
                                In-Course
                            </span>
                            @elseif($sub->status === 'pending')
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800 animate-pulse flex items-center justify-center gap-1 mx-auto w-fit transition-colors">
                                 🔔 Review Needed
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800 flex items-center justify-center gap-1 mx-auto w-fit transition-colors">
                                 ✅ Completed
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right text-xs text-slate-400 dark:text-slate-500 font-medium transition-colors">
                            {{ $sub->created_at->format('d M, H:i') }}
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($sub->status === 'pending')
                            <a href="{{ route('submissions.review', $sub) }}"
                                class="bg-[#E2231A] hover:bg-[#E2231A] text-white px-4 py-2 rounded-xl text-[10px] font-black transition shadow-lg shadow-[#E2231A]/20 uppercase tracking-widest inline-flex items-center gap-2 transform hover:scale-105 active:scale-95">
                                 ✍️ Grade Essay
                            </a>
                            @else
                            <div class="flex justify-end items-center gap-2">
                                <span class="bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-slate-700 opacity-60">
                                     ✓ Graded
                                </span>
                                
                                {{-- Delete Button --}}
                                <button type="button" 
                                    @click="deleteSubmission('{{ $sub->uuid }}', '{{ addslashes($sub->user->name ?? 'Unknown Student') }}')"
                                    class="p-2 text-slate-300 dark:text-slate-600 hover:text-[#E2231A] dark:hover:text-rose-400 hover:bg-red-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors group/del">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>

                                <form id="delete-form-{{ $sub->uuid }}" action="{{ route('submissions.destroy', $sub) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-slate-400 dark:text-slate-600 italic transition-colors">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-slate-200 dark:text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                No submissions yet for this exam.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE VIEW --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
            @forelse($submissions as $sub)
            <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="font-bold text-slate-800 dark:text-white leading-tight">{{ $sub->user->name ?? 'Unknown Student' }}</div>
                        <div class="text-[10px] text-[#00bceb] font-black uppercase tracking-widest mt-1">{{ $sub->user->school ?? 'General' }}</div>
                    </div>
                    @if($sub->status === 'in_progress')
                    <span class="p-1 px-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase border border-blue-100 dark:border-blue-800/50">In-Course</span>
                    @elseif($sub->status === 'pending')
                    <span class="p-1 px-2 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase border border-amber-100 dark:border-amber-800/50 animate-pulse">🔔 Pending</span>
                    @else
                    <span class="p-1 px-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase border border-emerald-100 dark:border-emerald-800/50">✅ Graded</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="space-y-1">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Score</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-black text-[#005073] dark:text-[#00bceb]">{{ $sub->score }}/{{ $sub->total_questions }}</span>
                            <div class="grow h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden min-w-[40px]">
                                @php $percent = ($sub->score / max($sub->total_questions, 1)) * 100; @endphp
                                <div class="bg-[#005073] dark:bg-[#00bceb] h-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1 text-right">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Submitted</span>
                        <div class="text-[10px] font-bold text-slate-600 dark:text-slate-400">{{ $sub->created_at->format('d M, H:i') }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-slate-800">
                    <div class="flex items-center gap-1.5 text-slate-400 dark:text-slate-500 font-bold text-[10px]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $sub->duration }}
                    </div>

                    <div class="flex gap-2">
                        @if($sub->status === 'pending')
                        <a href="{{ route('submissions.review', $sub) }}"
                            class="bg-[#E2231A] text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-500/10">
                            Grade
                        </a>
                        @else
                        <button type="button" @click="deleteSubmission('{{ $sub->uuid }}', '{{ addslashes($sub->user->name ?? 'Unknown Student') }}')"
                            class="p-2 text-slate-400 dark:text-slate-600 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-100 dark:border-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="py-12 text-center text-slate-400 italic text-xs uppercase tracking-widest">
                No submissions yet.
            </div>
            @endforelse
        </div>

        @if($submissions->hasPages())
        <div class="px-6 py-4 border-t border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 transition-colors">
            {{ $submissions->links() }}
        </div>
        @endif
    </div>
</div>

<script>
 window.activeDeleteId = null;

 window.deleteSubmission = function(uuid, studentName) {
 window.activeDeleteId = uuid;
 confirmAction({
 title: 'Delete Submission',
 message: 'Are you sure you want to permanently remove ' + studentName + '\'s exam submission? This action cannot be undone.',
 icon: '🗑️',
 confirmText: 'Delete Data',
 cancelText: 'Keep it',
 onConfirm: 'executeDelete'
 });
 };

 window.executeDelete = function() {
 if (window.activeDeleteId) {
 document.getElementById('delete-form-' + window.activeDeleteId).submit();
 }
 };
</script>
@endsection
