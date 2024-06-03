<?php
namespace App\Services;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthService
{
    use ApiResponse;

    public function __construct() {}


    /**
     * @param array $data
     * @return JsonResponse
     */
    public function register(array $data): JsonResponse
    {
        try {
            $user = User::create($data);
            $userRole = Role::where('name', 'user')->firstOrFail();
            $user->assignRole($userRole);

            return $this->response([], ['Регистрация прошла успешно']);
        } catch (Exception $exception) {
            return $this->error([$exception->getMessage()], 400);
        }
    }


    /**
     * @param array $data
     * @return JsonResponse
     */
    public function login(array $data): JsonResponse
    {
        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'],$user->password)){
            return $this->error(['Неверные учетные данные'], 401);
        }

        return $this->response([
            'token' => $user->createToken($user->email.'-AuthToken')->plainTextToken,
            'user' => $user,
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->ok();
    }

}
