<?php

declare(strict_types=1);

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
        Schema::create('screens', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('doc_file', 500)->nullable();
            $table->unsignedBigInteger('application_id');
            $table->string('public_key', 15);
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications');
        });

        Schema::create('screen_columns', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('screen_id');
            $table->unsignedBigInteger('column_id');

            $table->foreign('screen_id')->references('id')->on('screens');
            $table->foreign('column_id')->references('id')->on('columns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screens');
        Schema::dropIfExists('screen_columns');
    }
};
