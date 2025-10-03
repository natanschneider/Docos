<?php

declare(strict_types=1);

use Hidehalo\Nanoid\Client as Nanoid;
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
        Schema::create('types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
        });

        Schema::create('columns', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('doc_file', 500)->nullable();
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('type_id');
            $table->string('public_key', 15);
            $table->timestamps();

            $table->foreign('table_id')->references('id')->on('tables');
            $table->foreign('type_id')->references('id')->on('types');
        });

        Schema::create('indexes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('column_id');

            $table->foreign('column_id')->references('id')->on('columns');
        });

        Schema::create('relationships', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('primary_key_id');
            $table->unsignedBigInteger('foreign_key_id');

            $table->foreign('primary_key_id')->references('id')->on('columns');
            $table->foreign('foreign_key_id')->references('id')->on('columns');
        });

        Schema::create('constraints', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
        });

        Schema::create('column_constraints', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('column_id');
            $table->unsignedBigInteger('constraint_id');

            $table->foreign('column_id')->references('id')->on('columns');
            $table->foreign('constraint_id')->references('id')->on('constraints');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
        Schema::dropIfExists('indexes');
        Schema::dropIfExists('relationships');
        Schema::dropIfExists('types');
        Schema::dropIfExists('constraints');
        Schema::dropIfExists('column_constraints');
    }
};
