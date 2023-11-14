<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class BackgroundItems extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('background_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('user')->nullable();
            $table->string('btm_index')->nullable();
            $table->string('name')->nullable();
            $table->string('developer_name')->nullable();
            $table->text('url')->nullable();
            $table->string('team_id')->nullable();
            $table->text('assoc_bundle_id')->nullable();
            $table->string('type')->nullable();
            $table->text('identifier')->nullable();
            $table->integer('generation')->nullable();
            $table->text('parent_id')->nullable();
            $table->text('executable_path')->nullable();
            $table->integer('state_enabled')->nullable();
            $table->integer('state_allowed')->nullable();
            $table->integer('state_visible')->nullable();
            $table->integer('state_notified')->nullable();
            $table->text('embedded_item_ids')->nullable();

            $table->index('serial_number');
            $table->index('user');
            $table->index('btm_index');
            $table->index('name');
            $table->index('developer_name');
            $table->index('team_id');
            $table->index('type');
            $table->index('generation');
            $table->index('state_enabled');
            $table->index('state_allowed');
            $table->index('state_visible');
            $table->index('state_notified');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('background_items');
    }
}
