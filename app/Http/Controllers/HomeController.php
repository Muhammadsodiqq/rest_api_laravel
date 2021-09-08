<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\SmsService\SmsService;
use Illuminate\Http\Request;

class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __constructor() {

    }

    public function myTestAddToLog () {
        LogActivity::addToLog('My testing Add To Logg');
    }

    public function logActivity()
    {
        $logs = LogActivity::LogActivityList();
        return view('logActivity',compact('logs'));
    }
    public function index () {
        return view("form");
    }

    public function run (Request $request) {
        $send = new SmsService();
        $send->send($request->phone,$request->message);
        $uniqid = uniqid();
        $rand_start = rand(1, 5);
        $rand_8_char = substr($uniqid, $rand_start, 6);
        dd($rand_8_char);
    }
}
