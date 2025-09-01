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
        Schema::create('endpoints', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('doc_file', 500)->nullable();
            $table->unsignedBigInteger('application_id');
            $table->uuid('uuid')->default(Uuid::uuid4());
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications');
        });

        Schema::create('endpoint_tables', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('endpoint_id');
            $table->unsignedBigInteger('table_id');

            $table->foreign('endpoint_id')->references('id')->on('endpoints');
            $table->foreign('table_id')->references('id')->on('tables');
        });

        Schema::create('endpoint_columns', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('endpoint_id');
            $table->unsignedBigInteger('column_id');

            $table->foreign('endpoint_id')->references('id')->on('endpoints');
            $table->foreign('column_id')->references('id')->on('columns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endpoints');
        Schema::dropIfExists('endpoint_tables');
        Schema::dropIfExists('endpoint_columns');
    }
};
