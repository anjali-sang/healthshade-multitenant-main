<x-guest-layout>
    <!-- Session Status -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Display Warning Message if Available -->
    @if (session('warning'))
        <p class="text-center" style="color: #d9534f;">
            {{ session('warning') }}
        </p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required
                autocomplete="current-password" />

            <!-- Eye Icon Inside Password Field -->
            <span onclick="togglePasswordVisibility()"
                class="cursor-pointer absolute right-2 top-1/2 transform -translate-y-1 text-gray-500"
                style="margin-top: 4px;">
                <i id="toggle-password-icon" class="fa fa-eye"></i>
            </span>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4 px-1 lg:px-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="border-t-2 my-3"></div>

        <!-- ✅ Responsive tweak here -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                {{ __('Start your Free Trail Today') }}
            </p>
            <a class='underline uppercase' href="/register">{{ __('Register now') }}</a>
        </div>
    </form>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-password-icon');

            // Toggle the input type
            const fieldType = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = fieldType;

            // Toggle the icon class
            if (fieldType === 'text') {
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

</x-guest-layout>