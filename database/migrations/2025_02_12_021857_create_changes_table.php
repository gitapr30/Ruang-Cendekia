<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('logo');
            $table->string('image');
            $table->string('nama_website');
            $table->string('alamat');
            $table->string('no_telp');
            $table->string('email');
            $table->string('maps');
            $table->string('tittle');
            $table->string('description');
            $table->string('content');
            $table->string('footer');
            $table->string('denda');
            $table->string('max_peminjaman');
            $table->string('waktu_operasional');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('changes');
    }
};
