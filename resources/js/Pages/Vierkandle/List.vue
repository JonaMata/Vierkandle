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

defineProps<{
    today?: App.Vierkandle,
    vierkandles: App.Vierkandle[],
}>()
</script>

<template>
    <BasicLayout title="Alle">
        <template #header>
            Alle Vierkandles
        </template>
        <div class="py-4 dark:text-white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl p-5 sm:rounded-lg h-[85svh] max-h-[85svh]">
                    <div class="flex flex-wrap gap-5">
                        <VierkandlePreview v-if="today" :vierkandle="today" title="Vandaag" route-name="index" />

                        <VierkandlePreview v-for="vierkandle in vierkandles" :vierkandle="vierkandle" />
                    </div>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
