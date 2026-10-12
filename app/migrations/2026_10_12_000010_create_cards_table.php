<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

return new class {
    public function up(): void
    {
        $schema = Capsule::schema();

        $schema->create('cards', function ($table): void {
            $table->increments('card_id');
            $table->integer('board_id');
            $table->integer('column_id');
            $table->smallInteger('sort_index');
            $table->string('title', 255);
            $table->text('content')->nullable()->default(null);
            $table->string('color', 6)->nullable()->default(null);
            $table->boolean('is_archived')->default(false);
            $table->timestamp('created_datetime');
            $table->timestamp('updated_datetime')->nullable()->default(null);

            $table->foreign('board_id')
                ->references('board_id')
                ->on('boards')
                ->cascadeOnDelete();

            $table->foreign('column_id')
                ->references('column_id')
                ->on('board_columns')
                ->cascadeOnDelete();
        });

        Capsule::connection()->statement(
            'CREATE INDEX idx_active_board_id
             ON cards (board_id)
             WHERE is_archived = false'
        );
    }
};
