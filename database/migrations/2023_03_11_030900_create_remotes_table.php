<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->datetime('from_datetime');
            $table->datetime('to_datetime');
            $table->integer('total_hours');
            $table->string('reason');
            $table->string('evident');
            $table->unsignedBigInteger('approver_id');
            $table->foreign('approver_id')->references('id')
                ->on('users');
            $table->text('comment');
            $table->tinyInteger('status')->comment('1: Đăng ký remote, 2: Duyệt đăng ký,  3: Không cho phép, 4: Hủy');
            $table->softDeletes();
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
        Schema::dropIfExists('remotes');
    }
}
