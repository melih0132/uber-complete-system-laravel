<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Course;
use App\Models\Reservation;

use Carbon\Carbon;

class ServiceCourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();

        return view('service-course.index', compact('courses'));
    }

    public function analyse()
    {
        $performances = true;

        return view('service-course.analyse', compact('performances'));
    }
}
