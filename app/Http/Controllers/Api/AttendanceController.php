<?php

namespace App\Http\Controllers\Api;

use App\Attendance;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function today()
    {
        $attendaces = Attendance::whereDate('created_at', Carbon::today())
            ->with(['user'])->get();

        return response()->json([
            'message' => 'list of attendaces today',
            'data' => $attendaces,
        ], Response::HTTP_OK);
    }

    public function in(Request $request)
    {
        $attendace = $request->user()->attendances()->create([
            'status' => 'in',
            'long' => $request->long,
            'lat' => $request->lat
        ]);

        return response()->json([
            'message' => 'check-in success',
            'data' => $attendace,
        ], Response::HTTP_CREATED);
    }

    public function out(Request $request)
    {
        $attendace = $request->user()->attendances()->create([
            'status' => 'out',
            'long' => $request->long,
            'lat' => $request->lat
        ]);

        return response()->json([
            'message' => 'check-out success',
            'data' => $attendace,
        ], Response::HTTP_CREATED);
    }
}
