@extends('layouts.app')

@php
    $settings = \App\Models\SiteSetting::pluck('value', 'key')->all();
    $achievements = \App\Models\Achievement::where('is_active', true)->latest()->take(3)->get();
    
    // Get customizable colors with defaults
    $primaryColor = $settings['primary_color'] ?? '#005073';
    $secondaryColor = $settings['secondary_color'] ?? '#00bceb';
    $accentColor = $settings['accent_color'] ?? '#E2231A';
    
    // Section visibility
    $showStats = ($settings['show_stats'] ?? '1') == '1';
    $showAbout = ($settings['show_about'] ?? '1') == '1';
    $showActivities = ($settings['show_activities'] ?? '1') == '1';
    $showCta = ($settings['show_cta'] ?? '1') == '1';
@endphp

@section('content')
<div class="flex flex-col gap-24 py-12">
    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden rounded-[3rem] bg-slate-900 text-white min-h-[600px] flex items-center">
        <div class="absolute inset-0 z-0">
            @if(isset($settings['hero_bg_image']))
                <img src="{{ asset('storage/' . $settings['hero_bg_image']) }}" class="w-full h-full object-cover opacity-30" alt="Hero Background">
            @else
                <img src="{{ asset('images/welcome-hero.png') }}" class="w-full h-full object-cover opacity-30" alt="Networking Background">
            @endif
            <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(15, 23, 42, 1) 0%, rgba(15, 23, 42, 0.8) 50%, transparent 100%);"></div>
        </div>
        
        <div class="relative z-10 px-12 md:px-24 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-super-wide mb-8 animate-bounce" 
                 style="background: {{ $accentColor }};">
                🚀 Now Live: 2026 Competition
            </div>
            <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
                {{ $settings['hero_title'] ?? 'Master Your Network, Shape Your Future.' }}
            </h1>
            <p class="text-xl text-slate-300 font-medium mb-10 leading-relaxed">
                {{ $settings['hero_subtitle'] ?? "Welcome to the official Cisco NetRider's assessment portal. Step into the world's most prestigious IT competition and prove your skills on a global stage." }}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('login') }}" class="px-10 py-5 text-white rounded-2xl font-black uppercase tracking-widest text-sm shadow-2xl transition transform hover:-translate-y-1 active:scale-95" 
                   style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);">
                    Login to Portal
                </a>
                <a href="#about" class="px-10 py-5 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 rounded-2xl font-black uppercase tracking-widest text-sm transition">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    {{-- STATS / BADGES --}}
    @if($showStats)
    <section class="max-w-6xl mx-auto w-full grid grid-cols-2 md:grid-cols-4 gap-8 px-6">
        @for($i = 1; $i <= 4; $i++)
            @php
                $number = $settings['stat_'.$i.'_number'] ?? '';
                $label = $settings['stat_'.$i.'_label'] ?? '';
                $color = $settings['stat_'.$i.'_color'] ?? $primaryColor;
            @endphp
            @if($number && $label)
            <div class="text-center">
                <div class="text-4xl font-black mb-2 transition-colors duration-300" style="color: {{ $color }};">{{ $number }}</div>
                <div class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ $label }}</div>
            </div>
            @endif
        @endfor
    </section>
    @endif

    {{-- ABOUT US SECTION --}}
    @if($showAbout)
    <section id="about" class="bg-white dark:bg-slate-800 rounded-[3rem] p-12 md:p-24 border border-slate-100 dark:border-slate-700 shadow-xl shadow-slate-200/50 dark:shadow-none flex flex-col md:flex-row gap-16 items-center transition-all duration-300">
        <div class="flex-1">
            <div class="h-1.5 w-24 mb-8" style="background: {{ $accentColor }};"></div>
            <h2 class="text-4xl font-black mb-8 leading-tight dark:text-white" style="color: {{ $primaryColor }};">
                {{ $settings['about_title'] ?? "About Cisco NetRiders" }}
            </h2>
            <div class="space-y-6 text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                <p>
                    {{ $settings['about_content'] ?? "NetRider's is an interactive networking competition that provides students with the opportunity to test their networking/IT skills, gain visibility among recruiters, and win prizes." }}
                </p>
                @if(!isset($settings['about_content']))
                <p>
                    Hosted by Cisco Networking Academy, this competition showcases the talent of students globally and highlights the importance of IT skills in today's workforce.
                </p>
                @endif
                <ul class="space-y-4 pt-4">
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <span>Real-world networking scenarios and troubleshooting.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <span>Hands-on Packet Tracer activities and theoretical assessments.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="text-emerald-500 font-bold">✓</span>
                        <span>Global ranking and career advancement opportunities.</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex-1 w-full flex justify-center">
            <div class="relative w-full aspect-square max-w-md">
                <div class="absolute inset-0 bg-blue-50 rounded-[4rem] rotate-3 translate-x-4 translate-y-4"></div>
                <div class="absolute inset-0 bg-slate-900 rounded-[4rem] overflow-hidden flex items-center justify-center transform -rotate-1 transition-transform hover:rotate-0 duration-500">
                    @if(isset($settings['about_image']))
                        <img src="{{ asset('storage/' . $settings['about_image']) }}" class="w-full h-full object-cover">
                    @else
                        <div class="p-12 text-center text-white">
                            <div class="text-7xl mb-6">🎓</div>
                            <h4 class="text-2xl font-black mb-4 uppercase tracking-widest">Student Excellence</h4>
                            <p class="text-slate-400 text-sm">Empowering the next generation of IT leaders.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ACHIEVEMENTS / NEWS SECTION --}}
    @if($achievements->count() > 0)
    <section id="achievements" class="max-w-7xl mx-auto w-full px-6">
        <div class="flex justify-between items-end mb-16">
            <div>
                <h2 class="text-4xl font-black mb-4" style="color: {{ $primaryColor }};">Achievements & News</h2>
                <p class="text-slate-500 font-medium">Celebrating our community's success and latest updates.</p>
            </div>
            <div class="h-1 w-24 mb-2" style="background: {{ $accentColor }};"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @foreach($achievements as $achievement)
            <div class="group bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col">
                <div class="h-56 bg-slate-100 relative overflow-hidden">
                    @if($achievement->image_path)
                        <img src="{{ asset('storage/' . $achievement->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-6xl">🏆</div>
                    @endif
                    <div class="absolute bottom-6 left-6 flex gap-2">
                        <span class="px-4 py-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest" style="color: {{ $primaryColor }};">
                            {{ $achievement->category }}
                        </span>
                    </div>
                </div>
                <div class="p-8 flex-1 flex flex-col">
                    <h3 class="text-xl font-extrabold text-[#005073] dark:text-[#00bceb] mb-4 line-clamp-2 leading-snug transition-colors">
                        {{ $achievement->title }}
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium leading-relaxed line-clamp-3 mb-6">
                        {{ $achievement->description }}
                    </p>
                    <div class="h-1 w-12 bg-slate-100 group-hover:w-full transition-all duration-500" style="background: {{ $accentColor }};"></div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ACTIVITIES SECTION --}}
    @if($showActivities)
    <section id="activities" class="max-w-7xl mx-auto w-full px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-black mb-4" style="color: {{ $primaryColor }};">Competitions & Activities</h2>
            <p class="text-slate-500 max-w-2xl mx-auto font-medium">Explore the different ways you can challenge yourself and grow with NetRider's.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @for($i = 1; $i <= 3; $i++)
                @php
                    $icon = $settings['activity_'.$i.'_icon'] ?? '🏆';
                    $title = $settings['activity_'.$i.'_title'] ?? '';
                    $description = $settings['activity_'.$i.'_description'] ?? '';
                    $activityColor = $settings['activity_'.$i.'_color'] ?? $secondaryColor;
                @endphp
                @if($title && $description)
                <div class="group bg-white dark:bg-slate-800 p-10 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-md hover:shadow-2xl transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-8 transition-colors" 
                         style="background: {{ $activityColor }}20; color: {{ $activityColor }};">
                        {{ $icon }}
                    </div>
                    <h4 class="text-xl font-black mb-4 uppercase tracking-wide" style="color: {{ $primaryColor }};">{{ $title }}</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6 font-medium">
                        {{ $description }}
                    </p>
                    <div class="h-1 w-12 bg-slate-100 group-hover:w-full transition-all duration-500" style="background: {{ $activityColor }};"></div>
                </div>
                @endif
            @endfor
        </div>
    </section>
    @endif

    {{-- FINAL CTA --}}
    @if($showCta)
    <section class="rounded-[3rem] p-12 md:p-24 text-center text-white" 
             style="background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);">
        <h2 class="text-4xl md:text-5xl font-black mb-8">
            {{ $settings['cta_title'] ?? 'Ready to start your journey?' }}
        </h2>
        <p class="text-lg text-white/90 mb-12 max-w-2xl mx-auto font-medium">
            {{ $settings['cta_description'] ?? 'Join thousands of students and networking professionals on the path to excellence. Your future starts with a single login.' }}
        </p>
        <a href="{{ route('login') }}" class="inline-flex items-center gap-4 px-12 py-6 rounded-2xl font-black uppercase tracking-widest text-sm shadow-2xl transition transform hover:-translate-y-2 active:scale-95"
           style="background: {{ $accentColor }};">
            {{ $settings['cta_button_text'] ?? 'Log in to Portal Now' }}
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </section>
    @endif
</div>
@endsection
