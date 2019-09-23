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
            $table->enum('type', ['dropdown', 'date', 'file', 'text', 'freetext'])->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->enum('mandatory', [true, false])->nullable()->default('');
            $table->enum('editable_by_user', [true, false])->nullable()->default('');
            $table->enum('is_default', [true, false])->nullable()->default('');
            $table->unsignedInteger('sequence_no');
            $table->timestamps();
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
