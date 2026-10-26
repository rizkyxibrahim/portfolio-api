<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreScheduleRequest;
use App\Http\Requests\Api\V1\UpdateScheduleRequest;
use App\Http\Resources\Api\V1\ScheduleResource;
use App\Models\Schedule;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $schedules = Schedule::where('user_id', $user->id)
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        return ApiResponse::success(
            data: ScheduleResource::collection($schedules),
            message: 'Schedules retrieved.',
            code: 'SCHEDULES_RETRIEVED',
        );
    }

    public function store(StoreScheduleRequest $request)
    {
        $user = $request->user();
        $schedule = Schedule::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return ApiResponse::success(
            data: new ScheduleResource($schedule),
            message: 'Schedule created.',
            code: 'SCHEDULE_CREATED',
            status: 201,
        );
    }

    public function show(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        return ApiResponse::success(
            data: new ScheduleResource($schedule),
            message: 'Schedule retrieved.',
            code: 'SCHEDULE_RETRIEVED',
        );
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        if ($schedule->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $schedule->update($request->validated());

        return ApiResponse::success(
            data: new ScheduleResource($schedule),
            message: 'Schedule updated.',
            code: 'SCHEDULE_UPDATED',
        );
    }

    public function destroy(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $schedule->delete();

        return ApiResponse::success(
            message: 'Schedule deleted.',
            code: 'SCHEDULE_DELETED',
        );
    }
}