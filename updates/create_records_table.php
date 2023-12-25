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
    const TABLE_NAME = 'bizmark_collector_records';

    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function(Blueprint $table) {
            $table->id();
            $table->boolean('is_read')->default(0);
            $table->string('group')->nullable();
            $table->text('properties');
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
