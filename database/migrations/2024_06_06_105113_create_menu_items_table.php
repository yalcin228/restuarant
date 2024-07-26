<?php

use App\Http\Enums\ProductTypeEnum;
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
            $table->integer('stock')->nullable();
            $table->foreignId('menu_id');
            $table->string('type')->nullable()->comment('adet,kq');
            $table->tinyInteger('show_qr')->default(1)->comment('1:qr menu-da gosterilecek, 2:qr menu-da gosterilmeyecek');
            $table->integer('stock_tracking_quantity')->nullable();
            $table->tinyInteger('is_stock_tracking')->default(2)->comment("1:Kritik miktar aktiv, 2:deaktiv");
            $table->tinyInteger('is_stock')->default(1)->comment("1:Stoklu, 2:Stoksuz");
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
