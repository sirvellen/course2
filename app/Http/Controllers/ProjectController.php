<?php

namespace App\Http\Controllers;

use App\Project;
use App\SubTask;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Concerns\InteractsWithInput;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Project[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return Project::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),
            [
                'project_name' => ['required', 'string'],
                'project_description' => ['required', 'string'],
                'project_deadline' => ['nullable', 'string'],
                'project_status' => 'nullable|numeric|min:1|max:3',
            ]);

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }
        $token = $request->bearerToken();
        $user = User::query()->select('id')->where('api_token', $token)->first();
        if ($user == NULL) {
            return response()->json([
                'message' => 'Пользователь не авторизован'
            ])->setStatusCode(403, 'Action Unauthorized');
        }
        try {
            $project = Project::create([
                'project_creator' => $user->id,
                'project_name' => $request->project_name,
                'project_description' => $request->project_description,
                'project_deadline' =>  $request->project_deadline,
                'project_status' =>  $request->project_status,
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($project)->setStatusCode(201, 'Successful Created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Project[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function show($project_id, Request $request)
    {
        try {
            $data = Project::all()->where('id', $project_id)->first();
            $tasks = Task::all()->where('project_id', $project_id)->toArray();
            $project_creator = User::query()->select('username')->where('id', $data->project_creator)->first()->toArray();
            $task_creator = User::query()->select('username')->where('id', $data->project_creator)->first()->toArray();
            $data = array_merge($data, $tasks, $project_creator, $task_creator);
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
    public function update($project_id, Request $request)
    {
        $validated = Validator::make($request->all(),
        [
            'project_name' => 'nullable|string',
            'project_description' => 'nullable|string',
            'project_deadline' => 'nullable|string',
            'project_status' => 'nullable|numeric|min:1|max:3',
        ]);

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }
        try {
            Project::query()->where('id', $project_id)
                ->update([
                    'project_name' => $request->project_name,
                    'project_description' => $request->project_description,
                    'project_deadline' => $request->project_deadline,
                    'project_status' =>  $request->project_status,
                ]);
            $project = Project::query()->where('id', $project_id)->first();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($project)->setStatusCode(202, 'Successful Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function destroy($id)
    {
        try {
            $project = Project::query()->where('id', $id)->delete();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($project)->setStatusCode(200, 'Successful deleted');
    }

    /**
     * Get the bearer token from the request headers.
     *
     * @return string|null
     */
    public function bearerToken()
    {
        $header = $this->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }
}
