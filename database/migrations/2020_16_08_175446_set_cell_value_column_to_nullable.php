<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetCellValueColumnToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_entry_cell', function(Blueprint $table) {
            $table->text('value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \BristolSU\Module\DataEntry\Models\Cell::whereNull('value')->update(['value' => '']);
        Schema::table('data_entry_cell', function(Blueprint $table) {
            $table->text('value')->nullable(false)->change();
        });
    }
}
