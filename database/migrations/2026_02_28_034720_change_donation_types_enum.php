<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert enum to string temporarily
        DB::statement("ALTER TABLE donations MODIFY COLUMN type VARCHAR(50)");
        
        // Update existing values
        DB::table('donations')->where('type', 'zakat')->update(['type' => 'wakaf_pembangunan']);
        DB::table('donations')->where('type', 'wakaf')->update(['type' => 'wakaf_produktif']);
        DB::table('donations')->where('type', 'donasi')->update(['type' => 'donasi_pendidikan']);
        
        // Convert back to enum with new values
        DB::statement("ALTER TABLE donations MODIFY COLUMN type ENUM('wakaf_pembangunan', 'wakaf_produktif', 'donasi_pendidikan')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert enum to string temporarily
        DB::statement("ALTER TABLE donations MODIFY COLUMN type VARCHAR(50)");
        
        // Revert values
        DB::table('donations')->where('type', 'wakaf_pembangunan')->update(['type' => 'zakat']);
        DB::table('donations')->where('type', 'wakaf_produktif')->update(['type' => 'wakaf']);
        DB::table('donations')->where('type', 'donasi_pendidikan')->update(['type' => 'donasi']);
        
        // Convert back to enum with old values
        DB::statement("ALTER TABLE donations MODIFY COLUMN type ENUM('donasi', 'zakat', 'wakaf')");
    }
};
