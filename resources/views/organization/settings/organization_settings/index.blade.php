<x-app-layout>
    <div class="py-2 px-4">
        <div class="max-w-screen-2xl mx-auto sm:px-3 lg:px-8 space-y-6">
            <div class="w-full rounded-lg mb-3">
                @include('organization.org_details')
            </div>
            <!-- Buttons -->
            <div class="sm:rounded-lg flex justify-center items-center mb-3">
                <div class="rounded-2xl bg-primary-lt dark:bg-primary-lt">
                    <button type="button" id="toggle-locations-btn"
                        class="inline-flex items-center px-2 py-2 bg-primary-dk dark:bg-primary-dk dark:border-gray-500 font-semibold text-xs text-white dark:text-white uppercase disabled:opacity-25 w-72 text-center justify-center rounded-2xl"
                        onclick="toggleView('locations')">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 3.35 3.8 8.2 6.14 11.13.57.72 1.75.72 2.32 0C15.2 17.2 19 12.35 19 9c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z" />
                            </svg>
                        </span>
                        {{ __('Locations') }}
                    </button>

                    <button type="button" id="toggle-users-btn"
                        class="inline-flex items-end px-2 py-2 bg-primary-lt dark:bg-primary-lt dark:border-gray-500 font-semibold text-xs text-primary-dk dark:text-white uppercase disabled:opacity-25 w-72 text-center justify-center rounded-2xl"
                        onclick="toggleView('users')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="size-5 mr-1">
                            <path
                                d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                        </svg>
                        {{ __('Users') }}
                    </button>
                </div>
            </div>


            <!-- Locations Details -->
            <div id="locations-section">
                <livewire:organization.location-component />
            </div>

            <!-- Users Details -->
            <div id="users-section" class="hidden">
                <livewire:organization.users-component />
            </div>

        </div>
    </div>

    <script>
        function toggleView(section) {
            const sections = ['locations', 'users', 'org'];
            const activeClasses = ['bg-primary-dk', 'text-white', 'dark:bg-primary-dk', 'dark:text-white'];
            const inactiveClasses = ['bg-primary-lt', 'text-primary-dk', 'dark:bg-primary-lt', 'dark:text-primary-dk'];

            sections.forEach((sec) => {
                const sectionElement = document.getElementById(`${sec}-section`);
                const buttonElement = document.getElementById(`toggle-${sec}-btn`);

                if (sec === section) {
                    sectionElement.classList.remove('hidden');
                    buttonElement.classList.add(...activeClasses);
                    buttonElement.classList.remove(...inactiveClasses);
                } else {
                    sectionElement.classList.add('hidden');
                    buttonElement.classList.remove(...activeClasses);
                    buttonElement.classList.add(...inactiveClasses);
                }
            });
        }
    </script>
</x-app-layout>