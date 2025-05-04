<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

{{--This is the same button as the one in the authentication card, but with a different class for the background color. We just removed the dark:bg-gray-200 class and added the bg-gray-800 class. This is to make the button look like the one in the authentication card, which has a dark background color. The rest of the classes are the same as the ones in the authentication card button.--}}
