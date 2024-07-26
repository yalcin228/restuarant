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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('type')->default(1)->comment('1:qr menu-da gosterilecek, 2:qr menu-da gosterilmeyecek');
            $table->tinyInteger('print')->default(null)->nullable()->comment("1:Mutfak yaziciya yonlecek, 2:Bar yaziciya yonlenecek");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
