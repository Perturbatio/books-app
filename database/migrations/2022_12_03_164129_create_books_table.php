<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('isbn', 32)->unique('unq_isbn')->comment('field size allows for expansion of ISBN length and hyphens');
            $table->string('title', 255)->index('idx_book_title');
            $table->decimal('price', 10, 2)->index('idx_price')->default('0.00');
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};
