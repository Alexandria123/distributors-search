<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name'];

    //Чтобы получить города определенного региона
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class);
    }

}
