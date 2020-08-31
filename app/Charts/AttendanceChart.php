<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Attendance;
use App\User;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class AttendanceChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        return Chartisan::build()
            ->labels(['Today'])
            ->dataset('In', [$this->countAtt('in')])
            ->dataset('Out', [$this->countAtt('out')])
            ->dataset('Total User', [User::where('is_admin', true)->count()]);
    }

    /**
     * Count attendance by status
     * @param int $status
     * @return int
     * @throws InvalidFormatException
     */
    private function countAtt($status)
    {
        return Attendance::where('status', $status)
            ->whereDate('created_at', Carbon::today())
            ->count();
    }
}
