<?php


namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function login(Request $request)
{
    \Log::info('Login attempt', $request->all());

    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = \App\Models\User::whereRaw('LOWER(email) = ?', [strtolower($request->email)])->first();

    if (! $user) {
        \Log::warning('User not found', ['email' => $request->email]);
        return response()->json(['message' => 'User not found'], 404);
    }

    if (! \Hash::check($request->password, $user->password)) {
        \Log::warning('Invalid password for user', ['email' => $request->email]);
        return response()->json(['message' => 'Incorrect password'], 401);
    }

    $token = $user->createToken('admin-token')->plainTextToken;

    \Log::info('Login success', ['email' => $request->email]);

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
}


    public function logout(Request $request)
    {
        // Invalidate token
        $user = User::where('api_token', $request->bearerToken())->first();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['message' => 'Logged out']);
    }
    public function updateCredentials(Request $request)
{
    $user = $request->user();

    $request->validate([
        'current_password' => 'required',
        'new_password' => [
            'required',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
        ]
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['message' => 'Current password is incorrect.'], 401);
    }

    
    $user->password = bcrypt($request->new_password);
    $user->save();

    $user->tokens()->delete(); // invalidate all tokens

    return response()->json(['message' => 'Credentials updated. Please login again.']);
}
public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
        ]);

        $user = $request->user();
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Email updated successfully']);
    }

    public function createAdmin(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:users,email',
        'password' => [
            'required',
            'min:8',
            'regex:/[a-z]/',      // at least one letter
            'regex:/[0-9]/',      // at least one number
            'regex:/[@$!%*#?&]/', // at least one special character
        ],
    ]);

    $user = new User();
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json(['message' => 'Admin created successfully']);
}
public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    try {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)]);
        } else {
            \Log::error('Password reset link not sent', ['status' => $status]);
            return response()->json(['message' => 'Reset link failed to send.'], 500);
        }
    } catch (\Exception $e) {
        \Log::error('Exception during password reset link sending', [
            'error' => $e->getMessage()
        ]);

        return response()->json(['message' => 'Something went wrong. Please try again.'], 500);
    }
}

public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/'
        ],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Password reset successful.'])
        : response()->json(['message' => __($status)], 500);
}

}
