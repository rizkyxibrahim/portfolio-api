<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $schedules = Schedule::where('user_id', $user->id)
            ->orderBy('start_time', 'asc')
            ->paginate(15);
        return ApiResponse::success(
            data: $schedules,
            message: 'Schedules retrieved.',
            code: 'SCHEDULES_RETRIEVED',
        );
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'boolean',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $schedule = Schedule::create(array_merge($validator->validated(), [
            'user_id' => $user->id,
        ]));
        return ApiResponse::success(
            data: $schedule,
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
            data: $schedule,
            message: 'Schedule retrieved.',
            code: 'SCHEDULE_RETRIEVED',
        );
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'nullable|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $schedule->update($validator->validated());
        return ApiResponse::success(
            data: $schedule,
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
