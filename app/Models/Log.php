<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'socket_id',
        'action',
    ];

//    protected $guarded = ['id'];

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }


}
