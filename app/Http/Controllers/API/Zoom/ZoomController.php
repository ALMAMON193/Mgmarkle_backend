<?php

namespace App\Http\Controllers\API\Zoom;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
    use ApiResponse;

    public function generateSdkToken(Request $request)
    {

        $sdkKey = config('services.zoom.sdk_key');
        $sdkSecret = config('services.zoom.sdk_secret');

        $sessionName = $request->session_name;
        $roleType = $request->role;

        $iat = time();
        $exp = $iat + 3600;

        $payload = [
            'app_key' => $sdkKey,
            'tpc' => $sessionName,
            'role_type' => (int) $roleType,
            'version' => 1,
            'iat' => $iat,
            'exp' => $exp,
        ];

        $token = JWT::encode($payload, $sdkSecret, 'HS256');

        return $this->sendResponse([
            'token' => $token,
        ], 'SDK Token generated successfully');
    }

    public function getSdkCredentials()
    {
        return $this->sendResponse([
            'sdk_key' => config('services.zoom.sdk_key'),
            'sdk_secret' => config('services.zoom.sdk_secret'),
        ], 'SDK Credentials retrieved successfully');
    }
}
