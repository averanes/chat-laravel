<?php

namespace App\Services;


use App\Models\Log;

class LogService
{


    public function saveNewLog($user_id, $socket_id, $action)
    {
//        \Illuminate\Support\Facades\Log::info("UserID: " . $user_id . " socket_id: " . $socket_id);

        $log = new Log();
        $log->user_id = $user_id;
        $log->socket_id = $socket_id;
        $log->action = $action;
        $log->save();

    }

    public function touchLog($socket_id){
        $log = Log::where('socket_id', $socket_id)->first();

        if($log){
            \Illuminate\Support\Facades\Log::info("LOG OK socket_id: " . $socket_id);
            $log->touch();
        }else{
            \Illuminate\Support\Facades\Log::info("************* LOG NOT FOUND socket_id: " . $socket_id);
        }
    }

}
