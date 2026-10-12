<?php

declare(strict_types=1);

use App\Eloquent;
use Illuminate\Database\Schema\Blueprint;

return new class {
    public function up(): void
    {
        $schema = Eloquent::boot()->schema();

        // Таблица могла быть создана существующим initdb-скриптом.
        if ($schema->hasTable('demo')) {
            return;
        }

        $schema->create('demo', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->timestamp('created_at')->useCurrent();
            $table->string('note', 500);
        });
    }
};
