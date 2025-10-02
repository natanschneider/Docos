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
        Schema::create('tables', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('doc_file', 500)->nullable();
            $table->unsignedBigInteger('database_id');
            $table->string('public_key', 15)->default((new Nanoid())->generateId(15));
            $table->timestamps();

            $table->foreign('database_id')->references('id')->on('databases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
