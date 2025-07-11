<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\PotentialClient;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Imports\UsersImport;
use Excel;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        logger($request->all());
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', 'recaptcha'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => 'avatar (8).png',
            'role_id' => '2',
            // 'is_medical_rep' => $request->medical_rep ?? false,
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('organization.catalog', absolute: false));
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $freeEmailProviders = [
            'gmail.com',
            'yahoo.com',
            'hotmail.com',
            'outlook.com',
            'aol.com',
            'icloud.com',
            'mail.com',
            'protonmail.com',
            'zoho.com',
            'yandex.com',
            'gmx.com',
            'msn.com'
        ];

        $domain = substr(strrchr($request->email, "@"), 1);

        if (in_array(strtolower($domain), $freeEmailProviders)) {
            return response()->json(['success' => false, 'message' => 'Please enter your work email.']);
        }
        $email = $request->email;
        if (User::where('is_deleted', false)->where('email', $email)->exists()) {
            return response()->json(['success' => false, 'message' => 'Email already exists.']);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        \Log::info("Generated OTP for {$email}: {$otp}");
        // Set OTP expiry time (3 minutes from now)
        $otpExpiry = now()->addMinutes(3);
        $potentialClient = PotentialClient::updateOrCreate(
            ['email' => $email],
            [
                'otp' => $otp,
                'otp_expires_at' => $otpExpiry,
                'otp_verified' => false
            ]
        );

        try {
            Mail::to($email)->send(new OtpMail($otp));
            return response()->json([
                'success' => true,
                'message' => 'OaTP sent successfully to your email.'
            ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('OTP Email sending failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $potentialClient = PotentialClient::where('email', $request->email)->first();

        if (!$potentialClient) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found. Please start the registration process again.'
            ]);
        }

        // Check if OTP is expired
        if (now()->greaterThan($potentialClient->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ]);
        }

        // Check if OTP matches
        if ($potentialClient->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please check and try again.'
            ]);
        }

        // Mark OTP as verified
        $potentialClient->update([
            'otp_verified' => true,
            'otp' => null, // Clear OTP for security
            'otp_expires_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully. You can now complete your registration.'
        ]);
    }

    public function usersindex()
    {
        $user = auth()->user();
        // Redirect admins to the organization.catalog
        if ($user->role_id != 1) {
            return redirect()->route('organization.catalog');
        }
        $users = User::with(['organization', 'location'])
            ->where('organization_id', $user->organization_id)
            ->where('is_deleted', false)
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);


        $import = new UsersImport(auth()->user());
        Excel::import($import, $request->file('csv_file'));
        if (!empty($import->getSkippedUsers())) {
            return $import->downloadSkippedCsv();
        }
        return back()->with('success', 'Users Imported Successfully!');
    }
}
