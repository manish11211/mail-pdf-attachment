<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['id', 'name', 'price', 'category_id', 'created_at', 'updated_at'];
}
