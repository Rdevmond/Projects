@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-950 py-12 px-6 transition-colors duration-500" x-data="cmsSettings()">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-[#00bceb]/10 dark:bg-[#00bceb]/20 text-[#005073] dark:text-[#00bceb] text-[10px] font-black uppercase tracking-widest mb-2 border border-[#00bceb]/30 dark:border-[#00bceb]/40 transition-colors">
                    <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
                    CMS Administration
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-[#005073] dark:text-white tracking-tight leading-none mb-2 transition-colors">Home Page Customization</h1>
                <p class="text-slate-500 dark:text-slate-400 font-semibold transition-colors">Fully customize your NetRiders homepage - content, styling, and layout</p>
            </div>
            
            <a href="{{ route('home') }}" target="_blank" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 rounded-2xl font-bold text-sm hover:text-[#005073] dark:hover:text-white hover:shadow-xl transition-all border-2 border-slate-200 dark:border-slate-800 hover:border-[#00bceb]/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Preview Site
            </a>
        </div>

        <form action="{{ route('admin.welcome.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Tab Navigation --}}
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/60 dark:border-slate-700 p-2 mb-6 flex flex-wrap gap-2 transition-colors">
                <button type="button" @click="activeTab = 'hero'" 
                        :class="activeTab === 'hero' ? 'bg-gradient-to-r from-[#005073] to-[#00bceb] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-6 py-3 rounded-2xl font-bold text-sm transition-all">
                    🚀 Hero Section
                </button>
                <button type="button" @click="activeTab = 'stats'" 
                        :class="activeTab === 'stats' ? 'bg-gradient-to-r from-[#005073] to-[#00bceb] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-6 py-3 rounded-2xl font-bold text-sm transition-all">
                    📊 Stats & Badges
                </button>
                <button type="button" @click="activeTab = 'about'" 
                        :class="activeTab === 'about' ? 'bg-gradient-to-r from-[#005073] to-[#00bceb] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-6 py-3 rounded-2xl font-bold text-sm transition-all">
                    🤝 About Section
                </button>
                <button type="button" @click="activeTab = 'activities'" 
                        :class="activeTab === 'activities' ? 'bg-gradient-to-r from-[#005073] to-[#00bceb] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-6 py-3 rounded-2xl font-bold text-sm transition-all">
                    🏆 Activities
                </button>
                <button type="button" @click="activeTab = 'cta'" 
                        :class="activeTab === 'cta' ? 'bg-gradient-to-r from-[#005073] to-[#00bceb] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-6 py-3 rounded-2xl font-bold text-sm transition-all">
                    📣 Call to Action
                </button>
                <button type="button" @click="activeTab = 'styling'" 
                        :class="activeTab === 'styling' ? 'bg-gradient-to-r from-[#005073] to-[#00bceb] text-white shadow-lg' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                        class="px-6 py-3 rounded-2xl font-bold text-sm transition-all">
                    🎨 Styling & Layout
                </button>
            </div>

            {{-- Hero Section Tab --}}
            <div x-show="activeTab === 'hero'" x-cloak class="space-y-6">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-white/60 dark:border-slate-700 p-10 md:p-14 transition-colors">
                    <h3 class="text-2xl font-black text-[#005073] dark:text-white mb-8 flex items-center gap-3 transition-colors">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-2xl" style="background: linear-gradient(135deg, #005073 0%, #00bceb 100%);">🚀</div>
                        Hero Section
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Main Heading</label>
                            <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? 'Master Your Network, Shape Your Future.' }}"
                                class="w-full px-6 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 outline-none transition font-bold text-slate-800 dark:text-slate-200 text-lg">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Subtitle / Description</label>
                            <textarea name="hero_subtitle" rows="3"
                                class="w-full px-6 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 outline-none transition font-semibold text-slate-700 dark:text-slate-300">{{ $settings['hero_subtitle'] ?? "Welcome to the official Cisco NetRider's assessment portal. Step into the world's most prestigious IT competition and prove your skills on a global stage." }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Background Image</label>
                            @if(isset($settings['hero_bg_image']))
                                <img src="{{ asset('storage/' . $settings['hero_bg_image']) }}" class="w-full h-48 object-cover rounded-3xl mb-4 border-2 border-slate-200 dark:border-slate-700">
                            @else
                                <div class="w-full h-48 bg-slate-100 dark:bg-slate-900 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-600 mb-4 font-semibold transition-colors">
                                    No background image uploaded
                                </div>
                            @endif
                            <input type="file" name="hero_bg_image" accept="image/*"
                                class="w-full px-6 py-3 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] outline-none transition font-bold text-slate-700 dark:text-slate-400 text-sm">
                            <p class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 italic ml-1 transition-colors">Recommended: Wide landscape image, 1920x1080px or higher</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Section Tab --}}
            <div x-show="activeTab === 'stats'" x-cloak class="space-y-6">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-white/60 dark:border-slate-700 p-10 md:p-14 transition-colors">
                    <h3 class="text-2xl font-black text-[#005073] dark:text-white mb-8 flex items-center gap-3 transition-colors">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-2xl" style="background: linear-to-br from-[#005073] 0%, #00bceb 100%;">📊</div>
                        Stats & Badges
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Stats Item loop --}}
                        @for($i = 1; $i <= 4; $i++)
                        <div class="p-8 bg-slate-50/50 dark:bg-slate-900/50 rounded-4xl border-2 border-slate-100 dark:border-slate-700 transition-colors">
                            <div class="flex items-center gap-2 mb-6 text-[#005073] dark:text-[#00bceb]">
                                <span class="text-xs font-black uppercase tracking-widest transition-colors">Stat Position {{ $i }}</span>
                            </div>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Metric Name</label>
                                    <input type="text" name="stat{{ $i }}_label" value="{{ $settings['stat' . $i . '_label'] ?? '' }}"
                                        class="w-full px-6 py-3 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-[#00bceb] outline-none transition font-bold text-slate-800 dark:text-slate-200">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Count / Value</label>
                                    <input type="text" name="stat{{ $i }}_count" value="{{ $settings['stat' . $i . '_count'] ?? '' }}"
                                        class="w-full px-6 py-3 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-[#00bceb] outline-none transition font-bold text-[#00bceb]">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- About Section Tab --}}
            <div x-show="activeTab === 'about'" x-cloak class="space-y-6">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-white/60 dark:border-slate-700 p-10 md:p-14 transition-colors">
                    <h3 class="text-2xl font-black text-[#005073] dark:text-white mb-8 flex items-center gap-3 transition-colors">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-2xl" style="background: linear-to-br from-[#005073] 0%, #00bceb 100%;">🤝</div>
                        About Section
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Section Title</label>
                                <input type="text" name="about_title" value="{{ $settings['about_title'] ?? "About Cisco NetRider's" }}"
                                    class="w-full px-6 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 outline-none transition font-bold text-slate-800 dark:text-slate-200">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Content</label>
                                <textarea name="about_content" rows="8"
                                    class="w-full px-6 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 outline-none transition font-semibold text-slate-700 dark:text-slate-300">{{ $settings['about_content'] ?? "NetRider's is an interactive networking competition that provides students with the opportunity to test their networking/IT skills, gain visibility among recruiters, and win prizes." }}</textarea>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Section Image</label>
                            @if(isset($settings['about_image']))
                                <img src="{{ asset('storage/' . $settings['about_image']) }}" class="w-full h-80 object-cover rounded-3xl mb-4 border-2 border-slate-200 dark:border-slate-700">
                            @else
                                <div class="w-full h-80 bg-slate-100 dark:bg-slate-900 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-600 mb-4 font-semibold transition-colors">
                                    No image uploaded
                                </div>
                            @endif
                            <input type="file" name="about_image" accept="image/*"
                                class="w-full px-6 py-3 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] outline-none transition font-bold text-slate-700 dark:text-slate-400 text-sm">
                            <p class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 italic ml-1 transition-colors">Recommended: Square or 4:5 aspect ratio, high resolution</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activities Tab --}}
            <div x-show="activeTab === 'activities'" x-cloak class="space-y-6">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-white/60 dark:border-slate-700 p-10 md:p-14 transition-colors">
                    <h3 class="text-2xl font-black text-[#005073] dark:text-white mb-8 flex items-center gap-3 transition-colors">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-2xl" style="background: linear-to-br from-[#005073] 0%, #00bceb 100%;">🏆</div>
                        Activities List
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @for($i = 1; $i <= 3; $i++)
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100/50 dark:from-slate-900/50 dark:to-slate-900/50 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-6 transition-colors">
                            <h4 class="text-sm font-black text-[#005073] dark:text-white mb-4 uppercase tracking-wide transition-colors">Activity {{ $i }}</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Icon (Emoji)</label>
                                    <input type="text" name="activity_{{ $i }}_icon" value="{{ $settings['activity_'.$i.'_icon'] ?? '🏆' }}" maxlength="2"
                                        class="w-full px-5 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-[#00bceb] focus:ring-2 focus:ring-[#00bceb]/20 outline-none transition font-bold text-slate-800 dark:text-slate-200 text-center text-2xl">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Title</label>
                                    <input type="text" name="activity_{{ $i }}_title" value="{{ $settings['activity_'.$i.'_title'] ?? '' }}" placeholder="Skills Competition"
                                        class="w-full px-5 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-[#00bceb] focus:ring-2 focus:ring-[#00bceb]/20 outline-none transition font-bold text-slate-800 dark:text-slate-200">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Description</label>
                                    <textarea name="activity_{{ $i }}_description" rows="3" placeholder="Brief description..."
                                        class="w-full px-5 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:border-[#00bceb] focus:ring-2 focus:ring-[#00bceb]/20 outline-none transition font-semibold text-slate-700 dark:text-slate-300 text-sm">{{ $settings['activity_'.$i.'_description'] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Accent Color</label>
                                    <input type="color" name="activity_{{ $i }}_color" value="{{ $settings['activity_'.$i.'_color'] ?? '#00bceb' }}"
                                        class="w-full h-12 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer transition-colors">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Call to Action Tab --}}
            <div x-show="activeTab === 'cta'" x-cloak class="space-y-6">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-white/60 dark:border-slate-700 p-10 md:p-14 transition-colors">
                    <h3 class="text-2xl font-black text-[#005073] dark:text-white mb-8 flex items-center gap-3 transition-colors">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-2xl" style="background: linear-to-br from-[#005073] 0%, #00bceb 100%;">📣</div>
                        Call to Action Card
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">CTA Heading</label>
                            <input type="text" name="cta_title" value="{{ $settings['cta_title'] ?? 'Ready to start your journey?' }}"
                                class="w-full px-6 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 outline-none transition font-bold text-slate-800 dark:text-slate-200 text-lg">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">CTA Description</label>
                            <textarea name="cta_description" rows="3"
                                class="w-full px-6 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 outline-none transition font-semibold text-slate-700 dark:text-slate-300">{{ $settings['cta_description'] ?? 'Join thousands of students and networking professionals on the path to excellence. Your future starts with a single login.' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 ml-1 transition-colors">Button Text</label>
                            <input type="text" name="cta_button_text" value="{{ $settings['cta_button_text'] ?? 'Log in to Portal Now' }}"
                                class="w-full px-6 py-4 rounded-2xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-[#00bceb] focus:ring-4 focus:ring-[#00bceb]/20 outline-none transition font-bold text-slate-800 dark:text-slate-200">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Styling & Layout Tab --}}
            <div x-show="activeTab === 'styling'" x-cloak class="space-y-6">
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[3rem] shadow-2xl border border-white/60 dark:border-slate-700 p-10 md:p-14 transition-colors">
                    <h3 class="text-2xl font-black text-[#005073] dark:text-white mb-8 flex items-center gap-3 transition-colors">
                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-2xl" style="background: linear-to-br from-[#005073] 0%, #00bceb 100%;">🎨</div>
                        Styling & Layout Options
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100/50 dark:from-slate-900/50 dark:to-slate-900/50 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-6 transition-colors">
                            <h4 class="text-sm font-black text-[#005073] dark:text-white mb-6 uppercase tracking-wide transition-colors">Brand Colors</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Primary Color</label>
                                    <input type="color" name="primary_color" value="{{ $settings['primary_color'] ?? '#005073' }}"
                                        class="w-full h-14 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer transition-colors">
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 ml-1 transition-colors">Main brand color (Navy)</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Secondary Color</label>
                                    <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#00bceb' }}"
                                        class="w-full h-14 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer transition-colors">
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 ml-1 transition-colors">Secondary brand color (Blue)</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 ml-1 transition-colors">Accent Color</label>
                                    <input type="color" name="accent_color" value="{{ $settings['accent_color'] ?? '#E2231A' }}"
                                        class="w-full h-14 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer transition-colors">
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 ml-1 transition-colors">Accent color (Red)</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-slate-50 to-slate-100/50 dark:from-slate-900/50 dark:to-slate-900/50 rounded-3xl border-2 border-slate-200 dark:border-slate-700 p-6 transition-colors">
                            <h4 class="text-sm font-black text-[#005073] dark:text-white mb-6 uppercase tracking-wide transition-colors">Section Visibility</h4>
                            <div class="space-y-4">
                                <label class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-[#00bceb] transition-colors">
                                    <input type="checkbox" name="show_stats" value="1" {{ ($settings['show_stats'] ?? '1') == '1' ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-[#00bceb] focus:ring-[#00bceb] bg-white dark:bg-slate-700">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 transition-colors">Show Stats & Badges</span>
                                </label>
                                <label class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-[#00bceb] transition-colors">
                                    <input type="checkbox" name="show_about" value="1" {{ ($settings['show_about'] ?? '1') == '1' ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-[#00bceb] focus:ring-[#00bceb] bg-white dark:bg-slate-700">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 transition-colors">Show About Section</span>
                                </label>
                                <label class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-[#00bceb] transition-colors">
                                    <input type="checkbox" name="show_activities" value="1" {{ ($settings['show_activities'] ?? '1') == '1' ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-[#00bceb] focus:ring-[#00bceb] bg-white dark:bg-slate-700">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 transition-colors">Show Activities Section</span>
                                </label>
                                <label class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-[#00bceb] transition-colors">
                                    <input type="checkbox" name="show_cta" value="1" {{ ($settings['show_cta'] ?? '1') == '1' ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-[#00bceb] focus:ring-[#00bceb] bg-white dark:bg-slate-700">
                                    <span class="font-bold text-slate-700 dark:text-slate-300 transition-colors">Show CTA Section</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fixed Save Bar --}}
            <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-full max-w-lg px-6">
                <div class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-xl rounded-2xl p-4 shadow-2xl border border-slate-200 dark:border-white/10 flex items-center justify-between transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-widest">CMS Ready to Sync</span>
                    </div>
                    <button type="submit" 
                            class="relative group overflow-hidden bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-lg shadow-emerald-500/20">
                        <span class="relative z-10">Deploy Updates</span>
                        <div class="absolute inset-0 bg-white/20 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function cmsSettings() {
        return {
            activeTab: 'hero'
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    input:focus, textarea:focus { outline: none !important; }
    input::placeholder, textarea::placeholder { color: #cbd5e1; font-weight: 500; }
</style>
@endsection
