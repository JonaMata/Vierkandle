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
import {useTheme} from "@/Composables/useTheme";
import VersionModal from "@/Components/Vierkandle/VersionModal.vue";

defineProps<{
    title: string
}>();

const theme = useTheme();
const prefersDark = ref();
const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
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

        <Banner/>
        <VersionModal/>

        <div class="min-h-[100dvh] max-h-[100dvh] bg-gray-100 dark:bg-gray-900 flex flex-col">
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('index')">
                                    <ApplicationMark/>
                                </Link>
                            </div>

                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('index')" :active="route().current('index')">
                                Vandaag
                            </NavLink>
                            <NavLink :href="route('list')" :active="route().current('list')">
                                Alle Vierkandles
                            </NavLink>
                        </div>


                        <div class="hidden sm:flex sm:items-center sm:ms-6">

                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                Thema: <select v-model="theme" @input="switchTheme"
                                               style="background-color: transparent;"
                                               class="appearance-none w-30 border-0 border-b-2 text-sm border-gray-200 focus:border-gray-200 dark:border-gray-600 dark:focus:border-gray-600 mx-2 py-1 px-1 pr-8 leading-tight focus:outline-none focus:ring-0">
                                <option value="auto">Auto</option>
                                <option value="dark">Dark</option>
                                <option value="light">Light</option>
                            </select>
                            </div>
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                                {{ $page.props.auth?.user?.name ?? 'Account' }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                     fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <template v-if="$page.props.auth.user">
                                            <!-- Account Management -->
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                Account Beheren
                                            </div>

                                            <DropdownLink :href="route('profile.show')">
                                                Profiel
                                            </DropdownLink>

                                            <div class="border-t border-gray-200 dark:border-gray-600"/>

                                            <!-- Authentication -->
                                            <form @submit.prevent="logout">
                                                <DropdownLink as="button">
                                                    Uitloggen
                                                </DropdownLink>
                                            </form>
                                        </template>
                                        <template v-else>
                                            <DropdownLink :href="route('login')">
                                                Inloggen
                                            </DropdownLink>
                                            <DropdownLink :href="route('register')">
                                                Registreren
                                            </DropdownLink>
                                        </template>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                                @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'scale-y-100': showingNavigationDropdown, 'scale-y-0': ! showingNavigationDropdown}"
                     class="sm:hidden z-[999] transition-transform origin-top absolute w-full bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('index')" :active="route().current('index')">
                            Vandaag
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('list')" :active="route().current('list')">
                            Alle Vierkandles
                        </ResponsiveNavLink>
                    </div>

                    <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex w-full justify-between items-center px-4 font-medium text-gray-600 dark:text-gray-400">
                            Thema: <select v-model="theme" @input="switchTheme"
                                           style="background-color: transparent;"
                                           class="appearance-none w-30 border-0 border-b-2 border-gray-200 focus:border-gray-200 dark:border-gray-600 dark:focus:border-gray-600 mx-2 py-1 px-1 pr-8 leading-tight focus:outline-none focus:ring-0">
                            <option value="auto">Auto</option>
                            <option value="dark">Dark</option>
                            <option value="light">Light</option>
                        </select>
                        </div>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div v-if="$page.props.auth.user" class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-4">
                            <div>
                                <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                Profiel
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Uitloggen
                                </ResponsiveNavLink>
                            </form>

                        </div>
                    </div>
                    <div v-else class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">

                        <div class="space-y-1">
                            <ResponsiveNavLink :href="route('login')" :active="route().current('login')">
                                Inloggen
                            </ResponsiveNavLink>

                            <ResponsiveNavLink :href="route('register')" :active="route().current('register')">
                                Registreren
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 font-medium text-xl text-gray-900 dark:text-gray-100">
                    <slot name="header"/>
                </div>
            </header>

            <!-- Page Content -->
            <main class="relative flex-grow flex-shrink overflow-hidden">
                <slot/>
            </main>

            <footer class="bg-transparent dark:text-white text-xs md:text-sm lg:text-base mb-2 mx-4 md:mx-12 flex flex-row justify-between gap-4">
                <div>
                    Vind je Vierkandle leuk? Je kan hier <a class="rounded-md bg-red-500 text-white px-2 py-0.5" href="https://donate.stripe.com/3cs9C646979b9Us3cc">Doneren</a>
                </div>

                <div>
                    Is er iets stuk of wil je gewoon lekker babbelen? Mail naar <a class="text-gray-500 dark:text-gray-400 hover:text-gray-400 dark:hover:text-gray-500 underline" href="mailto:jonathan@vierkandle.nl">jonathan@vierkandle.nl</a>
                </div>
            </footer>
        </div>
    </div>
</template>
