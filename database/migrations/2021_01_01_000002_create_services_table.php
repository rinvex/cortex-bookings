<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('cortex.bookings.tables.services'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('slug');
            $table->json('name');
            $table->json('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('base_cost')->default('0.00');
            $table->decimal('unit_cost')->default('0.00');
            $table->string('currency', 3);
            $table->string('unit')->default('hour');
            $table->smallInteger('maximum_units')->unsigned()->nullable();
            $table->smallInteger('minimum_units')->unsigned()->nullable();
            $table->tinyInteger('is_cancelable')->unsigned()->default(0);
            $table->tinyInteger('is_recurring')->unsigned()->default(0);
            $table->mediumInteger('sort_order')->unsigned()->default(0);
            $table->mediumInteger('capacity')->unsigned()->nullable();
            $table->string('style')->nullable();
            $table->auditableAndTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('cortex.bookings.tables.services'));
    }
}
