<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function home()
    {
        return view('frontend.frontend-app');
    }
    public function postDetail()
    {
        return view('frontend.post-details');
    }
}
