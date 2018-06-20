<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','user_to_id','report','reason','level'
    ];
    public function user_report(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function report_user(){
        return $this->belongsTo(User::class,'user_to_id');
    }
}
