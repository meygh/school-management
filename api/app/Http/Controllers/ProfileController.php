<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\PatchUserProfileRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function info(User $user)
    {
        $profile = $user->profile;

        if (!$profile) {
            return new UserProfileResource(new UserProfile());
        }

        return new UserProfileResource($profile);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return response()->noContent();
    }

    public function updateAvatar(PatchUserProfileRequest $request)
    {
        $file = $request->file('avatar');
        $file_name = time() . '-' . $file->getClientOriginalName();
        $file->storePubliclyAs('avatars', $file_name);

        $request->user()->profile()->fill($request->validated('avatar'));

        $request->user()->profile()->save();

        return response()->json([
            'filename' => $file_name,
            'message' => __('File uploaded successfully')
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
