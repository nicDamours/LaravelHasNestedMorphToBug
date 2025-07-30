<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("create view if not exists `nameables` as
                                select name                                               as `name`,
                                     'App\\\\Models\\\\User' collate 'utf8mb4_general_ci' as `entity_type`,
                                     id                                                   as `entity_id` 
                              from users
                              union
                              select name                                                  as `name`,
                                     'App\\\\Models\\\\Group' collate 'utf8mb4_general_ci' as `entity_id`,
                                     id                                                    as `entity_id`
                              from groups
                            ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('drop view if exists `nameables`');
    }
};
