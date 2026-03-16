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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('photo_visible')->default(true)->after('profile_photo');
            $table->boolean('interests_visible')->default(true)->after('photo_visible');
            $table->boolean('bio_visible')->default(true)->after('interests_visible');
        });
        // We use raw statement to make it a LONGBLOB, to avoid 64kb limit of normal BLOB
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY profile_photo LONGBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'photo_visible',
                'interests_visible',
                'bio_visible'
            ]);
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY profile_photo VARCHAR(255)');
    }
};
