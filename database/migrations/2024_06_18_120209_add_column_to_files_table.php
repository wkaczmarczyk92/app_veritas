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
        Schema::table('files', function (Blueprint $table) {
            $table->unsignedBigInteger('file_mime_type_id')->nullable()->after('size');
            $table->foreign('file_mime_type_id')->references('id')->on('file_mime_types');

            $table->unsignedBigInteger('file_type_id')->nullable()->after('file_mime_type_id');
            $table->foreign('file_type_id')->references('id')->on('file_types');
            // $table->boolean('is_media_library')->default(false)->after('file_mime_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign('files_file_mime_type_id_foreign');
            $table->dropColumn('file_mime_type_id');

            $table->dropForeign('files_file_type_id_foreign');
            $table->dropColumn('file_type_id');
            // $table->dropColumn('is_media_library');
        });
    }
};
