<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class TrigramIndex extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Db::statement("CREATE EXTENSION pg_trgm;");
         Db::statement("CREATE INDEX idx_trigram_searchable ON person USING gin (searchable gin_trgm_ops);");
        // Db::statement("SELECT set_limit(0.1);");
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
