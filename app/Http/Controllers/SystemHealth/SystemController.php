<?php

namespace App\Http\Controllers\SystemHealth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SystemController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    /**
     * Database Status Checker.
     * @param DatabaseConnection-object
     */
    
    private function getDatabaseStatus($connection) {
        $database_status = 'OK';
        try{
            DB::connection($connection)->getPdo();
        } catch(\Exception $e) {
            $database_status = 'Failed to connect';
        }
        return $database_status;
    }

    /**
     * Redis Instance Status Checker.
     * @param RedisConnection-object
     */
    private function getRedisSatus($connection) {
        $redis_status = 'OK';
        try{
            $redis = Redis::conection($connection);
            $redis->connect();
            $redis->disconnect();
        } catch(\Exception $e) {
            $redis_status = 'Failed to connect';
        }
        return $redis_status;
    }

    public function getSystemStatus()
    {
        $database_status = $this->getDatabaseStatus($connection = null);
        $redis_status = $this->getRedisSatus($connection = null);
        if($database_status == 'OK') {
            return response()->json(['database_status' => $database_status, 'redis_status' => $redis_status], 200);
        } else {
            return response()->json(['database_status' => $database_status, 'redis_status' => $redis_status], 500);
         }
    }
}
