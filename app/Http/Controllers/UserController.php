<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizationRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'index');
    }

    public function index()
    {
        $user = User::select('id', 'username', 'email', 'role')->get();
        return response()->json($user);
    }

    public function show(Request $request)
    {
        $user_id = $request->id;
        $user = User::select()->where('id', $request->id)->get();
        return response()->json($user)->setStatusCode('200', 'Ok');
    }

    /**
     * @param AuthorizationRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store(Request $request)
    {
        /** @var Validator $validator */
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:1|max:24|regex:[^(?=.{1,24}$)(?![_.-])(?!.*[_.]{2})(?=[a-zA-ZА-Яа-яЁё0-9._-].[А-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яёa-zA-Z0-9\- @%;()\$\.\,\"\'])(?!\S*\s\S*\s).+(?<![_.-])$]',
            'email' => 'required|string|email:rfc,dns|unique:users,email|max:129',
            'password' => ['required', 'string', 'min:8', 'max:24', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])*(?=.*[$&+,:;=?@#|\'<>.-^*()%!]).{8,24}$/'],
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }

        /** @var User $userId */
        try {
            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(422, 'Необрабатываемый экземпляр');
        }
        $user = $this->login($request);
        return response()->json($user)->setStatusCode(201, 'Успешно зарегистрирован');
    }

    /**
     * @param AuthorizationRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function update(AuthorizationRequest $request)
    {
        /** @var Validator $validator */
        $validator = Validator::make($request->all(), [
            'username' => ['required,string,min:1,max:24,regex:[^(?=.{1,24}$)(?![_.-])(?!.*[_.]{2})[a-zA-ZА-Яа-яЁё0-9._-]*[А-ЯЦцУуШшЩщФфЫыРрЭэЧчТтЬьЮюЪъХхЁа-яёa-zA-Z0-9\- @%;()\$\.\,\"\'].+(?<![_.-])$]'],
            'email' => 'nullable|string|email:rfc,dns|unique:users,email|max:129',
            'password' => ['nullable', 'string', 'min:8', 'max:24', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])*(?=.*[$&+,:;=?@#|\'<>.-^*()%!]).{8,24}$/'],
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 200);
        }

        try {
            /** @var User $user */
            $user = User::query()->where('email', $request->email)
                ->update([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(422, 'Необрабатываемый экземпляр');
        }
        return response()->json($user)->setStatusCode(202, 'Успешно отредактировано');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function login(Request $request)
    {
        /** @var Validator $validator */
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email:rfc,dns|max:129',
            'password' => 'required|string|min:8|max:24',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }

        /** @var User $user */
        if ($user = User::query()->where(['email' => $request->email,])->first()
            and
            Hash::check($request->password, $user->password)
        ) {

            $user->api_token = Str::random(40);
            $user->save();

            return response()->json([
                'user_id' => $user->id,
                'username' => $user->username,
                'auth_token' => $user->api_token,
                'email' => $user->email,
                'role' => $user->role,
            ])->setStatusCode(200, 'Успешная авторизация');
        }
        return response()->json(['login' => 'Некорректный логин или пароль'])->setStatusCode(422, 'Необрабатываемый экземпляр');
    }

    public function logout($id)
    {
        Auth::logout($id);
        $user = User::query()->where(['id' => $id]);
        $user->api_token = NULL;
        $user->save();

        return response()->json([
            'message' => 'logged out',
        ])->setStatusCode(200, 'Успешный выход');
    }

    public function destroy()
    {

    }
}
