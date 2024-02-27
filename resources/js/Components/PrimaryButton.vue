<template>
    <button
        class="inline-flex items-center
        px-4 py-2
        border border-transparent rounded-md tracking-widest
        font-semibold text-xs uppercase
        bg-gray-200 text-black dark:bg-gray-800 dark:text-white
        hover:bg-gray-600 hover:text-white dark:hover:bg-white dark:hover:text-gray-800
        active:bg-gray-800 dark:active:bg-gray-600 dark:active:text-white
        focus:bg-gray-400 dark:focus:bg-white dark:focus:text-gray-800 focus:ring-indigo-500 focus:ring-2 focus:outline-none focus:ring-offset-2 dark:focus:ring-offset-gray-800
        transition ease-in-out duration-150"
    >
        <slot/>
    </button>
</template>
