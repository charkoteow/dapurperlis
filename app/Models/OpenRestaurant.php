<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenRestaurant extends Model
{
    public $table = 'open_restaurant';
    public $primaryKey = 'id';

    public $fillable = [
        'restaurant_id',
        'day_of_week',
        'open_time',
        'close_time'
    ];

        /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'restaurant_id' => 'integer',
        'day_of_week' => 'integer',
        'open_time' => 'time',
        'close_time' => 'time'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'day_of_week' => 'required'
    ];
}
