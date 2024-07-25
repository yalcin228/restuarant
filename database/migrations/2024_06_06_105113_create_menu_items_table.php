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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->foreignId('menu_id');
            $table->tinyInteger('type')->default(1)->comment('1:qr menu-da gosterilecek, 2:qr menu-da gosterilmeyecek');
            $table->tinyInteger('stock_tracking')->default(2)->comment("1:Kritik miktar aktiv, 2:deaktiv");
            $table->integer('ordinal_number')->default(1);
            $table->time('order_start_time');
            $table->time('order_end_time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
