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
        Schema::table('exam_forms', function (Blueprint $table) {
            $table->boolean('randomize_questions')->default(false)->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_forms', function (Blueprint $table) {
            $table->dropColumn('randomize_questions');
        });
    }
};
