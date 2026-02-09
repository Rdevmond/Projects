<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
        });

        // Backfill existing users
        $users = \DB::table('users')->get();
        foreach ($users as $user) {
            $username = explode('@', $user->email)[0];
            $originalUsername = $username;
            $counter = 1;
            
            // Ensure uniqueness
            while (\DB::table('users')->where('username', $username)->exists()) {
                $username = $originalUsername . $counter;
                $counter++;
            }
            
            \DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
