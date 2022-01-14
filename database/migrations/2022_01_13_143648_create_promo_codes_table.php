<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("event_id")->constrained("events");
            $table->string("code", 6);
            $table->decimal("amount", 19, 2);
            $table->dateTime("expires_at");
            $table->boolean("is_active")->default(true);
            $table->decimal("radius")->nullable();
            $table->string("radius_unit")->nullable();
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
        Schema::dropIfExists('promo_codes');
    }
}
