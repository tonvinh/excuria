<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSearchBox extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('search_box', function (Blueprint $table) {
			$table->renameColumn('id', 'search_box_id');
			$table->renameColumn('field_name', 'column_name');
			$table->boolean('status')->default(1); /*  "1" for active entry and "0" for inactive entry */
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('search_box');
	}
}
