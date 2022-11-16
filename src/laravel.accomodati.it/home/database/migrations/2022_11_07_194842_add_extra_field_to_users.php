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
        Schema::table('users', function (Blueprint $table) {

            $table->string('first_name')->after('name')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('city')->after('last_name')->nullable();
            $table->string('country')->after('city')->nullable();
            $table->string('address')->after('country')->nullable();
            $table->string('postcode')->after('address')->nullable();
            $table->string('company')->after('postcode')->nullable();
            $table->string('vat')->after('company')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('city');
            $table->dropColumn('country');
            $table->dropColumn('address');
            $table->dropColumn('postcode');
            $table->dropColumn('company');
            $table->dropColumn('vat');
        });
    }
};
