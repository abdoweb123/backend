<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable =[
        'id',
        'model',
        'key',
        'member_id',
        'is_read',
        'pushed',
        'fcm_token',
        'fcm_status',
        'lang_code'
    ];


    /**
     * @return mixed
     */
    public function getModelAttribute()
    {
        if($this->is_read ==22){
            return $this->getAttributes()['model'];
        }
        try {
            $r = unserialize($this->getAttributes()['model']);
        }catch (\Exception $e){
            return null;
        }
        return $r;
    }

    /**
     * @param $value
     */
    public function setModelAttribute($value)
    {
        $this->attributes['model'] = serialize($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(User::class);
    }
}
