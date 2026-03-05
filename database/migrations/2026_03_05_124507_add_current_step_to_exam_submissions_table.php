<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->integer('current_step')->default(0)->after('total_questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->dropColumn('current_step');
        });
    }
};
