<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // عمود ID افتراضي
            $table->string('name'); // عمود الاسم
            $table->string('email')->unique(); // عمود البريد الإلكتروني مع تمييز فريد
            $table->string('password'); // عمود كلمة المرور
            $table->timestamps(); // إضافة أعمدة created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
