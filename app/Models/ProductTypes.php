<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTypes extends Model {

    protected $table = 'product_types';

    public function parent(){
        return $this->belongsTo('product_types', 'parent_id');
    }

    public function child(){
        return $this->hasMany('product_types', 'parent_id');
    }

}

