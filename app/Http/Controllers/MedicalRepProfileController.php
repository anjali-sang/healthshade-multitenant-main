<!-- <?php

// namespace App\Http\Controllers;


// use App\Http\Requests\ProfileUpdateRequest;
// use App\Models\MedicalRep;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Redirect;
// use Illuminate\Validation\Rule;
// use Illuminate\View\View;

// class MedicalRepProfileController extends Controller
// {
//     /**
//      * Display the user's profile form.
//      */
//     public function edit(Request $request): View
//     {
//         $avatars = [
//             'avatar (1).png',
//             'avatar (2).png',
//             'avatar (3).png',
//             'avatar (4).png',
//             'avatar (5).png',
//             'avatar (6).png',
//             'avatar (7).png',
//             'avatar (8).png',
//             'avatar (9).png',
//             'avatar (10).png',
//         ];
//         return view('medical_rep.profile.edit', [
//             'user' => $request->user(),
//             'avatars' => $avatars,
//         ]);
//     }

//     /**
//      * Update the user's profile information.
//      */
//     public function update(Request $request): RedirectResponse
//     {
//         logger($request->all());
//         $validated = $request->validate([
//             'name' => ['required', 'string', 'max:255'],
//             'email' => [
//                 'required',
//                 'string',
//                 'lowercase',
//                 'email',
//                 'max:255',
//                 Rule::unique(MedicalRep::class)->ignore($request->user()->id),
//             ],
//             'avatar' => ['nullable'],
//         ]);

//         $request->user()->fill($validated);

//         if ($request->user()->isDirty('email')) {
//             $request->user()->email_verified_at = null;
//         }

//         $request->user()->save();

//         return Redirect::route('medical_rep.profile.edit')->with('status', 'profile-updated');
//     }


//     /**
//      * Delete the user's account.
//      */
//     public function destroy(Request $request): RedirectResponse
//     {
//         $request->validateWithBag('userDeletion', [
//             'password' => ['required', 'current_password'],
//         ]);

//         $user = $request->user();

//         Auth::logout();

//         $user->delete();

//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

//         return Redirect::to('/');
//     }
// } -->
