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
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('book_id');
            $table->string('status');
            $table->string('keterangan')->nullable()->after('status');
            $table->integer('denda')->default(0)->after('keterangan');
            $table->date('tanggal_pinjam'); // Ubah string ke date
            $table->date('tanggal_kembali'); // Ubah string ke date
            $table->date('tanggal_dikembalikan')->nullable()->after('tanggal_kembali');
            $table->string('kode_peminjaman')->unique();
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
    Schema::table('borrows', function (Blueprint $table) {
        $table->dropColumn('keterangan');
            $table->dropColumn('denda');
    });
}

};
