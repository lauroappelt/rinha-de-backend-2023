<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class AlterTablePerson extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('person', function (Blueprint $table) {
            $table->jsonb('stack');
            $table->string('searchable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            //
        });
    }
}
