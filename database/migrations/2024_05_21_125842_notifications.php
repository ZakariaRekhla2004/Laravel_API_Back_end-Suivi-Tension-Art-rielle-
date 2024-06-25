<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender');
            $table->unsignedBigInteger('recever')->nullable();
            $table->dateTime('medecin_id');
            $table->string('message');
            $table->string('title');

            $table->boolean('read')->default(false);
            // $table->string('type');
            $table->timestamps();
            $table->foreign('sender')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('dossier_id')->nullable()->constrained('dossier_medical');
            // $table->foreignId('dossier_id')->nullable()->constrained('dossier_medical');
            $table->foreignId('Appoint_id')->nullable()->constrained('appointement');
            $table->foreign('recever')->references('id')->on('users')->onDelete('cascade');
            
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
