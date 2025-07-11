<button {{ $attributes->merge(['type' => 'submit', 'class' => 'text-nowrap inline-flex items-center px-4 py-2 bg-green-500 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-400 dark:hover:bg-green-400 focus:bg-green-500 dark:focus:bg-green-500 active:bg-green-500 dark:active:bg-green-500 focus:outline-none focus:ring-2 focus:ring-primary-md focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
