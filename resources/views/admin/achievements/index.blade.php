@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10 transition-colors">
  <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 transition-colors">
  <div class="flex-1">
  <h1 class="text-4xl font-black text-[#005073] dark:text-[#00bceb] tracking-tight transition-colors">Achievements & News</h1>
  <p class="text-slate-500 dark:text-slate-400 font-medium transition-colors">Manage the success stories and updates shown on the home page.</p>
  </div>
  
  {{-- Search Bar --}}
  <form action="{{ route('admin.achievements.index') }}" method="GET" class="relative w-full md:w-80">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search news or stories..."
          class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-800 rounded-2xl text-xs focus:ring-4 focus:ring-[#E2231A]/10 focus:border-[#E2231A]/50 transition shadow-sm font-bold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500">
      <div class="absolute left-4 top-1/2 -translate-y-1/2 text-[#E2231A]/40">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
  </form>

  <a href="{{ route('admin.achievements.create') }}" class="shrink-0 px-8 py-4 bg-[#E2231A] text-white rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-rose-900/20 transition transform hover:-translate-y-1">
  + New Achievement
  </a>
  </div>
 <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-xl dark:shadow-none overflow-hidden transition-colors">
        {{-- DESKTOP VIEW --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-800 transition-colors">
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Achievement</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Date</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Status</th>
                        <th class="px-8 py-6 text-right text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 transition-colors">
                    @forelse($achievements as $ach)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="px-8 py-6 transition-colors">
                            <div class="flex items-center gap-4 transition-colors">
                                @if($ach->image_path)
                                <img src="{{ asset('storage/' . $ach->image_path) }}" class="w-12 h-12 rounded-xl object-cover border border-slate-100 dark:border-slate-800 transition-colors">
                                @else
                                <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-xl transition-colors">🏆</div>
                                @endif
                                <div class="transition-colors overflow-hidden">
                                    <div class="font-black text-[#005073] dark:text-[#00bceb] transition-colors truncate max-w-xs">{{ $ach->title }}</div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500 font-medium truncate max-w-xs transition-colors">{{ $ach->content }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 font-bold text-slate-600 dark:text-slate-300 text-sm transition-colors">
                            {{ $ach->date ? $ach->date->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-8 py-6 transition-colors">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $ach->is_active ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/10' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500' }} transition-colors">
                                {{ $ach->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right space-x-2">
                            <a href="{{ route('admin.achievements.edit', $ach) }}" class="inline-flex p-2 bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form id="delete-form-{{ $ach->id }}" action="{{ route('admin.achievements.destroy', $ach) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" 
                                    onclick="window.confirmAction({
                                        title: 'Delete Achievement',
                                        message: 'Are you sure you want to permanently delete this achievement? This action cannot be undone.',
                                        icon: '🗑️',
                                        type: 'danger',
                                        confirmText: 'Delete Now',
                                        onConfirm: '#delete-form-{{ $ach->id }}'
                                    })" 
                                    class="p-2 bg-rose-50 dark:bg-rose-500/10 text-rose-500 dark:text-rose-400 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 dark:text-slate-500 font-medium transition-colors">No achievements found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE VIEW --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
            @forelse($achievements as $ach)
            <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                <div class="flex gap-4 mb-4">
                    @if($ach->image_path)
                    <img src="{{ asset('storage/' . $ach->image_path) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-100 dark:border-slate-800">
                    @else
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-2xl">🏆</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="font-black text-[#005073] dark:text-[#00bceb] leading-tight truncate">{{ $ach->title }}</div>
                        <div class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-widest mt-1">{{ $ach->date ? $ach->date->format('M d, Y') : 'N/A' }}</div>
                        <div class="mt-2">
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest {{ $ach->is_active ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/10' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500' }}">
                                {{ $ach->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-6 line-clamp-2">
                    {{ $ach->content }}
                </p>

                <div class="flex gap-2">
                    <a href="{{ route('admin.achievements.edit', $ach) }}" 
                        class="flex-1 flex items-center justify-center py-3 bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 rounded-xl border border-blue-100 dark:border-blue-500/20 font-bold text-[10px] uppercase tracking-widest">
                        Edit Success Story
                    </a>
                    <form id="delete-form-mobile-{{ $ach->id }}" action="{{ route('admin.achievements.destroy', $ach) }}" method="POST" class="contents">
                        @csrf @method('DELETE')
                        <button type="button" 
                            onclick="window.confirmAction({
                                title: 'Delete Achievement',
                                message: 'Are you sure?',
                                icon: '🗑️',
                                type: 'danger',
                                confirmText: 'Delete',
                                onConfirm: '#delete-form-mobile-{{ $ach->id }}'
                            })" 
                            class="px-4 flex items-center justify-center bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-xl border border-rose-100 dark:border-rose-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="py-12 text-center text-slate-400 italic text-xs uppercase tracking-widest">
                No success stories recorded.
            </div>
            @endforelse
        </div>
    </div>
 @if($achievements->hasPages())
 <div class="px-8 py-4 bg-slate-50 dark:bg-slate-800/50 transition-colors">
 {{ $achievements->links() }}
 </div>
 @endif
</div>
@endsection
