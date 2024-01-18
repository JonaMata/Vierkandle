<script setup lang="ts">
import {computed, onMounted, onUnmounted, ref, watch} from 'vue';
import {Head, Link, router} from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps<{
    title: string
}>();

const theme = ref();

const prefersDark = ref();
const switchTheme = (e: Event) => {
    localStorage.theme = e.target?.value;
}

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
    theme.value = localStorage.theme ?? 'auto';
    prefersDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', changePreferedTheme);
})

onUnmounted(() => {
    window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', changePreferedTheme);
})
</script>

<template>
    <div>
        <Head :title="title"/>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="max-w-7xl py-6">
                        <div class="flex flex-col gap-2"><h2
                            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Vierkandle
                        </h2>
                            <h3 class="text-sm text-gray-500 dark:text-gray-400">
                                door <a href="https://github.com/JonaMata" target="_blank"
                                        class="underline transition cursor-pointer hover:text-gray-400 dark:hover:text-gray-500">Jonathan
                                Matarazzi</a>
                            </h3></div>
                    </div>
                    <div class="hidden md:flex gap-2">
                        <NavLink :active="$page.url == '/'" :href="route('index')">Vandaag</NavLink>
                        <NavLink :active="route('list').endsWith($page.url)" :href="route('list')">Alle Vierkandles
                        </NavLink>
                    </div>
                    <div class="text-gray-800 dark:text-gray-200">
                        Thema: <select v-model="theme" @input="switchTheme" style="background-color: transparent;"
                                       class="appearance-none w-30 border-0 border-b-2 border-gray-200 focus:border-gray-200 dark:border-gray-600 dark:focus:border-gray-600 py-3 px-4 pr-8 leading-tight focus:outline-none focus:ring-0">
                        <option value="auto">Auto</option>
                        <option value="dark">Dark</option>
                        <option value="light">Light</option>
                    </select>
                    </div>
                </div>
                <div class="md:hidden flex justify-evenly items-center">
                    <NavLink :active="$page.url == '/'" :href="route('index')">Vandaag</NavLink>
                    <NavLink :active="route('list').endsWith($page.url)" :href="route('list')">Alle Vierkandles
                    </NavLink>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot/>
            </main>
        </div>
    </div>
</template>
