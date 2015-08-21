<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Session';

    public $timestamps = false;
}
