<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Common\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/home');
    }

    /**
     * show console page
     * @return \Illuminate\Http\Response
     */
    public function console(){
        return view('admin/console');
    }
}
