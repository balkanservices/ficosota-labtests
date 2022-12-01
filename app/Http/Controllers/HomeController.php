<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
//        $request->user()->authorizeRoles(['user', 'admin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    /**
     * Redirect to the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function oldHome()
    {
        return redirect()->route('home');
    }
}
