<x-guest-layout>
    <!-- Font Awesome for Eye Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <form id="registration-form" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <span id="email-error" class="text-red-500 hidden"></span>
        </div>

        <!-- Proceed Button -->
        <div class="flex items-center justify-end mt-4" id="proceed-button-div" @if(old('name') || old('password') || old('password_confirmation')) style="display: none;" @endif>
            <x-primary-button id="proceed-button">
                {{ __('Proceed') }}
            </x-primary-button>
        </div>

        <div id="otp-fields" class="hidden">
            <!-- OTP Input -->
            <div class="mt-4">
                <x-input-label for="otp" :value="__('OTP')" />
                <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" required autocomplete="off"
                    maxlength="6" />
                <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                <span id="otp-error" class="text-red-500 hidden"></span>
            </div>

            <!-- Timer Display -->
            <div class="mt-2 text-sm text-gray-600">
                <span>OTP expires in: </span>
                <span id="timer" class="font-semibold text-red-500">03:00</span>
            </div>

            <!-- Resend OTP Button (Initially Hidden) -->
            <div class="mt-2">
                <button type="button" id="resend-otp"
                    class="text-sm text-blue-600 hover:text-blue-800 underline hidden">
                    Resend OTP
                </button>
            </div>

            <!-- Verify OTP Button -->
            <div class="flex items-center justify-end mt-4" id="verify-otp-div">
                <x-primary-button id="verify-otp" type="button">
                    {{ __('Verify OTP') }}
                </x-primary-button>
            </div>
        </div>

        <!-- Dynamic Fields (Initially Hidden) -->
        <div id="additional-fields"
            class="@if(old('name') || old('password') || old('password_confirmation')) block @else hidden @endif">
            <!-- Name -->
            <div class="mt-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                    autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Password Field -->
            <div class="mt-4 relative">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required
                    autocomplete="new-password" />

                <!-- Eye Icon for Password -->
                <span onclick="togglePasswordVisibility('password', 'toggle-password-icon')"
                    class="cursor-pointer absolute right-2 top-1/2 transform -translate-y-1 text-gray-500"
                    style="margin-top: 4px;">
                    <i id="toggle-password-icon" class="fa fa-eye"></i>
                </span>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password Field -->
            <div class="mt-4 relative">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10" type="password"
                    name="password_confirmation" required autocomplete="new-password" />

                <!-- Eye Icon for Confirm Password -->
                <span onclick="togglePasswordVisibility('password_confirmation', 'toggle-password-confirm-icon')"
                    class="cursor-pointer absolute right-2 top-1/2 transform -translate-y-1 text-gray-500"
                    style="margin-top: 4px;">
                    <i id="toggle-password-confirm-icon" class="fa fa-eye"></i>
                </span>

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

              {{-- <div class="mt-4 relative">
                    <div class="flex items-center space-x-2 mt-1">
                        <input id="medical_rep" name="medical_rep" type="checkbox" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring focus:ring-blue-200"
                            {{ old('medical_rep') ? 'checked' : '' }}>
                    <label for="medical_rep" class="text-sm text-gray-700 dark:text-gray-300">
                        Are you a Medical Representative?
                    </label>
                </div>
    <x-input-error :messages="$errors->get('medical_rep')" class="mt-2" />
</div> --}}



            <!-- Google Captcha -->
            <div class="mt-4">
                {!! htmlFormSnippet() !!}
                @if ($errors->has('g-recaptcha-response'))
                    <div>
                        <small class="text-red-500">
                            {{ $errors->first('g-recaptcha-response') }}
                        </small>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end mt-4">
                <x-primary-button type="submit">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    <script>
        let otpTimer;
        let timeRemaining = 180; // 3 minutes in seconds

        function togglePasswordVisibility(inputId, iconId) {
            let input = document.getElementById(inputId);
            let icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function startOtpTimer() {
            timeRemaining = 180; // Reset to 3 minutes
            const timerElement = document.getElementById('timer');
            const resendButton = document.getElementById('resend-otp');
            const verifyButton = document.getElementById('verify-otp');

            // Hide resend button and enable verify button
            resendButton.classList.add('hidden');
            verifyButton.disabled = false;
            verifyButton.classList.remove('opacity-50', 'cursor-not-allowed');

            otpTimer = setInterval(function () {
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;

                timerElement.textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timeRemaining <= 0) {
                    clearInterval(otpTimer);
                    timerElement.textContent = '00:00';
                    timerElement.classList.add('text-red-600');

                    // Disable verify button and show resend button
                    verifyButton.disabled = true;
                    verifyButton.classList.add('opacity-50', 'cursor-not-allowed');
                    resendButton.classList.remove('hidden');

                    // Show expired message
                    document.getElementById('otp-error').classList.remove('hidden');
                    document.getElementById('otp-error').innerText = 'OTP has expired. Please request a new one.';
                }

                timeRemaining--;
            }, 1000);
        }

        function sendOtp(email) {
            return fetch('{{ route('check-email') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ email }),
            });
        }

        // Proceed button click handler
        document.getElementById('proceed-button').addEventListener('click', function (event) {
            event.preventDefault();
            const email = document.getElementById('email').value;

            if (!email) {
                document.getElementById('email-error').classList.remove('hidden');
                document.getElementById('email-error').innerText = "This field is required";
                return;
            }

            // Disable the button to prevent multiple clicks
            this.disabled = true;
            this.innerText = 'Sending...';

            sendOtp(email)
                .then(response => response.json())
                .then(data => {
                    //console.log(data);
                    if (data.success == true) {
                        document.getElementById('otp-fields').classList.remove('hidden');
                        document.getElementById('email-error').classList.add('hidden');
                        document.getElementById('proceed-button-div').classList.add('hidden');
                        startOtpTimer();
                    } else {
                        document.getElementById('email-error').classList.remove('hidden');
                        document.getElementById('email-error').innerText = data.message;
                        // Re-enable button on error
                        this.disabled = false;
                        this.innerText = 'Proceed';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Something went wrong. Please try again.');
                    // Re-enable button on error
                    this.disabled = false;
                    this.innerText = 'Proceed';
                });
        });

        // Verify OTP button click handler
        document.getElementById('verify-otp').addEventListener('click', function (event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const otp = document.getElementById('otp').value;

            if (!otp) {
                document.getElementById('otp-error').classList.remove('hidden');
                document.getElementById('otp-error').innerText = "Please enter the OTP";
                return;
            }

            // Disable the button to prevent multiple clicks
            this.disabled = true;
            this.innerText = 'Verifying...';

            fetch('{{ route('verify-otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    email: email,
                    otp: otp
                }),
            })
                .then(response => response.json())
                .then(data => {
                    //console.log(data);
                    if (data.success == true) {
                        // Clear timer
                        clearInterval(otpTimer);

                        // Hide OTP fields and show registration fields
                        document.getElementById('otp-fields').classList.add('hidden');
                        document.getElementById('additional-fields').classList.remove('hidden');
                        document.getElementById('otp-error').classList.add('hidden');

                        // Disable email field to prevent changes
                        document.getElementById('email').readonly = true;
                        document.getElementById('email').classList.add('bg-gray-100');
                    } else {
                        document.getElementById('otp-error').classList.remove('hidden');
                        document.getElementById('otp-error').innerText = data.message || 'Invalid OTP. Please try again.';
                        this.disabled = false;
                        this.innerText = 'Verify OTP';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('otp-error').classList.remove('hidden');
                    document.getElementById('otp-error').innerText = 'Something went wrong. Please try again.';
                    // Re-enable button on error
                    this.disabled = false;
                    this.innerText = 'Verify OTP';
                });
        });

        // Resend OTP button click handler
        document.getElementById('resend-otp').addEventListener('click', function (event) {
            event.preventDefault();
            const email = document.getElementById('email').value;

            // Clear any existing error messages
            document.getElementById('otp-error').classList.add('hidden');
            document.getElementById('otp').value = '';

            // Disable the button to prevent multiple clicks
            this.disabled = true;
            this.innerText = 'Sending...';

            sendOtp(email)
                .then(response => response.json())
                .then(data => {
                    if (data.success == true) {
                        startOtpTimer();
                        // Show success message briefly
                        const otpError = document.getElementById('otp-error');
                        otpError.classList.remove('hidden', 'text-red-500');
                        otpError.classList.add('text-green-500');
                        otpError.innerText = 'New OTP sent successfully!';

                        setTimeout(() => {
                            otpError.classList.add('hidden');
                            otpError.classList.remove('text-green-500');
                            otpError.classList.add('text-red-500');
                        }, 3000);
                    } else {
                        document.getElementById('otp-error').classList.remove('hidden');
                        document.getElementById('otp-error').innerText = data.message || 'Failed to send OTP. Please try again.';
                    }
                    // Re-enable button
                    this.disabled = false;
                    this.innerText = 'Resend OTP';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('otp-error').classList.remove('hidden');
                    document.getElementById('otp-error').innerText = 'Something went wrong. Please try again.';
                    // Re-enable button
                    this.disabled = false;
                    this.innerText = 'Resend OTP';
                });
        });
    </script>
</x-guest-layout>