<?php

namespace App\Models;

use App\Traits\Uuid\UseUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Concerns\HasTimestamps, Model, SoftDeletes};

class TableModelAbstract extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTimestamps;
    use UseUuid;
}
