<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

final class Demo extends Model
{
    protected $table = 'demo';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'note',
    ];
}
