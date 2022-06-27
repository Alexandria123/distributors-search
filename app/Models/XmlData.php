<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\MockObject\Method;

class XmlData extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['regname', 'city', 'email', 'domain'];
    protected $casts = ['email'=>'array', 'domain'=>'array'];

}
