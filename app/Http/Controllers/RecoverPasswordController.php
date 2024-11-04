<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckResetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetPasswordRequest;
use App\Mail\RecoverPasswordNotifycation;
use App\Models\PasswordResetToken;
use App\Models\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class RecoverPasswordController extends Controller
{
    public function sendResetPassword(SendResetPasswordRequest $request)
    {
        // Radom token
        $tokenRandom = Str::random(60);

        $data = [
            'email' => $request->email,
            'token' => $tokenRandom
        ];

        try {
            //Create Password Reset Token in DB
            PasswordResetToken::create([
                'email' => $data['email'],
                'token' =>  $data['token']
            ]);
            // Email sent successfully, return a success response
            Mail::to($data['email'])->send(new RecoverPasswordNotifycation($data));
            return response()->json([
                'message' => 'We have sent a reset password link to your email address',
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            // Error sending email, return an error response
            return response()->json([
                'message' => 'Reset password link already send. Please check email.',
                'success' => false,
            ], 412);
        }
    }

    public function checkResetPassword(CheckResetPasswordRequest $request)
    {
        // Get email and password from user input
        $email = $request->email;
        $token = $request->token;

        // Find email and token is valid in password_reset_token 
        $exists = PasswordResetToken::where('email', $email)
            ->where('token', $token)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Check reset password token has in the system',
                'success' => true
            ], 200);
        } else {
            // User record does not exist with the given email and token
            return response()->json([
                'message' => 'Email or token is invalid.',
                'success' => false
            ], 200);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        // Get email and password from user input
        $email = $request->email;
        $token = $request->token;
        $newPassword = $request->new_password;

        // Find email and token is valid in password_reset_token 
        $exists = PasswordResetToken::where('email', $email)
            ->where('token', $token)
            ->exists();

        if ($exists) {
            // User record exists with the given email and token

            // Update password by email in users DB
            $user = User::where('email', '=', $email)->first();
            $user->password = bcrypt($newPassword);
            $user->save();

            // Delete token in password_reset_tokens in DB
            PasswordResetToken::where('email', '=', $email)->delete();
            return response()->json([
                'message' => 'Password changed successfully',
                'success' => true
            ], 200);
        } else {
            // User record does not exist with the given email and token
            return response()->json([
                'message' => 'Email or token is invalid.',
                'success' => false
            ], 200);
        }
    }
}
