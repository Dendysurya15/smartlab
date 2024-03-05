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
    public function up()
    {
        Schema::create('track_parameter', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah');
            $table->integer('totalakhir');
            $table->integer('personel')->nullable();
            $table->integer('alat')->nullable();
            $table->integer('bahan')->nullable();
            $table->string('id_tracksampel', 15);
            $table->integer('id_parameter');
            $table->timestamps();
        });

        // Insert existing data
        // DB::table('track_parameter')->insert([
        //     // Insert your existing data here
        // ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_parameter');
    }
};
