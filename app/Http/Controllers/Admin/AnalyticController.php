<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{
    use Helpers;

    public function mysql()
    {
        $sql = 'show global status where variable_name = \'Com_select\'';
        $old = DB::select($sql);
        sleep(1);
        $new = DB::select($sql);

        return $this->response->array([
            'x' => now()->toTimeString(),
            'y' => $new[0]->Value - $old[0]->Value
        ]);
    }

    public function server()
    {
        $cpu_usage = (float)shell_exec('ps -A -o %cpu | awk \'{s+=$1} END {print s}\'');

        switch (PHP_OS) {
            case 'Linux':
                if (false === ($str = file("/proc/cpuinfo"))) return $this->response->errorInternal();
                $str = implode("", $str);
                preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
                $thread_count = count($model[1]);

                if (false === ($str = file_get_contents("/proc/meminfo"))) return false;

                preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $mems);
                preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);

                $mtotal = $mems[1][0] * 1024;
                $mfree = $mems[2][0] * 1024;
                $mbuffers = $buffers[1][0] * 1024;
                $mcached = $mems[3][0] * 1024;
                $mrealused = $mtotal - $mfree - $mcached - $mbuffers;
                $mem_usage = round($mrealused/$mtotal * 100, 2);
                break;
            case 'Darwin':
                $thread_count = (int)shell_exec('/usr/sbin/sysctl -n machdep.cpu.thread_count');
                $mem = shell_exec("/usr/bin/memory_pressure -Q");
                preg_match('/System-wide memory free percentage: (?P<free>\d+)/', $mem, $mem_pressure);
                $mem_usage = 100 - (int)$mem_pressure['free'];
                break;
            default:
                return $this->response->errorInternal();
                break;
        }

        $disk_total = disk_total_space('.');
        $disk_free = disk_free_space('.');

        return $this->response->array([
            'cpu' => [
                'x' => now()->toTimeString(),
                'y' => round($cpu_usage / $thread_count, 2)
            ],
            'memory' => [
                'pressure' => (int)$mem_usage,
            ],
            'disk' => [
                'total' => $disk_total,
                'free' => $disk_free,
                'percent' => round((($disk_total - $disk_free) / $disk_total) * 100, 2)
            ]
        ]);
    }
}
