<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'catalog_products';

    static $rules=[
        'name'=>'required',
        'description'=>'required',
    ];


    protected $fillable = [
        'name', 
        'description', 
        'height',
        'length',  
        'width'
    ];    

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
