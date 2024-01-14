<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Vierkand from "@/Components/Vierkandle/Vierkand.vue";
import {computed, onMounted, ref} from "vue";
import Connection from "@/Components/Vierkandle/Connection.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import NavLink from "@/Components/NavLink.vue";

const props = defineProps<{
    today: {},
    vierkandles: [],
}>()
</script>

<template>
    <BasicLayout title="Vierkandle">
        <template #header>
            <div class="flex items-baseline gap-2"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Vierkandle
            </h2>
            <h3 class="text-sm text-gray-500 dark:text-gray-400">
                door <a href="https://github.com/JonaMata" target="_blank" class="underline transition cursor-pointer hover:text-gray-400 dark:hover:text-gray-500">Jonathan Matarazzi</a>
            </h3></div>
        </template>

        <div class="py-4 dark:text-white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl p-5 sm:rounded-lg h-[85svh] max-h-[85svh]">
                    <h1 class="text-2xl font-bold">Alle Vierkandles:</h1>
                    <div class="flex flex-wrap gap-5">
                        <a class="hover:opacity-70" :href="route('index')">
                        <div class="flex flex-col items-center rounded-lg px-2 pb-2 bg-gray-200 dark:bg-gray-600">
                            <div>Vandaag</div>
                            <div class="grid grid-cols-4 grid-rows-4 gap-1">
                                <div v-for="letter in today.letters" class="w-5 h-5 rounded bg-gray-300 dark:bg-gray-700 align-middle text-center">{{ letter }}</div>
                            </div>
                        </div>
                        </a>

                        <a class="hover:opacity-70" v-for="vierkandle in vierkandles" :href="route('show', {vierkandle: vierkandle})">
                            <div class="flex flex-col items-center rounded-lg px-2 pb-2 bg-gray-200 dark:bg-gray-600">
                                <div>{{ vierkandle.date }}</div>
                                <div class="grid grid-cols-4 grid-rows-4 gap-1">
                                    <div v-for="letter in vierkandle.letters" class="w-5 h-5 rounded bg-gray-300 dark:bg-gray-700 align-middle text-center">{{ letter }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
