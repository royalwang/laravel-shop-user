<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopUserAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_user_address', function(Blueprint $table)
		{
			$table->increments('id')->comment('主键ID');
			$table->char('user_id', 32)->index('user_id_index')->comment('用户ID');
			$table->char('byname', 20)->default('')->comment('地址别名');
			$table->integer('province')->comment('省的区域ID');
			$table->integer('city')->comment('城市的区域ID');
			$table->integer('county')->comment('区的区域ID');
			$table->string('region_desc', 80)->default('')->comment('省市区的中文描述');
			$table->string('address')->comment('详细地址');
			$table->string('consignee', 50)->default('')->comment('收件人');
			$table->string('telephone', 11)->default('')->comment('收件人电话');
			$table->boolean('default')->default(0)->comment('默认值,0:不默认,1:默认');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_user_address');
	}

}
