<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function up(): void
    {
        Capsule::schema()->create('board_columns', function ($table): void {
            $table->increments('column_id');
            $table->integer('board_id');
            $table->smallInteger('ordinal_position');
            $table->string('title', 255);
            $table->string('color', 6)->nullable()->default(null);

            $table->foreign('board_id')
                ->references('board_id')
                ->on('boards')
                ->cascadeOnDelete();
        });

        Capsule::connection()->statement(
            'CREATE INDEX idx_board_id ON board_columns (board_id)'
        );
    }
};
