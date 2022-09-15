<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seed extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "seeds";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class',
    ];

    /**
     * @param  string  $value
     * @return void
     */
    public function setClass(string $value)
    {
        $this->attributes['class'] = $value;
    }

    /**
     * @return string
     */
    public function getClass() : string
    {
        return $this->class;
    }
}
