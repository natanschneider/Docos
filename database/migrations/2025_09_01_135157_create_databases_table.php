<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('engines', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
        });

        Schema::create('databases', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('engine_id');
            $table->uuid('uuid')->default(Uuid::uuid4());
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('engine_id')->references('id')->on('engines');
        });

        Schema::create('application_databases', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('database_id');

            $table->foreign('application_id')->references('id')->on('applications');
            $table->foreign('database_id')->references('id')->on('databases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engines');
        Schema::dropIfExists('databases');
        Schema::dropIfExists('application_databases');
    }
};
