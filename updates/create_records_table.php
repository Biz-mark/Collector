<?php namespace BizMark\Collector\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateRecordsTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
return new class extends Migration
{
    /**
     * up builds the migration
     */
    public function up(): void
    {
        Schema::create('bizmark_collector_records', function(Blueprint $table) {
            $table->id();
            $table->boolean('is_read')->default(0);
            $table->string('ip')->nullable();
            $table->string('group')->nullable();
            $table->text('properties');
            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down(): void
    {
        Schema::dropIfExists('bizmark_collector_records');
    }
};
