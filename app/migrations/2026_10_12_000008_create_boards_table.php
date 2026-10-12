<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function up(): void
    {
        Capsule::schema()->create('boards', function ($table): void {
            $table->increments('board_id');
            $table->string('title', 255);
            $table->integer('owner_user_id');
            $table->boolean('is_public')->default(false);
            $table->timestamp('created_datetime');
            $table->timestamp('updated_datetime')->nullable()->default(null);

            $table->foreign('owner_user_id')
                ->references('user_id')
                ->on('users')
                ->restrictOnDelete();
        });
    }
};
