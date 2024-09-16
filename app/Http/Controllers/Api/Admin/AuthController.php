<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Admin;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LogoutResource;
use Illuminate\Support\Facades\Validator; // For request validation
use Illuminate\Support\Facades\Auth; // For authentication functions like guard and user
use Tymon\JWTAuth\Facades\JWTAuth; // For JWTAuth functions like setToken and invalidate
use Tymon\JWTAuth\Exceptions\TokenInvalidException; // For catching invalid token exceptions


class AuthController extends Controller
{

    use GeneralTrait;

    public function login(Request $request)
    {

        try {
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('admin-api')->attempt($credentials);

            if (!$token)
                return $this->returnError('E001', 'بيانات الدخول غير صحيحة');

            $admin = Auth::guard('admin-api')->user();
            $admin->api_token = $token;
            //return token
            return $this->returnData('admin', $admin);
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    // public function logout(Request $request)
    // {
    //      $token = $request -> header('auth-token');
    //     if($token){
    //         try {

    //             JWTAuth::setToken($token)->invalidate(); //logout
    //         }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
    //             return  $this -> returnError('','some thing went wrongs');
    //         }
    //         return $this->returnSuccessMessage('Logged out successfully');
    //     }else{
    //         $this -> returnError('','some thing went wrongs');
    //     }

    // }


    public function logout(Request $request)
    {
        try {
            // Check if the token is valid
            $token = $request->header('auth-token');

            if (!$token) {
                return new LogoutResource((object)[
                    'success' => false,
                    'message' => 'Authorization token not found',
                ]);
            }

            // Invalidate the token
            JWTAuth::setToken($token)->invalidate();

            return new LogoutResource((object)[
                'success' => true,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $ex) {
            return new LogoutResource((object)[
                'success' => false,
                'message' => 'Invalid token',
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $ex) {
            return new LogoutResource((object)[
                'success' => false,
                'message' => 'Token has expired',
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $ex) {
            return new LogoutResource((object)[
                'success' => false,
                'message' => 'Token could not be parsed',
            ]);
        } catch (\Exception $ex) {
            return new LogoutResource((object)[
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }
}
