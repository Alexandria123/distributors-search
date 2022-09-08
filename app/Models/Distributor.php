<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'region_id', 'city_id', 'name', 'address', 'emails', 'domains', 'phone', 'status'];
    public $timestamps = false;

    public function region(){
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
    public function city(){
        return $this->belongsTo(City::class);
    }
}
