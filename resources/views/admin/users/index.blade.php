@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10" x-data="{
    search: '',
    genPass() { this.$refs.passInput.value = Math.random().toString(36).slice(-8); },
    confirmDelete(action, name) {
        window.confirmAction({
            title: 'Delete Student',
            message: `Are you sure you want to permanently remove ${name}? This action will delete their profile and credentials.`,
            icon: '👤',
            type: 'danger',
            confirmText: 'Delete Now',
            onConfirm: () => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = action;
                form.innerHTML = `
                    <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                    <input type='hidden' name='_method' value='DELETE'>
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
}">
    {{-- Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#E2231A]/10 dark:bg-[#E2231A]/20 text-[#E2231A] text-[10px] font-black uppercase tracking-widest mb-3 border border-[#E2231A]/30 transition-colors">
                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
                Student Management
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-[#005073] dark:text-white tracking-tight leading-none mb-2">Manage Students</h1>
            <p class="text-slate-500 dark:text-slate-400 font-semibold">Register new students and manage credentials for Cisco NetRider's exams</p>
        </div>

        <div class="relative w-full md:w-80">
            <input type="text" x-model="search" placeholder="Search name or school..."
                class="w-full pl-12 pr-4 py-4 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl text-sm focus:ring-4 focus:ring-[#E2231A]/10 focus:border-[#E2231A]/50 transition shadow-sm font-bold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-[#E2231A]/40 dark:text-[#E2231A]/60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Registration Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl shadow-slate-200/60 dark:shadow-black/40 border border-slate-100 dark:border-slate-700 p-10 md:p-12 mb-10 relative overflow-hidden transition-colors duration-300">
        {{-- Decorative gradient --}}
        <div class="absolute top-0 right-0 w-96 h-96 opacity-10 dark:opacity-20 pointer-events-none" style="background: radial-gradient(circle, #E2231A 0%, transparent 70%);"></div>
        
        <div class="flex items-center gap-4 mb-8 relative z-10">
            <div class="p-3 rounded-2xl bg-[#E2231A] bg-gradient-to-br from-[#E2231A] to-[#be1e16] shadow-lg shadow-[#E2231A]/40">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-black text-[#005073] dark:text-white tracking-tight uppercase">Register New Student</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-widest">Entry Point for Participants</p>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-5 relative z-10">
            @csrf
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2 ml-1 tracking-widest">Full Name</label>
                <input type="text" name="name" placeholder="John Doe" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:ring-4 focus:ring-[#00bceb]/20 focus:border-[#00bceb] font-bold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 transition" required>
            </div>
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2 ml-1 tracking-widest">Username / ID</label>
                <input type="text" name="username" placeholder="johndoe123" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:ring-4 focus:ring-[#00bceb]/20 focus:border-[#00bceb] font-bold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 transition" required>
            </div>
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2 ml-1 tracking-widest">Email Address</label>
                <input type="email" name="email" placeholder="john@school.com" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:ring-4 focus:ring-[#00bceb]/20 focus:border-[#00bceb] font-bold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 transition" required>
            </div>
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2 ml-1 tracking-widest">School Name</label>
                <input type="text" name="school" placeholder="High School A" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:ring-4 focus:ring-[#00bceb]/20 focus:border-[#00bceb] font-bold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 transition">
            </div>
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 mb-2 ml-1 tracking-widest">Access Key (Optional)</label>
                <div class="relative">
                    <input type="text" name="password" x-ref="passInput" placeholder="••••••••" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:ring-4 focus:ring-[#00bceb]/20 focus:border-[#00bceb] font-bold text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 pr-10 transition">
                    <button type="button" @click="genPass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#00bceb] hover:scale-125 transition active:rotate-45" title="Generate Random">✨</button>
                </div>
            </div>
            <div class="lg:col-span-1 flex items-end">
                <button type="submit" class="w-full px-6 py-3.5 rounded-2xl font-black uppercase tracking-widest text-xs text-white shadow-xl shadow-[#E2231A]/30 dark:shadow-none hover:shadow-2xl transition-all transform hover:-translate-y-0.5 active:scale-95 bg-[#E2231A] bg-gradient-to-r from-[#E2231A] via-[#be1e16] to-[#a01912] border-none">
                    Add Student
                </button>
            </div>
        </form>
    </div>

    {{-- Students Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl shadow-slate-200/60 dark:shadow-black/40 border border-slate-100 dark:border-slate-700 border-t-4 border-t-[#E2231A] overflow-hidden transition-colors duration-300">
        <div class="p-8 md:p-10 border-b border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-[#E2231A]/5 dark:bg-[#E2231A]/10 rounded-2xl flex items-center justify-center transition-colors shadow-inner">
                        <svg class="w-6 h-6 text-[#E2231A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#005073] dark:text-white tracking-tight uppercase transition-colors">Student Roster</h3>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest transition-colors">Current Personnel in System</p>
                    </div>
                </div>
                
                <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800 rounded-xl text-[10px] font-black uppercase text-slate-400 dark:text-slate-500 transition-colors">
                    {{ count($users) }} Registered
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                        <th class="px-8 py-5 text-[10px] font-black text-[#E2231A] dark:text-[#E2231A] uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 transition-colors">Identity Profile</th>
                        <th class="px-8 py-5 text-[10px] font-black text-[#E2231A] dark:text-[#E2231A] uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 transition-colors">Academic Hub</th>
                        <th class="px-8 py-5 text-[10px] font-black text-[#E2231A] dark:text-[#E2231A] uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 text-right transition-colors">Command Access</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 transition-colors">
                    @forelse($users as $user)
                    <tr x-show="search === '' || '{{ strtolower($user->name) }} {{ strtolower($user->school) }}'.includes(search.toLowerCase())"
                        class="hover:bg-slate-50 dark:hover:bg-slate-800/30 group transition-all duration-200">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-[#005073] bg-gradient-to-br from-[#005073] to-[#00bceb] flex items-center justify-center text-white font-black text-xs shadow-lg border-2 border-white dark:border-slate-800 ring-2 ring-[#00bceb]/20 transition-all group-hover:ring-[#00bceb]/40">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 dark:text-white group-hover:text-[#00bceb] transition-colors uppercase tracking-tight">{{ $user->name }}</div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold transition-colors uppercase tracking-widest mt-0.5">
                                        <span class="text-[#00bceb]">{{ $user->username }}</span> • {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-[#005073] dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-slate-200 dark:border-slate-700 transition-colors">
                                {{ $user->school ?? 'Remote Terminal' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                    class="p-2.5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 hover:bg-[#00bceb] hover:text-white dark:hover:text-slate-900 rounded-xl shadow-sm transition-all transform hover:scale-110" title="Edit Access">
                                    <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button type="button" @click="confirmDelete('{{ route('admin.users.destroy', $user) }}', '{{ $user->name }}')"
                                    class="p-2.5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 hover:bg-rose-600 hover:text-white rounded-xl shadow-sm transition-all transform hover:scale-110" title="Revoke Access">
                                    <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-4xl flex items-center justify-center transition-colors shadow-inner">
                                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <span class="text-slate-400 dark:text-slate-600 font-black text-sm uppercase tracking-[0.2em] transition-colors">Zero Personnel Detected</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-8 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
