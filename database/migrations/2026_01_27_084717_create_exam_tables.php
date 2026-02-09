<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table 1: The Exam Form itself
        Schema::create('exam_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table 2: The Questions inside the form
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            // Link to the parent Exam
            $table->foreignId('exam_form_id')->constrained('exam_forms')->onDelete('cascade');

            $table->text('question_text');
            $table->string('type'); // 'option', 'essay', 'picture', 'connect'
            $table->string('context_image_path')->nullable(); // If the question has an image
            $table->boolean('is_required')->default(false);

            // This JSON column is magic. It will store:
            // - The list of Options (for multiple choice)
            // - The list of Pairs (for connect the dots)
            // - The Correct Answer Key (index or text)
            // - File paths for picture answers
            $table->json('answer_details')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exam_forms');
    }
};
