<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['user_id','date','locationX','locationY','type','description','notified'];
}
