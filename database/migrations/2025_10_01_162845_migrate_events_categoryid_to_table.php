<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //To update one-to-many to many-to-many
        if (Schema::hasColumn('events', 'category_id')) {
            $rows = DB::table('events')->whereNotNull('category_id')->get();
            foreach ($rows as $r) {
                    DB::table('category_event')->insert([
                        'event_id'    => $r->id,
                        'category_id' => $r->category_id,
                    ]);
                
            }

                //Remove old column
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('category_id');
            });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       // Re-create column if rolling back (nullable to avoid data loss)
       if (!Schema::hasColumn('events', 'category_id')) {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->nullable()->after('capacity');
        });
    }
}
};
