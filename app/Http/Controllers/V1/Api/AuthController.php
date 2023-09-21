<?php

namespace App\Http\Controllers\V1\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\V1\Entity\AuthService;
use App\Http\Requests\V1\Api\Auth\LoginRequest;
use App\Http\Requests\V1\Api\Auth\LogoutRequest;
use App\Http\Controllers\V1\Api\BaseApiController;
use App\Http\Requests\V1\Api\Auth\RegisterRequest;


class AuthController extends BaseApiController
{
    public function __construct(protected AuthService $authService){}

    public function register(RegisterRequest $request){return $this->handleSharedMessage($this->authService->register($request));}

    public function login(LoginRequest $request){return $this->handleSharedMessage($this->authService->login($request));}

    public function logout(LogoutRequest $request){return $this->handleSharedMessage($this->authService->logout($request));}
}
