<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalFieldsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('additional_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->enum('type', ['dropdown', 'date', 'file', 'text', 'freetext', 'number', 'password', 'email'])->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->enum('mandatory', [true, false])->nullable()->default('');
            $table->enum('editable_by_user', [true, false])->nullable()->default('');
            $table->enum('is_default', [true, false])->nullable()->default('');
            $table->enum('available_for_filters', [true, false])->nullable()->default('');
            $table->string('column_name')->nullable();
            $table->text('validations')->nullable();
            $table->unsignedInteger('sequence_no')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('additional_fields');
    }

}
