<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todoapp extends Model
{
    use HasFactory, SoftDeletes;  

    protected $fillable =['title', 'description', 'is_completed'];
}
