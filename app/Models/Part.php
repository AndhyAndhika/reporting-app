<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory;
    protected $guarded = [];
    use SoftDeletes;

    /* Relational to user by created */
    public function created_user(){
        return $this->belongsTo(User::class, 'created_by', 'nomor_pegawai');
    }

    /* Relational to user by updated */
    public function updated_user(){
        return $this->belongsTo(User::class, 'updated_by', 'nomor_pegawai');
    }

    /* Relations to rejects */
    public function RejectOnPart(){
        return $this->hasMany(RejectOnPart::class);
    }
}
