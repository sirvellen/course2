<?php

namespace App\Http\Controllers;

use App\Exceptions\Exception;
use App\Task;
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
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }

        try {
            $task = Task::create([
                'list_id' => $request->list_id,
                'task_name' => $request->task_name,
                'task_description' => $request->task_description,
            ]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($task)->setStatusCode(201, 'Successful Created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function show(Request $request)
    {
        try {
            $data = Task::select()->where('id', $request->task_id)->get();
        } catch (Exception $exception) {
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
    public function update(TaskRequest $request)
    {
        $validated = $request->validated();

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }
        try {
            $task = Task::query()->where('id', $request->task_id)
                ->update([
                    'task_name' => $request->task_name,
                    'task_description' => $request->task_description,
                ]);
        } catch (Exception $exception) {
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
        } catch (Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($task)->setStatusCode(202, 'Successful deleted');
    }

    public function done(Request $request)
    {
        try {
            $task = Task::query()->where('id', $request->task_id)->update(['task_done' => true]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($task)->setStatusCode(202, 'Successful marked');
    }

    public function undone(Request $request)
    {
        try {
            $task = Task::query()->where('id', $request->task_id)->update(['task_done' => false]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($task)->setStatusCode(202, 'Successful marked');
    }
}
