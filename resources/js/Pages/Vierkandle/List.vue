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
