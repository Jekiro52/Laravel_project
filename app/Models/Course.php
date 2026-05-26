<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function students(){
    return $this->belongsToMany(Student::class, 'course_students','course_id','student_id');
    }
}
