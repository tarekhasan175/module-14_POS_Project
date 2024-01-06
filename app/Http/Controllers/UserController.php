<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //View Pages functions
    public function userRegistrationPage()
    {
        return view('pages.auth.user_registration');
    }

    public function userLoginPage()
    {
        return view('pages.auth.user_login');
    }

    public function sendOTPPage()
    {
        return view('pages.auth.send_otp');
    }

    public function verifyOTPPage()
    {
        return view('pages.auth.verify_otp');
    }

    public function setPasswordPage()
    {
        return view('pages.auth.set_password');
    }


    // Api Features functions
    public function userRegistration(Request $request)
    {


        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User registration successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User registration failed'
                // 'message' => $e->getMessage()
            ], 200);
        }
    }

    public function userLogin(Request $request)
    {
        $count = User::where('email', $request->input('email'))
            ->where('password', $request->input('password'))
            ->select('id')->first();

        if ($count !== null) {
            $token = JWTToken::createToken($request->input('email'), $count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User login successfully'
            ], 200)->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User login failed'
            ], 200);
        }
    }

    public function sendOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(100000, 999999);
        $count = User::where('email', '=', $email)->count();

        if ($count == 1) {
            // OTP sending logic
            Mail::to($email)->send(new OTPMail($otp));

            // OTP inserting to database
            User::where('email', '=', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not found'
            ], 200);
        }

    }

    public function verifyOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)->where('otp', '=', $otp)->count();

        if ($count == 1) {
            // OTP update on database
            User::where('email', '=', $email)->update(['otp' => '0']);

            // Password reset token issued
            $token = JWTToken::createTokenForSetPassword($email);
            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully'
            ], 200)->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Invalid OTP'
            ], 200);
        }
    }

    public function setPassword(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            //Reset Password Logic
            User::where('email', '=', $email)->update(['password' => $password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password reset successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong'
            ], 200);
        }
    }

    // User Logout function
    public function logout()
    {
        return redirect('/user-login')->cookie('token', '', -1);
    }

}
