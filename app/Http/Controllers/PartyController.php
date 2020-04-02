<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartyController extends Controller
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
     * Show the application customerList.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerList()
    {
        return view('party/customerList');
    }


    /**
     * Show the application customerList.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerAdd()
    {
        return view('party/customerAdd');
    }

    /**
     * Show the application vendorList.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vendorList()
    {
        return view('party/vendorList');
    }

    /**
     * Show the application customerList.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function vendorAdd()
    {
        return view('party/vendorAdd');
    }
}
