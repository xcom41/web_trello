<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function up(): void
    {
        Capsule::schema()->create('users', function ($table): void {
            $table->increments('user_id');
            $table->string('email', 255);
            $table->string('password_hash', 255);
            $table->string('name', 255);
            $table->timestamp('created_datetime');
            $table->timestamp('updated_datetime')->nullable()->default(null);
        });

        Capsule::connection()->statement(
            'CREATE UNIQUE INDEX idx_email ON users (email)'
        );
    }
};
