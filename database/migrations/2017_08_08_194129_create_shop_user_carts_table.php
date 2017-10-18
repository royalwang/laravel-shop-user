<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopAttributeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shop_attribute', function(Blueprint $table)
		{
			$table->integer('id', true)->comment('属性编号');
			$table->string('name', 100)->comment('属性名称');
			$table->text('attr_value', 65535)->comment('属性值列');
			$table->boolean('type')->default(2)->comment('属性值，1，文本，2 单选');
			$table->integer('cate_id')->index('index_cate_id')->comment('所属类型id');
			$table->boolean('show')->default(1)->comment('是否显示。0为不显示、1为显示');
			$table->boolean('sort')->default(1)->comment('排序');
			$table->timestamps();
			$table->softDeletes();
			$table->unique(['cate_id','name'], 'unique_attr_cate_id_name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shop_attribute');
	}

}
