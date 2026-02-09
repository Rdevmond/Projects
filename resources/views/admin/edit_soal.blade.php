@extends('layouts.app')

@section('content')
<div x-data="examBuilder({{ $exam->questions->toJson() }})" class="max-w-5xl mx-auto py-12 px-4 transition-colors" x-cloak>
    <form action="{{ route('admin.exams.update', $exam) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Header Card --}}
        <div class="bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 border-t-4 border-t-[#005073] dark:border-t-[#00bceb] shadow-sm p-10 mb-10 transition-colors">
            <div class="flex justify-between items-center mb-6">
                <span class="px-4 py-1.5 bg-[#005073] dark:bg-[#00bceb] text-white dark:text-slate-900 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md transition-colors">Edit Mode</span>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-black text-slate-400 dark:text-slate-500 hover:text-[#E2231A] dark:hover:text-rose-400 uppercase tracking-widest transition-colors">
                    ✕ Cancel Changes
                </a>
            </div>

            <input type="text" name="exam_title" value="{{ $exam->title }}" required
                class="text-4xl font-black text-[#005073] dark:text-[#00bceb] w-full bg-transparent border-b-2 border-slate-100 dark:border-slate-800 focus:border-[#E2231A] dark:focus:border-[#00bceb] focus:outline-none mb-4 pb-2 transition-all placeholder-slate-200 dark:placeholder-slate-700 transition-colors"
                placeholder="Exam Title">

            <input type="text" name="exam_description" value="{{ $exam->description }}"
                class="text-lg text-slate-400 dark:text-slate-500 w-full bg-transparent border-none focus:ring-0 p-0 transition-colors"
                placeholder="No description provided...">

            <div class="mt-4 flex items-center gap-4">
                <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 transition-colors">
                    <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <input type="number" name="duration" value="{{ $exam->duration }}" min="1"
                        class="bg-transparent border-none p-0 w-24 text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 placeholder-slate-300 dark:placeholder-slate-600 transition-colors"
                        placeholder="Duration">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase transition-colors">Minutes</span>
                </div>
            </div>

            {{-- User Assignment Section --}}
            <div class="mt-6">
                <label class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Assign Students (Optional - Leave empty for all students)
                </label>
                <select name="assigned_users[]" multiple 
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-700 dark:text-slate-200 focus:border-[#00bceb] dark:focus:border-[#00bceb] focus:ring-0 transition-all"
                        size="6">
                    @foreach(\App\Models\User::where('role', 0)->orderBy('name')->get() as $student)
                        <option value="{{ $student->id }}" 
                                {{ $exam->assignedUsers->contains($student->id) ? 'selected' : '' }}
                                class="py-1">
                            {{ $student->name }} ({{ $student->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-1 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Hold Ctrl/Cmd to select multiple students
                </p>
            </div>
        </div>

        {{-- Question Loop --}}
        <template x-for="(question, index) in questions" :key="question.id || index">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm dark:shadow-black/40 border border-slate-200 dark:border-slate-800 mb-8 overflow-hidden transition-all hover:shadow-xl transition-colors">

                <div class="p-8 pb-4">
                    {{-- Grid Header: Text & Type --}}
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                        <div class="md:col-span-8">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#E2231A] dark:bg-rose-500 text-white text-xs font-black shadow-lg shadow-[#E2231A]/20 dark:shadow-none transition-colors" x-text="index + 1"></span>
                                <span class="text-xs font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-[0.2em] transition-colors">Question Text</span>
                            </div>
                            <input type="text" :name="`questions[${index}][text]`" x-model="question.question_text" required
                                class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-transparent rounded-2xl p-4 text-lg font-bold text-slate-800 dark:text-white focus:bg-white dark:focus:bg-slate-700 focus:border-[#E2231A] dark:focus:border-[#00bceb] focus:ring-0 transition-all">
                        </div>

                        <div class="md:col-span-4 transition-colors">
                            <div class="mb-3">
                                <span class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] transition-colors">Type</span>
                            </div>
                            <div class="relative group">
                                <select x-model="question.type" :name="`questions[${index}][type]`"
                                    class="w-full bg-[#005073] dark:bg-[#00bceb] border-2 border-[#005073] dark:border-[#00bceb] rounded-2xl p-4 font-black text-white dark:text-slate-900 focus:bg-[#E2231A] dark:focus:bg-white focus:border-[#E2231A] dark:focus:border-white appearance-none cursor-pointer transition-all shadow-lg pr-12 transition-colors">
                                    <option value="option" class="text-slate-900 dark:text-white bg-white dark:bg-slate-800">Multiple Choice</option>
                                    <option value="connect" class="text-slate-900 dark:text-white bg-white dark:bg-slate-800">Matching Pairs</option>
                                    <option value="essay" class="text-slate-900 dark:text-white bg-white dark:bg-slate-800">Essay / Manual</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-white/70 dark:text-slate-900/70 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Main Question Image --}}
                    <div class="mt-6 flex flex-wrap items-end gap-6 transition-colors">
                        <template x-if="question.context_image_path && !question.new_preview">
                            <div class="relative">
                                <p class="text-[9px] font-black text-[#005073] dark:text-[#00bceb] uppercase mb-1 tracking-widest transition-colors">Current Image:</p>
                                <img :src="`/storage/${question.context_image_path}`" class="h-24 w-40 rounded-xl border-2 border-slate-100 dark:border-slate-800 object-cover shadow-sm bg-slate-50 dark:bg-slate-800 transition-colors">
                            </div>
                        </template>

                        <template x-if="question.new_preview">
                            <div class="relative">
                                <p class="text-[9px] font-black text-[#E2231A] dark:text-rose-400 uppercase mb-1 tracking-widest transition-colors">New Upload:</p>
                                <img :src="question.new_preview" class="h-24 w-40 rounded-xl border-2 border-[#E2231A] dark:border-rose-400 object-cover shadow-md transition-colors">
                            </div>
                        </template>

                        <label class="inline-flex items-center gap-3 px-5 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-[#005073] dark:text-[#00bceb] rounded-xl text-[10px] font-black cursor-pointer border border-slate-200 dark:border-slate-700 transition-all mb-1 transition-colors">
                            <span x-text="question.context_image_path || question.new_preview ? 'CHANGE QUESTION IMAGE' : 'ADD IMAGE'"></span>
                            <input type="file" :name="`questions[${index}][context_image]`" class="hidden"
                                   @change="question.new_preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                    </div>
                </div>

                {{-- Answers Container --}}
                <div class="px-8 pb-8 pt-2">
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-4xl border-2 border-slate-100 dark:border-slate-800 p-6 shadow-inner transition-colors">

                        {{-- Multiple Choice Section --}}
                        <div x-show="question.type === 'option'" class="space-y-3">
                            <template x-for="(opt, optIndex) in question.answer_details.options" :key="optIndex">
                                <div class="flex items-center gap-4 bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm group hover:border-[#00bceb]/50 transition-colors"
                                     x-data="{ optPreview: null }">

                                    {{-- OPTION KEY BADGE --}}
                                    <label class="relative flex flex-col items-center justify-center w-12 h-12 rounded-xl cursor-pointer transition-all duration-300 transition-colors"
                                           :class="opt.is_correct ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 ring-2 ring-emerald-500 ring-offset-2 dark:ring-offset-slate-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-300 dark:text-slate-600 hover:bg-slate-200 dark:hover:bg-slate-700 hover:text-slate-400 dark:hover:text-slate-400'">
                                        <input type="checkbox" :name="`questions[${index}][correct][]`" :value="optIndex" :checked="opt.is_correct"
                                               x-model="opt.is_correct"
                                               class="absolute opacity-0 w-full h-full cursor-pointer">
                                        
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                    </label>

                                    <div class="grow">
                                        <input type="text" :name="`questions[${index}][options][${optIndex}][text]`" x-model="opt.text"
                                            class="w-full border-none bg-transparent focus:ring-0 p-0 text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors" placeholder="Option text...">
                                    </div>

                                    {{-- OPTION IMAGE LOGIC --}}
                                    <div class="flex items-center gap-3 pr-2 transition-colors">
                                        <div x-show="optPreview || opt.image" class="relative">
                                            <img :src="optPreview || '/storage/' + opt.image"
                                                 class="h-10 w-10 rounded-lg object-cover border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-sm transition-colors">
                                            <button type="button" x-show="optPreview" @click="optPreview = null"
                                                    class="absolute -top-1 -right-1 bg-black text-white rounded-full p-0.5 shadow-lg">
                                                <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="4"></path></svg>
                                            </button>
                                        </div>

                                        <label class="p-2 text-slate-400 dark:text-slate-500 hover:text-[#005073] dark:hover:text-[#00bceb] cursor-pointer transition-colors bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-100 dark:border-slate-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <input type="file" :name="`questions[${index}][options][${optIndex}][file]`" class="hidden"
                                                   @change="optPreview = URL.createObjectURL($event.target.files[0])">
                                        </label>

                                        <button type="button" @click="removeOption(index, optIndex)" class="p-2 text-slate-300 dark:text-slate-600 hover:text-[#E2231A] dark:hover:text-rose-400 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <button type="button" @click="addOption(index)" class="flex items-center gap-2 text-[10px] font-black text-[#E2231A] dark:text-rose-400 uppercase tracking-[0.2em] pt-2 ml-4 transition-colors">
                                <span class="bg-[#E2231A] dark:bg-rose-500 text-white rounded-md w-5 h-5 flex items-center justify-center text-lg transition-colors">+</span>
                                Add Choice
                            </button>
                        </div>

                        {{-- Matching Pairs Section --}}
                        <div x-show="question.type === 'connect'" class="space-y-3">
                            <template x-for="(pair, pairIndex) in question.answer_details.pairs" :key="pairIndex">
                                <div class="flex items-center gap-4 transition-colors">
                                    <input type="text" :name="`questions[${index}][pairs][${pairIndex}][left]`" x-model="pair.left"
                                        class="flex-1 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm font-bold shadow-sm dark:shadow-none text-slate-700 dark:text-slate-200 transition-colors" placeholder="Left Side">
                                    <div class="text-[#E2231A] dark:text-rose-400 transition-colors">➔</div>
                                    <input type="text" :name="`questions[${index}][pairs][${pairIndex}][right]`" x-model="pair.right"
                                        class="flex-1 bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm font-bold shadow-sm dark:shadow-none text-slate-700 dark:text-slate-200 transition-colors" placeholder="Right Side">
                                    <button type="button" @click="removePair(index, pairIndex)" class="text-slate-300 dark:text-slate-600 hover:text-[#E2231A] dark:hover:text-rose-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addPair(index)" class="text-[10px] font-black text-[#005073] dark:text-[#00bceb] uppercase tracking-[0.2em] mt-2 transition-colors">+ Add Pair</button>
                        </div>

                        {{-- Essay Section --}}
                        <div x-show="question.type === 'essay'" class="text-center py-6 bg-white/50 dark:bg-slate-900/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 transition-colors">
                            <p class="text-slate-400 dark:text-slate-500 text-xs font-bold italic transition-colors">Manual grading required.</p>
                        </div>
                    </div>
                </div>



                {{-- Footer: Actions --}}
                <div class="bg-slate-50 dark:bg-slate-800 px-10 py-4 flex justify-between items-center border-t border-slate-100 dark:border-slate-800/50 transition-colors duration-300">
                    <div class="flex items-center gap-4 transition-colors">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative w-10 h-5 flex items-center">
                                <input type="checkbox" :name="`questions[${index}][required]`" :checked="question.is_required" class="sr-only peer">
                                <div class="w-full h-full bg-[#002e44] dark:bg-slate-900 rounded-full peer peer-checked:bg-[#E2231A] dark:peer-checked:bg-rose-500 transition-colors"></div>
                                <div class="absolute left-1 w-3 h-3 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 group-hover:text-[#005073] dark:group-hover:text-[#00bceb] uppercase tracking-widest transition-colors">Required</span>
                        </label>

                        {{-- Reorder Buttons --}}
                        <div class="flex items-center bg-white dark:bg-slate-900 rounded-lg p-1 border border-slate-200 dark:border-slate-800 transition-colors">
                            <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-[#005073] dark:hover:text-[#00bceb] hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md transition disabled:opacity-30 disabled:hover:bg-transparent transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" @click="moveDown(index)" :disabled="index === questions.length - 1"
                                class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-[#005073] dark:hover:text-[#00bceb] hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md transition disabled:opacity-30 disabled:hover:bg-transparent transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="button" @click="removeQuestion(index)" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-rose-300 dark:text-rose-500 hover:text-white dark:hover:text-rose-400 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </div>
            </div>
        </template>

        {{-- Add Question Button --}}
        <div class="mb-12 transition-colors">
            <button type="button" @click="addQuestion()" class="w-full py-12 bg-white dark:bg-slate-900 border-4 border-dashed border-slate-200 dark:border-slate-800 rounded-[3rem] text-slate-300 dark:text-slate-700 font-black uppercase tracking-[0.4em] hover:bg-[#f8fafc] dark:hover:bg-slate-800 hover:border-[#E2231A] dark:hover:border-[#00bceb] hover:text-[#E2231A] dark:hover:text-[#00bceb] transition-all shadow-sm group transition-colors">
                <span class="inline-block group-hover:animate-bounce">+ Add Question Node</span>
            </button>
        </div>

        {{-- Action Bar (Static) --}}
        <div class="w-full transition-colors">
            <div class="bg-slate-50 dark:bg-slate-950 border-2 border-slate-200 dark:border-slate-800 border-b-8 border-b-[#E2231A] dark:border-b-[#00bceb] rounded-full p-4 flex items-center justify-between shadow-2xl transition-colors duration-300">
                <div class="pl-6 text-slate-400 dark:text-slate-600">
                    <span class="block text-[8px] font-black uppercase tracking-widest leading-none mb-1">Total nodes</span>
                    <span class="text-xl font-black leading-none text-slate-800 dark:text-white" x-text="questions.length"></span>
                </div>
                <button type="submit" class="bg-[#E2231A] dark:bg-[#00bceb] hover:bg-[#E2231A] dark:hover:bg-[#00bceb] text-white dark:text-slate-900 px-10 py-4 rounded-full font-black uppercase tracking-widest text-xs shadow-xl transition-all active:scale-95 transition-colors">
                    Update Exam Data
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function examBuilder(dbQuestions) {
    return {
        questions: dbQuestions.map(q => ({
            ...q,
            new_preview: null,
            is_required: q.is_required,
            answer_details: {
                options: (q.answer_details && q.answer_details.options) ? q.answer_details.options : [],
                pairs: (q.answer_details && q.answer_details.pairs) ? q.answer_details.pairs : []
            }
        })),

        addQuestion() {
            this.questions.push({
                id: null,
                type: 'option',
                question_text: '',
                is_required: true,
                answer_details: { options: [{text: '', is_correct: false}], pairs: [] },
                new_preview: null
            });
        },

        removeQuestion(index) {
            window.pendingDeleteIndex = index;
            window.confirmAction({
                title: 'Delete Question',
                message: 'Are you sure you want to permanently delete this question node? This action cannot be undone.',
                icon: '🗑️',
                type: 'danger',
                confirmText: 'Delete Question',
                onConfirm: 'executeQuestionDeletion'
            });
        },

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

        addOption(qIndex) {
            this.questions[qIndex].answer_details.options.push({ text: '', is_correct: false, image: null });
        },

        removeOption(qIndex, oIndex) {
            this.questions[qIndex].answer_details.options.splice(oIndex, 1);
        },

        addPair(qIndex) {
            this.questions[qIndex].answer_details.pairs.push({ left: '', right: '' });
        },

        removePair(qIndex, pIndex) {
            this.questions[qIndex].answer_details.pairs.splice(pIndex, 1);
        }
    }
}

window.executeQuestionDeletion = function() {
    const el = document.querySelector('[x-data^="examBuilder"]');
    if (el && window.Alpine) {
        const data = window.Alpine.$data(el);
        if (data && typeof window.pendingDeleteIndex !== 'undefined') {
            data.questions.splice(window.pendingDeleteIndex, 1);
            window.pendingDeleteIndex = undefined;
        }
    }
};
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
