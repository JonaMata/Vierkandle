<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import {useTheme} from "@/Composables/useTheme.js";
import {onMounted, onUnmounted, ref, watch} from "vue";

defineProps({
    policy: String,
});

const theme = useTheme();
const prefersDark = ref();
const changePreferedTheme = (e: MediaQueryListEvent) => {
    prefersDark.value = e.matches;
}

const updateTheme = () => {
    if (theme.value == 'dark' || (theme.value == 'auto' && prefersDark.value)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

watch([theme, prefersDark], updateTheme);

onMounted(() => {
    prefersDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', changePreferedTheme);
})

onUnmounted(() => {
    window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', changePreferedTheme);
})
</script>

<template>
    <Head title="Privacy Policy" />

    <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <div class="pt-4 bg-gray-100 dark:bg-gray-900">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <AuthenticationCardLogo />
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg prose dark:prose-invert" v-html="policy" />
            </div>
        </div>
    </div>
</template>
