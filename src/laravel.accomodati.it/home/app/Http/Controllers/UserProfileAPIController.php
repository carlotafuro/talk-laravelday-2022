<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserProfileAPIController extends Controller
{
    public function ProfileProvisioning(Request $request) {

        $input_payload = json_decode($request->getContent(), true);

        $user = User::where('email', $input_payload['username'])->first();

        //
        // la coppia username/password fornita è corretta
        //
        if (is_object($user) and \Hash::check($input_payload['password'], $user->password)) {

            return response()->json(['result' => true, 'error' => '', 'user_data' => $user]);
        //
        // la coppia username/password fornita non è corretta
        //
        } else {
            return response()->json(['result' => false, 'error' => 'user auth error', 'user_data' => (object) []]);
        }
    }
}