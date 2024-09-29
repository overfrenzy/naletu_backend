<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTableAddQuantityTypeId extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Удалить старый столбец itemQuantityType.
            $table->dropColumn('itemQuantityType');

            // Добавить столбец внешнего ключа для ссылки на таблицу Quantity_types.
            $table->unsignedBigInteger('quantity_type_id')->nullable(); // Сделать обнуляемым, если не все продукты имеют тип количества
            $table->foreign('quantity_type_id')->references('id')->on('quantity_types')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('itemQuantityType');
            $table->dropForeign(['quantity_type_id']);
            $table->dropColumn('quantity_type_id');
        });
    }
}
