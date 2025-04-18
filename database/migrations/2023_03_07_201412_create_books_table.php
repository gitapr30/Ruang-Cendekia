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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('kode_buku')->unique();
            $table->string('penulis');
            $table->text('description');
            $table->string('image');
            $table->string('penerbit');
            $table->integer('stok');
            $table->date('thn_terbit');
            $table->string('suka');
            $table->string('penonton');
            $table->string('status')->default('ready');
            $table->foreignId('user_id');
            $table->foreignId('category_id');
            $table->unsignedBigInteger('rak_id')->nullable();  // Add rak_id
            $table->foreign('rak_id')->references('id')->on('bookshelves')->onDelete('set null');  // Set foreign key constraint
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
    Schema::table('books', function (Blueprint $table) {
        $table->dropForeign(['rak_id']);
        $table->dropColumn('rak_id');
    });
}
};
