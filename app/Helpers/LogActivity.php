<?php
namespace App\Helpers;

use Request;
use App\Models\LogActivity as LogActivityModel;

class LogActivity
{
    public static function addToLog($subject) {
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
//        dd($log);
        LogActivityModel::create($log);

    }

    public static function LogActivityList() {
        return LogActivityModel::latest()->get();
    }
}
