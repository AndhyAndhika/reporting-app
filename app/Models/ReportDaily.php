<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDaily extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = [];

    /* relation to part */
    public function toParts()
    {
        return $this->belongsTo(Part::class, 'parts_id', 'id');
    }

    /* relation to reject */
    public function toRejects()
    {
        return $this->belongsTo(Reject::class, 'rejects_id', 'id');
    }
}
