<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\MongoProduct;
use App\Models\User;

class HealthController extends Controller
{
    public function healthCheck()
    {
        $checks = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'app' => $this->checkApplication(),
            'databases' => $this->checkDatabases(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'environment' => $this->checkEnvironment()
        ];

        $overallStatus = $this->determineOverallStatus($checks);
        $checks['status'] = $overallStatus;

        return response()->json($checks, $overallStatus === 'healthy' ? 200 : 503);
    }

    public function simpleHealth()
    {
        // Simple health check for load balancers
        try {
            // Quick database connectivity check
            DB::connection('mysql')->getPdo();
            return response('healthy', 200)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response('unhealthy', 503)->header('Content-Type', 'text/plain');
        }
    }

    private function checkApplication()
    {
        return [
            'name' => config('app.name'),
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'version' => app()->version(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version()
        ];
    }

    private function checkDatabases()
    {
        $databases = [];

        // Check MySQL
        try {
            $start = microtime(true);
            DB::connection('mysql')->getPdo();
            $responseTime = round((microtime(true) - $start) * 1000, 2);
            
            $userCount = User::count();
            
            $databases['mysql'] = [
                'status' => 'connected',
                'response_time_ms' => $responseTime,
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
                'user_count' => $userCount
            ];
        } catch (\Exception $e) {
            $databases['mysql'] = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }

        // Check MongoDB
        try {
            $start = microtime(true);
            $productCount = MongoProduct::count();
            $responseTime = round((microtime(true) - $start) * 1000, 2);
            
            $databases['mongodb'] = [
                'status' => 'connected',
                'response_time_ms' => $responseTime,
                'host' => config('database.connections.mongodb.host'),
                'database' => config('database.connections.mongodb.database'),
                'product_count' => $productCount
            ];
        } catch (\Exception $e) {
            $databases['mongodb'] = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }

        return $databases;
    }

    private function checkCache()
    {
        try {
            $testKey = 'health_check_' . time();
            $testValue = 'test_value';
            
            $start = microtime(true);
            Cache::put($testKey, $testValue, 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);
            $responseTime = round((microtime(true) - $start) * 1000, 2);
            
            return [
                'status' => $retrieved === $testValue ? 'working' : 'error',
                'driver' => config('cache.default'),
                'response_time_ms' => $responseTime
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkStorage()
    {
        try {
            $storagePath = storage_path();
            $publicPath = public_path();
            
            return [
                'status' => 'accessible',
                'storage_writable' => is_writable($storagePath),
                'public_writable' => is_writable($publicPath),
                'storage_path' => $storagePath,
                'disk_space_free' => $this->formatBytes(disk_free_space($storagePath))
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    private function checkEnvironment()
    {
        return [
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'php_memory_limit' => ini_get('memory_limit'),
            'php_max_execution_time' => ini_get('max_execution_time'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'extensions' => [
                'mongodb' => extension_loaded('mongodb'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'gd' => extension_loaded('gd'),
                'curl' => extension_loaded('curl'),
                'zip' => extension_loaded('zip')
            ]
        ];
    }

    private function determineOverallStatus($checks)
    {
        // Check if any critical components are down
        if (isset($checks['databases']['mysql']['status']) && $checks['databases']['mysql']['status'] === 'error') {
            return 'unhealthy';
        }
        
        if (isset($checks['databases']['mongodb']['status']) && $checks['databases']['mongodb']['status'] === 'error') {
            return 'unhealthy';
        }
        
        if (isset($checks['cache']['status']) && $checks['cache']['status'] === 'error') {
            return 'degraded';
        }
        
        if (isset($checks['storage']['status']) && $checks['storage']['status'] === 'error') {
            return 'degraded';
        }
        
        return 'healthy';
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}