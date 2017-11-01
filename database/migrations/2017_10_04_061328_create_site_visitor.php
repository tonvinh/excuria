<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteVisitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('site_visitor', function (Blueprint $table) {
			$table->increments('site_visitor_id');
			$table->timestamp('site_visit_date');
			$table->macAddress('ip_address'); /*The source IP Address*/
			$table->string('country_isp_name', 255); /*The name of the detected COUNTRY name received from the ISP*/
			$table->integer('country_id'); /*The translated COUNTRY_ID from Excuri COUNTRY table if there is a match*/
			$table->string('city_isp_name', 255); /*The name of the detected CITY name received from the ISP*/
			$table->integer('city_id'); /*The translated CITY_ID from Excuri CITY table if there is a match*/
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('site_visitor');
	}
}
