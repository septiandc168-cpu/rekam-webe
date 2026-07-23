<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Fix: subject_id must be string to support UUID primary keys
     * from RencanaKegiatan and LaporanKegiatan models.
     */
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            // Drop the old morph index (named 'subject')
            $table->dropIndex('subject');

            // Change subject_id from bigint to string(36) to hold UUIDs
            $table->string('subject_id', 36)->nullable()->change();

            // Recreate the index
            $table->index(['subject_type', 'subject_id'], 'subject');
        });
    }

    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropIndex('subject');
            $table->unsignedBigInteger('subject_id')->nullable()->change();
            $table->index(['subject_type', 'subject_id'], 'subject');
        });
    }
};
