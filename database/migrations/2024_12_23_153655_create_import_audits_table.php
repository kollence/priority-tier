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
        Schema::create('import_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_import_id')->constrained();
            $table->integer('row_number');
            $table->string('column_name');
            $table->text('old_value')->nullable();
            $table->text('new_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_audits');
    }
};
