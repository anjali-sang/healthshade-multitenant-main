 <!-- Enhanced Theme Selector Dropdown Component -->
    <div class="bg-white dark:bg-gray-800 rounded-xl">
        <div class="relative inline-block text-left">
            <button
                id="theme-dropdown-button"
                class="inline-flex items-center justify-between w-full sm:w-48 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                onclick="toggleDropdown()"
            >
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <span id="selected-theme-name">Choose Theme</span>
                </div>
                <svg class="w-4 h-4 ml-2 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div
                id="theme-dropdown-menu"
                class="absolute left-0 sm:left-auto right-0 z-10 mt-2 w-full sm:w-56 origin-top-right 
           bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 
           rounded-lg shadow-lg max-h-57 overflow-y-auto opacity-0 invisible 
           transform scale-95 transition-all duration-200"
            >
                <div class="py-2 max-h-52 overflow-y-auto">
                    @foreach([
                        'red' => '#ef4444',
                        'yellow' => '#f59e0b', 
                        'green' => '#10b981',
                        'blue' => '#3b82f6',
                        'indigo' => '#6366f1',
                        'violet' => '#8b5cf6',
                        'purple' => '#9333ea',
                        'teal' => '#14b8a6',
                        'cyan' => '#06b6d4',
                        'pink' => '#ec4899',
                    ] as $color => $hex)
                        <button
                            class="flex items-center w-full px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150 group"
                wire:click="updateTheme('{{ $color }}')"
                onclick="selectTheme('{{ $color }}', '{{ $hex }}', '{{ ucfirst($color) }} Theme')"
            >
                            <div class="w-5 h-5 rounded-full mr-3 border border-gray-200 dark:border-gray-500 group-hover:scale-110 transition-transform duration-200" style="background-color: {{ $hex }}"></div>
                            <span>{{ ucfirst($color) }} Theme</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const menu = document.getElementById('theme-dropdown-menu');
            const arrow = document.getElementById('dropdown-arrow');
            
            if (menu.classList.contains('opacity-0')) {
                menu.classList.remove('opacity-0', 'invisible', 'scale-95');
                menu.classList.add('opacity-100', 'visible', 'scale-100');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                menu.classList.add('opacity-0', 'invisible', 'scale-95');
                menu.classList.remove('opacity-100', 'visible', 'scale-100');
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        function selectTheme(color, hex, name) {
            document.getElementById('selected-theme-color').style.backgroundColor = hex;
            document.getElementById('selected-theme-name').textContent = name;
            toggleDropdown();
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('theme-dropdown-button').parentElement;
            if (!dropdown.contains(event.target)) {
                const menu = document.getElementById('theme-dropdown-menu');
                const arrow = document.getElementById('dropdown-arrow');
                menu.classList.add('opacity-0', 'invisible', 'scale-95');
                menu.classList.remove('opacity-100', 'visible', 'scale-100');
                arrow.style.transform = 'rotate(0deg)';
            }
        });
    </script>