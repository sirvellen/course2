<?php

namespace App\Http\Controllers;

use App\Exceptions\Exception;
use App\Http\Requests\TaskListRequest;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TaskList[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return TaskList::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store($desk_id, TaskListRequest $request)
    {

        $validated = Validator::make($request->all(), $request->rules());

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }

        try {
            $tasklist = TaskList::create([
                'desk_id' => $desk_id,
                'list_name' => $request->list_name,
            ]);
            dd($tasklist);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($tasklist)->setStatusCode(201, 'Successful Created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function show($desk_id, $list_id, Request $request)
    {
        // код для будущего расширения поиска
//      todo:  $data = TaskList::select('id', 'desk_id', 'list_name')->where('id', $request->list_id)->get();
        try {
            $data = TaskList::select()->where('id', $list_id)->get();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($data)->setStatusCode(200, 'Successful Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskList $tasklist
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function update($desk_id, $list_id, TaskListRequest $request)
    {
        $validated = Validator::make($request->all(), $request->rules());

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }
        try {
            $tasklist = TaskList::query()->where('id', $list_id)
                ->update(['list_name' => $request->list_name]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($tasklist)->setStatusCode(202, 'Successful Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function destroy($desk_id, $list_id, Request $request)
    {
        try {
            $taskList = TaskList::query()->where('id', $list_id)->delete();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($taskList)->setStatusCode(200, 'Successful deleted');
    }
}
