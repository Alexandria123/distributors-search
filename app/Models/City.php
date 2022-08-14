<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable=['name'];
    public function distributors(){
        return $this->hasMany(Distributor::class);
    }
    public function region(){
        return $this->belongsTo(Region::class);
    }
}
