<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Response;

class PagesController extends Controller
{
    public function userProfile(): Response
    {
        $user = User::query()
            ->with('profile')
            ->find(1);

        if (!$user) {
            return response('User not found.');
        }

        $bio = $user->profile?->bio ?? 'No profile yet.';

        return response($user->name.' - '.$bio);
    }

    public function userPosts(): Response
    {
        $user = User::query()
            ->with('posts')
            ->find(1);

        if (!$user) {
            return response('User not found.');
        }

        $lines = [];
        foreach ($user->posts as $post) {
            $content = $post->content ?? '(no content)';
            $title = $post->title ?? '(untitled)';
            $lines[] = $user->name.': '.$content.' - '.$title;
        }

        if (empty($lines)) {
            return response($user->name.' has no posts.');
        }

        return response(implode('<br>', $lines));
    }

    public function studentCourses(): Response
    {
        $student = Student::query()
            ->with('degree')
            ->first();

        if (!$student) {
            return response('No students found yet.');
        }

        return response($student->full_name.' is enrolled in: '.$student->degree->title);
    }

    public function studentCourse(): Response
    {
        return $this->studentCourses();
    }



    public function maintenance(){
        return view('maintenance');
    }
    
}
