<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_forms', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable()->unique();
        });

        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable()->unique();
        });

        // Backfill existing records
        DB::table('exam_forms')->whereNull('uuid')->get()->each(function ($row) {
            DB::table('exam_forms')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
        });

        DB::table('exam_submissions')->whereNull('uuid')->get()->each(function ($row) {
            DB::table('exam_submissions')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
        });

        // Make it non-nullable after backfill
        Schema::table('exam_forms', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });

        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_forms', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
