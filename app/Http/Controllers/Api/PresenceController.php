<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Presence;
use App\Traits\ImageStorage;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PresenceController extends Controller
{
    use ImageStorage;

    /**
     * Store presence status
     * @param Request $request
     * @return JsonResponse|void
     * @throws InvalidFormatException
     * @throws BindingResolutionException
     */
    public function store(Request $request)
    {
        $request->validate([
            'long' => ['required'],
            'lat' => ['required'],
            'address' => ['required'],
            'type' => ['in:in,out', 'required'],
            'photo' => ['required']
        ]);

        $photo = $request->file('photo');
        $presenceType = $request->type;
        $userPresenceToday = $request->user()
            ->presences()
            ->whereDate('created_at', Carbon::today())
            ->first();

        // is presence type equal with 'in' ?
        if ($presenceType == 'in') {
            // is $userPresenceToday not found?
            if (! $userPresenceToday) {
                $presence = $request
                    ->user()
                    ->presences()
                    ->create(
                        [
                            'status' => false
                        ]
                    );

                $presence->detail()->create(
                    [
                        'type' => 'in',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'presence'),
                        'address' => $request->address
                    ]
                );

                return response()->json(
                    [
                        'message' => 'Success'
                    ],
                    Response::HTTP_CREATED
                );
            }

            // else show user has been checked in
            return response()->json(
                [
                    'message' => 'User has been checked in',
                ],
                Response::HTTP_OK
            );
        }

        if ($presenceType == 'out') {
            if ($userPresenceToday) {

                if ($userPresenceToday->status) {
                    return response()->json(
                        [
                            'message' => 'User has been checked out',
                        ],
                        Response::HTTP_OK
                    );
                }

                $userPresenceToday->update(
                    [
                        'status' => true
                    ]
                );

                $userPresenceToday->detail()->create(
                    [
                        'type' => 'out',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'presence'),
                        'address' => $request->address
                    ]
                );

                return response()->json(
                    [
                        'message' => 'Success'
                    ],
                    Response::HTTP_CREATED
                );
            }

            return response()->json(
                [
                    'message' => 'Please do check in first',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
