<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
/**
 * Display the user's profile edit form.
 *
 * @param \Illuminate\Http\Request $request The incoming HTTP request to edit.
 * @return \Illuminate\View\View The view displaying the profile edit form.
 */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

/**
 * Updates the user's profile information.
 *
 * This method validates the incoming profile update request, applies the changes
 * to the user database table
 *
 * @param \App\Http\Requests\ProfileUpdateRequest $request The request containing updated profile data.
 * @return \Illuminate\Http\RedirectResponse Redirects to the profile edit view.
 */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

/**
 * Delete the user's account.
 *
 * This method validates the user's password using the 'userDeletion' error bag,
 * 
 * @param \Illuminate\Http\Request $request The HTTP request containing the user id/password to confirm the deletion.
 * @return \Illuminate\Http\RedirectResponse Redirects to the homepage after account deletion.
 *
 */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
