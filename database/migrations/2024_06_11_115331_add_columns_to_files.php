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
            // $table->string('mime_type', 255)->after('');
            $table->unsignedBigInteger('file_mime_type_id')->after('size')->nullable();
            $table->foreign('file_mime_type_id')->references('id')->on('file_mime_types')->onDelete('set null');

            $table->unsignedBigInteger('file_type_id')->after('file_mime_type_id')->nullable();
            $table->foreign('file_type_id')->references('id')->on('file_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['file_mime_type_id']);
            $table->dropColumn('file_mime_type_id');
        });
    }
};
