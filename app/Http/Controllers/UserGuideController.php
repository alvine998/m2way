<?php
namespace App\Http\Controllers;

class UserGuideController extends Controller
{
    public function index()
    {
        return view('user-guide.index')->with('pageTitle', __('app.user_guide'));
    }
}
