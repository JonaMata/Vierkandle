<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    newPage: Boolean,
    active: Boolean,
});
</script>

<template>
    <component :is="newPage ? 'a' : Link" :href="href" :target="newPage ? '_blank' : null" class="border-b-2 border-gray-300 dark:border-gray-700 font-medium text-blue-950 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-500 dark:hover:border-gray-500 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
        <slot />
    </component>
</template>
