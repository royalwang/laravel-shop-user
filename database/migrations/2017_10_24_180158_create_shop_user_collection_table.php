<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopUserCollectionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_user_collection', function(Blueprint $table)
		{
			$table->increments('id');
			$table->char('user_id', 32)->comment('用户id');
			$table->boolean('type')->default(1)->comment('收藏的类型 1:商品');
			$table->integer('type_id')->comment('收藏的id');
			$table->timestamps();
			$table->index(['user_id','type','type_id'], 'user_collection_user_id_type_type_id_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_user_collection');
	}

}
