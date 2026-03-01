@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-10 border-b border-slate-100 dark:border-slate-800 pb-8 transition-colors">
        <h1 class="text-5xl font-black text-slate-900 dark:text-white tracking-tight italic transition-colors">{{ $exam->title }}</h1>
        <p class="text-slate-400 dark:text-slate-500 mt-3 text-xl font-medium transition-colors">{{ $exam->description }}</p>
    </div>

    <form id="exam-form" action="{{ route('exam.submit', $exam) }}" method="POST">
        @csrf
        @foreach($exam->questions as $index => $q)
            <div class="bg-white dark:bg-slate-800 p-8 rounded-[3rem] shadow-sm border border-slate-200 dark:border-slate-700 mb-8 transition-colors">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest">Q-{{ $index + 1 }}</span>
                    <span class="text-[10px] font-black text-slate-300 dark:text-slate-600 uppercase tracking-widest transition-colors">{{ $q->type }}</span>
                </div>

                <p class="text-2xl font-bold text-slate-800 dark:text-slate-200 mb-8 leading-tight transition-colors">{{ $q->question_text }}</p>

                {{-- Context Image if any --}}
                @if($q->context_image_path)
                    <div class="mb-8">
                        <img src="{{ asset('storage/' . $q->context_image_path) }}" 
                             alt="Question Illustration" 
                             class="max-w-full h-auto rounded-3xl border-4 border-slate-50 dark:border-slate-700 shadow-sm mx-auto transition-colors">
                    </div>
                @endif

                {{-- Multiple Choice: Checkbox for multiple right answers --}}
                @if($q->type === 'option')
                    <div class="grid gap-4">
                        @foreach($q->answer_details['options'] as $opt)
                            <label class="flex items-center p-6 rounded-4xl border-2 border-slate-50 dark:border-slate-700 cursor-pointer hover:bg-blue-50/50 dark:hover:bg-slate-700/50 hover:border-blue-200 dark:hover:border-blue-500 transition-all group">
                                <input type="checkbox" name="answers[{{ $q->id }}][]" value="{{ $opt['id'] }}"
                                    class="w-6 h-6 rounded-lg border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500 transition-all">
                                
                                @if(isset($opt['image']) && $opt['image'])
                                    <img src="{{ asset('storage/' . $opt['image']) }}" 
                                         class="ml-5 w-20 h-20 object-cover rounded-xl border-2 border-slate-100 dark:border-slate-600 shadow-sm transition-colors">
                                @endif

                                <span class="ml-5 text-lg text-slate-700 dark:text-slate-300 font-bold group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">{{ $opt['text'] }}</span>
                            </label>
                        @endforeach
                    </div>

                {{-- Connect the Dots: Randomized Right Column --}}
                @elseif($q->type === 'connect')
                    <div class="connect-container relative p-8 bg-slate-50 dark:bg-slate-900/50 rounded-[2.5rem] transition-colors" id="q-{{ $q->id }}" data-question-id="{{ $q->id }}">
                        <canvas class="absolute top-0 left-0 w-full h-full pointer-events-none z-0"></canvas>
                        <div class="flex justify-between items-center relative z-10 gap-20">
                            <div class="space-y-6 w-full">
                                @foreach($q->answer_details['pairs'] as $pair)
                                    <div class="dot-item flex items-center justify-between p-5 bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm transition-colors" data-side="left" data-value="{{ $pair['left'] }}">
                                        <span class="font-black text-slate-700 dark:text-slate-300 italic transition-colors">{{ $pair['left'] }}</span>
                                        <div class="dot w-6 h-6 bg-blue-500 rounded-full cursor-pointer hover:scale-125 transition"></div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="space-y-6 w-full">
                                @php $rights = collect($q->answer_details['pairs'])->pluck('right')->shuffle(); @endphp
                                @foreach($rights as $r)
                                    <div class="dot-item flex items-center gap-5 p-5 bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm transition-colors" data-side="right" data-value="{{ $r }}">
                                        <div class="dot w-6 h-6 bg-blue-500 rounded-full cursor-pointer hover:scale-125 transition"></div>
                                        <span class="font-black text-slate-700 dark:text-slate-300 italic transition-colors">{{ $r }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="hidden-inputs"></div>
                    </div>

                {{-- Essay: Teacher Review Message --}}
                @elseif($q->type === 'essay')
                    <div class="relative">
                        <textarea name="answers[{{ $q->id }}]" class="w-full p-8 rounded-4xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:border-blue-500 dark:focus:border-blue-400 text-slate-800 dark:text-slate-200 transition-all font-medium text-lg outline-none" rows="5" placeholder="Enter your response..."></textarea>
                        <div class="mt-3 flex items-center gap-2 text-amber-500 font-bold text-xs uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/></svg>
                            Manual Review Required
                        </div>
                    </div>
                @endif
            </div>
        @endforeach

        <button type="button" @click="confirmSubmission()" class="w-full bg-slate-900 text-white py-6 rounded-[2.5rem] font-black text-xl shadow-2xl hover:bg-blue-600 transition-all transform hover:-translate-y-1">
            SUBMIT EXAM
        </button>
    </form>
</div>

{{-- Sticky Timer Bar --}}
@if(isset($exam->duration) && $exam->duration > 0)
    <div id="timer-bar" class="fixed bottom-0 left-0 w-full bg-slate-900/90 backdrop-blur-md text-white py-4 px-8 flex justify-between items-center z-50 shadow-2xl border-t border-slate-700">
        <div class="text-xs font-black uppercase tracking-widest text-slate-400">Time Remaining</div>
        <div class="text-2xl font-black tabular-nums tracking-widest" id="countdown-display">--:--</div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // NAVIGATION LOCKING - Prevent back button
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };

    // Warn before leaving page
    window.onbeforeunload = function() {
        return 'Are you sure you want to leave? Your exam progress will be lost.';
    };

    // TIMER LOGIC
    const durationMinutes = {{ $exam->duration ?? 0 }};
    const form = document.getElementById('exam-form');
    let fiveMinWarningShown = false;
    
    if (durationMinutes > 0) {
        const timerKey = 'exam_start_' + '{{ $exam->uuid }}';
        let startTime = localStorage.getItem(timerKey);
        
        if (!startTime) {
            startTime = Date.now();
            localStorage.setItem(timerKey, startTime);
        }

        const endTime = parseInt(startTime) + (durationMinutes * 60 * 1000);

        function updateTimer() {
            const now = Date.now();
            const distance = endTime - now;

            if (distance < 0) {
                // Time's up - auto submit
                document.getElementById('countdown-display').innerHTML = "TIME UP!";
                localStorage.removeItem(timerKey);
                // Remove beforeunload to allow auto-submit
                window.onbeforeunload = null;
                form.submit();
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('countdown-display').innerHTML = 
                (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                (seconds < 10 ? "0" + seconds : seconds);
            
            const timerBar = document.getElementById('timer-bar');
            
            // 10 seconds warning - pulse animation
            if (distance <= 10000) {
                timerBar.classList.add('animate-pulse');
                timerBar.classList.add('bg-[#E2231A]/90');
                timerBar.classList.remove('bg-slate-900/90', 'bg-[#FF9E18]/90');
            }
            // Last minute warning - orange
            else if (minutes === 0) {
                timerBar.classList.add('bg-[#FF9E18]/90');
                timerBar.classList.remove('bg-slate-900/90');
            }
            // 5 minute warning - show toast once
            else if (minutes === 4 && seconds === 59 && !fiveMinWarningShown) {
                fiveMinWarningShown = true;
                notify('Please stay focused. Proceed with caution.', '5 Minutes Remaining!', 'warning');
            }
        }

        setInterval(updateTimer, 1000);
        updateTimer();
        
        // Clear timer and beforeunload on manual submit
        form.addEventListener('submit', function() {
             localStorage.removeItem(timerKey);
        });
    }

    // Always clear beforeunload on form submit
    form.addEventListener('submit', function() {
        window.onbeforeunload = null;
    });

    window.confirmSubmission = function() {
            confirmAction({
                title: 'Final Submission',
                message: 'Are you sure you want to end the exam? You will not be able to change your answers once submitted.',
                icon: '🚀',
                confirmText: 'Submit Now',
                cancelText: 'Continue Exam',
                onConfirm: 'submitExamForm'
            });
        };

        window.submitExamForm = function() {
            window.onbeforeunload = null;
            if (durationMinutes > 0) { // Only clear timer key if timer was active
                const timerKey = 'exam_start_' + '{{ $exam->uuid }}';
                localStorage.removeItem(timerKey);
            }
            form.submit();
        };
    }


    const containers = document.querySelectorAll('.connect-container');
    const colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4'];

    containers.forEach(container => {
        const canvas = container.querySelector('canvas');
        const ctx = canvas.getContext('2d');
        const qId = container.dataset.questionId;
        const hiddenContainer = container.querySelector('.hidden-inputs');
        let selectedDot = null;
        let connections = [];

        function resize() {
            canvas.width = container.offsetWidth;
            canvas.height = container.offsetHeight;
            draw();
        }
        window.addEventListener('resize', resize);
        setTimeout(resize, 200);

        container.addEventListener('click', (e) => {
            const dotItem = e.target.closest('.dot-item');
            if (!dotItem) return;

            const side = dotItem.dataset.side;
            const value = dotItem.dataset.value;
            const dot = dotItem.querySelector('.dot');

            if (!selectedDot || selectedDot.side === side) {
                if(selectedDot) selectedDot.element.classList.remove('ring-4', 'ring-blue-300');
                selectedDot = { side, value, element: dot };
                dot.classList.add('ring-4', 'ring-blue-300');
            } else {
                const left = side === 'left' ? value : selectedDot.value;
                const right = side === 'right' ? value : selectedDot.value;
                const leftEl = side === 'left' ? dot : selectedDot.element;
                const rightEl = side === 'right' ? dot : selectedDot.element;

                // REPLACEMENT LOGIC: Remove any existing connection for this left or right item
                connections = connections.filter(c => c.left !== left && c.right !== right);

                connections.push({
                    left, right, leftEl, rightEl,
                    color: colors[Math.floor(Math.random() * colors.length)]
                });

                selectedDot.element.classList.remove('ring-4', 'ring-blue-300');
                selectedDot = null;
                updateInputs();
                draw();
            }
        });

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            const containerRect = container.getBoundingClientRect();

            connections.forEach(c => {
                const rectL = c.leftEl.getBoundingClientRect();
                const rectR = c.rightEl.getBoundingClientRect();

                ctx.beginPath();
                ctx.moveTo(rectL.left - containerRect.left + 10, rectL.top - containerRect.top + 10);
                ctx.lineTo(rectR.left - containerRect.left + 10, rectR.top - containerRect.top + 10);
                ctx.strokeStyle = c.color;
                ctx.lineWidth = 4;
                ctx.lineCap = 'round';
                ctx.stroke();
            });
        }

        function updateInputs() {
            hiddenContainer.innerHTML = '';
            connections.forEach(c => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${qId}][${c.left}]`;
                input.value = c.right;
                hiddenContainer.appendChild(input);
            });
        }
    });
});
</script>
@endsection
