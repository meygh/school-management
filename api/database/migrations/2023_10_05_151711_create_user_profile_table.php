<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->foreign('user_id')->references('id')
                ->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedBigInteger('neighborhood_id')->nullable();
            $table->foreign('neighborhood_id')->references('id')
                ->on('neighborhoods')->nullOnDelete();

            $table->char('national_code', 10)->nullable();

            $table->string('avatar', 34)->nullable();
            $table->string('personal_photo', 34)->nullable();
            $table->string('mobile', 11)->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('address')->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('work_address')->nullable();
            $table->string('work_phone', 11)->nullable();
            $table->text('cv')->nullable();

            $table->unsignedBigInteger('education_certificate')->nullable();
            $table->foreign('education_certificate')->references('id')
                ->on('education_degrees')->nullOnDelete();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')
                ->on('users')->nullOnDelete();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')
                ->on('users')->nullOnDelete();

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')
                ->on('users')->nullOnDelete();

            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
}
