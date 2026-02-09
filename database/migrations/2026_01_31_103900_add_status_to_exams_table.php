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
    // status: 'active', 'inactive'
    $table->string('status')->default('inactive');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
