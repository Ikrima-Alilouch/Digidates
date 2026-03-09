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
            // Extra profielgegevens volgens ERD
            $table->string('first_name', 100)->nullable()->after('id');
            $table->string('middle_name', 100)->nullable()->after('first_name');
            $table->string('last_name', 100)->nullable()->after('middle_name');

            $table->string('country', 100)->nullable()->after('name');
            $table->string('gender', 50)->nullable()->after('country');
            $table->string('location', 225)->nullable()->after('gender');
            $table->dateTime('birthdate')->nullable()->after('location');
            $table->text('biography')->nullable()->after('birthdate');

            // Opslag als pad naar bestand i.p.v. BLOB voor eenvoud
            $table->string('profile_photo')->nullable()->after('biography');

            // Banned en rol-functionaliteit
            $table->boolean('banned')->default(false)->after('remember_token');
            $table->string('role', 20)->default('user')->after('banned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'country',
                'gender',
                'location',
                'birthdate',
                'biography',
                'profile_photo',
                'banned',
                'role',
            ]);
        });
    }
};

