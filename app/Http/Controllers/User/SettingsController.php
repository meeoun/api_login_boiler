<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePassword;
use App\Http\Requests\User\UpdateProfile;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function updateProfile(UpdateProfile $request)
    {
        $user = auth()->user();
        $user->x =$request->location['longitude'];
        $user->y =$request->location['latitude'];
        $user->fill($request->except(['location']));
        $user->save();
        return new UserResource($user);

    }

    public function updatePassword(UpdatePassword $request)
    {
        $request->user()->update(['password'=>bcrypt($request->password)]);
        return response()->json(['message' =>'password updated']);

    }
}
