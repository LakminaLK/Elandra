<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    /**
     * System health check
     */
    public function healthCheck(): JsonResponse
    {
        $health = [
            'timestamp' => now(),
            'status' => 'healthy',
            'checks' => []
        ];

        // Database check
        try {
            DB::connection()->getPdo();
            $health['checks']['database'] = [
                'status' => 'healthy',
                'response_time' => $this->measureDatabaseResponseTime()
            ];
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['checks']['database'] = [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }

        // Cache check
        try {
            Cache::put('health_check', 'test', 10);
            $testValue = Cache::get('health_check');
            
            $health['checks']['cache'] = [
                'status' => $testValue === 'test' ? 'healthy' : 'unhealthy'
            ];
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['checks']['cache'] = [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }

        // Storage check
        try {
            $testFile = 'health_check.txt';
            Storage::put($testFile, 'test');
            $content = Storage::get($testFile);
            Storage::delete($testFile);
            
            $health['checks']['storage'] = [
                'status' => $content === 'test' ? 'healthy' : 'unhealthy'
            ];
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['checks']['storage'] = [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }

        // Memory usage
        $health['checks']['memory'] = [
            'status' => 'healthy',
            'usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
            'peak' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB'
        ];

        return response()->json([
            'status' => 'success',
            'data' => $health
        ]);
    }

    /**
     * Get system logs
     */
    public function getLogs(Request $request): JsonResponse
    {
        $lines = $request->get('lines', 100);
        $level = $request->get('level', 'all');
        
        try {
            $logPath = storage_path('logs/laravel.log');
            
            if (!file_exists($logPath)) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'logs' => [],
                        'message' => 'No log file found'
                    ]
                ]);
            }

            $logs = $this->readLogFile($logPath, $lines, $level);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'logs' => $logs,
                    'total_lines' => count($logs)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error reading logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            $cacheTypes = $request->get('types', ['config', 'route', 'view']);
            $cleared = [];

            foreach ($cacheTypes as $type) {
                switch ($type) {
                    case 'config':
                        Artisan::call('config:clear');
                        $cleared[] = 'Configuration cache cleared';
                        break;
                    case 'route':
                        Artisan::call('route:clear');
                        $cleared[] = 'Route cache cleared';
                        break;
                    case 'view':
                        Artisan::call('view:clear');
                        $cleared[] = 'View cache cleared';
                        break;
                    case 'application':
                        Artisan::call('cache:clear');
                        $cleared[] = 'Application cache cleared';
                        break;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Cache cleared successfully',
                'data' => $cleared
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error clearing cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system performance metrics
     */
    public function getPerformanceMetrics(): JsonResponse
    {
        try {
            $metrics = [
                'timestamp' => now(),
                'server' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'server_time' => now()->toDateTimeString(),
                    'timezone' => config('app.timezone')
                ],
                'memory' => [
                    'current_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
                    'peak_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
                    'limit' => ini_get('memory_limit')
                ],
                'database' => [
                    'connection_status' => 'connected',
                    'response_time' => $this->measureDatabaseResponseTime() . 'ms'
                ],
                'cache' => [
                    'driver' => config('cache.default'),
                    'status' => $this->getCacheStatus()
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $metrics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error getting performance metrics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Measure database response time
     */
    private function measureDatabaseResponseTime(): float
    {
        $start = microtime(true);
        try {
            DB::select('SELECT 1');
            return round((microtime(true) - $start) * 1000, 2);
        } catch (\Exception $e) {
            return -1;
        }
    }

    /**
     * Get cache status
     */
    private function getCacheStatus(): string
    {
        try {
            Cache::put('status_check', 'test', 10);
            $value = Cache::get('status_check');
            return $value === 'test' ? 'operational' : 'error';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * Read log file with filtering
     */
    private function readLogFile(string $path, int $lines, string $level): array
    {
        $file = new \SplFileObject($path, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();
        
        $startLine = max(0, $totalLines - $lines);
        $file->seek($startLine);
        
        $logs = [];
        while (!$file->eof() && count($logs) < $lines) {
            $line = trim($file->fgets());
            if (empty($line)) continue;
            
            if ($level === 'all' || strpos($line, strtoupper($level)) !== false) {
                $logs[] = [
                    'line' => $line,
                    'timestamp' => $this->extractTimestamp($line),
                    'level' => $this->extractLevel($line)
                ];
            }
        }
        
        return array_reverse($logs);
    }

    /**
     * Extract timestamp from log line
     */
    private function extractTimestamp(string $line): ?string
    {
        if (preg_match('/\[(.*?)\]/', $line, $matches)) {
            return $matches[1] ?? null;
        }
        return null;
    }

    /**
     * Extract log level from log line
     */
    private function extractLevel(string $line): string
    {
        $levels = ['DEBUG', 'INFO', 'NOTICE', 'WARNING', 'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'];
        
        foreach ($levels as $level) {
            if (strpos($line, $level) !== false) {
                return $level;
            }
        }
        
        return 'UNKNOWN';
    }
}