<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menu_item_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id');
            $table->tinyInteger('type')->default(1)->comment('1:Giris, 2:Cikis');
            $table->string('note');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_stock_histories');
    }
};
