<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Vierkand from "@/Components/Vierkandle/Vierkand.vue";
import {computed, onMounted, ref} from "vue";
import Connection from "@/Components/Vierkandle/Connection.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputLabel from "@/Components/InputLabel.vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import NavLink from "@/Components/NavLink.vue";
import VierkandlePreview from "@/Components/Vierkandle/VierkandlePreview.vue";

const props = defineProps<{
    today: {},
    vierkandles: [],
}>()
</script>

<template>
    <BasicLayout title="Alle">
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
                        <VierkandlePreview :vierkandle="today" title="Vandaag" route-name="index" />

                        <VierkandlePreview v-for="vierkandle in vierkandles" :vierkandle="vierkandle" />
                    </div>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
