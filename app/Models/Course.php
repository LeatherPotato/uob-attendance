<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Course extends Model
{
    use Notifiable;

    /**
    * @var list<string>
    */
    protected $fillable = [
        'name'
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'course_module');
    }
}
