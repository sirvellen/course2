<?php

namespace App\Http\Controllers;

use App\Exceptions\Exception;
use App\Http\Requests\TaskRequest;
use App\SubTask;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Task[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */

    public function index()
    {
        return Task::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store(Request $request)
    {
        $token = $request->bearerToken();
        $user = User::query()->select('id')->where('api_token', $token)->first();
        if ($user == NULL) {
            return response()->json([
                'message' => 'Пользователь не авторизован'
            ])->setStatusCode(403, 'Action Unauthorized');
        }
        $validated = Validator::make($request->all(), [
            'assignee_id' => 'required|numeric',
            'task_name' => 'required|string|max:25',
            'task_description' => 'nullable|string|max:250',
            'urgency' => 'nullable|numeric|between:1,3',
            'is_private' => 'nullable|bool',
        ]);

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }

        try {
            $task = Task::create([
                'task_name' => $request->task_name,
                'task_description' => $request->task_description,
                'creator_id' => $user->id,
                'project_id' => $request->project_id,
                'assignee_id' => $request->assignee_id,
                'urgency' => $request->urgency,
                'is_private' => $request->is_private,
                'deadline' => $request->deadline,
                'estimated_time' => $request->estimated_time,
                'done_time' => $request->done_time,
            ]);
            $data = Task::all()->where('id', $task->id)->first();
            $creator = User::query()->select('username')->where('id', $user->id)->first();
            $executer = User::query()->select('username')->where('id', $task->assignee_id)->first();
            $data = [
                'task' => $data,
                'creator' => $creator->username,
                'executer' => $executer->username,
            ];
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($data)->setStatusCode(201, 'Successful Created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function show($task_id, Request $request)
    {
        try {
            $data = Task::query()->where('id', $task_id)->first();
            $creator = User::query()->select('username')->where('id', $data->creator_id)->first();
            $executer = User::query()->select('username')->where('id', $data->assignee_id)->first();
            $data = [
                'task' => $data,
                'creator' => $creator->username,
                'executer' => $executer->username,
            ];
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($data)->setStatusCode(201, 'Successful Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function update($task_id, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'assignee_id' => 'nullable|numeric',
            'task_name' => 'nullable|string|max:25',
            'task_description' => 'nullable|string|max:250',
            'urgency' => 'nullable|numeric|between:1,3',
            'is_private' => 'nullable|bool',
        ]);

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }
        try {
            $task = [
                'update_status' => Task::query()->where('id', $task_id)
                    ->update([
                        'task_name' => $request->task_name,
                        'task_description' => $request->task_description,
                        'project_id' => $request->project_id,
                        'assignee_id' => $request->assignee_id,
                        'urgency' => $request->urgency,
                        'is_private' => $request->is_private,
                        'deadline' => $request->deadline,
                        'estimated_time' => $request->estimated_time,
                        'done_time' => $request->done_time,
                    ]),
                'task' => Task::query()->where('id', $task_id)->first(),
            ];
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($task)->setStatusCode(202, 'Successful Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function destroy(Request $request)
    {
        try {
            $task = Task::query()->where('id', $request->task_id)->delete();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($task)->setStatusCode(202, 'Successful deleted');
    }

    public function done($task_id, Request $request)
    {
        try {
            $task = Task::query()->where('id', $task_id)->update(['task_done' => true]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($task)->setStatusCode(202, 'Successful marked');
    }

    public function undone($task_id, Request $request)
    {
        try {
            $task = Task::query()->where('id', $task_id)->update(['task_done' => false]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($task)->setStatusCode(202, 'Successful marked');
    }

    public function get_user_tasks(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $user = User::query()->select('id')->where('api_token', $token)->first();
            if ($user == NULL) {
                return response()->json([
                    'message' => 'Пользователь не авторизован'
                ])->setStatusCode(403, 'Action Unauthorized');
            }
            $data = Task::query()->select()->where('assignee_id', $user->id)->get();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($data)->setStatusCode(200, 'Ok');
    }

    public function get_user_private_tasks(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $user = User::query()->select('id')->where('api_token', $token)->first();
            if ($user == NULL) {
                return response()->json([
                    'message' => 'Пользователь не авторизован'
                ])->setStatusCode(403, 'Action Unauthorized');
            }
            $data = Task::query()->select()->where('assignee_id', $user->id)->where('is_private', 1)->get();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($data)->setStatusCode(200, 'Ok');
    }
}
