<script setup lang="ts">
import BasicLayout from "@/Layouts/BasicLayout.vue";
import VierkandlePreview from "@/Components/Vierkandle/VierkandlePreview.vue";

const props = defineProps<{
    daily: App.Vierkandle[],
    express: App.Vierkandle[],
    vierkandles: Record<number, App.Vierkandle[]>,
}>()
</script>

<template>
    <BasicLayout title="Alle">
        <template #header>
            Alle Vierkandles
        </template>
        <div class="py-4 dark:text-white absolute w-full h-full overflow-hidden">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-white dark:bg-gray-800 h-full overflow-y-auto shadow-xl p-5 sm:rounded-lg">
                    <h2 class="text-2xl font-semibold mb-5">Dagelijkse Vierkandles</h2>
                    <div class="flex flex-wrap gap-5 mb-10">
                        <VierkandlePreview v-for="vierkandle in daily" :vierkandle="vierkandle" />
                    </div>
                    <h2 class="text-2xl font-semibold mb-5">Dagelijkse Vierkandle Per ongeluks</h2>
                    <div class="flex flex-wrap gap-5 mb-10">
                        <VierkandlePreview v-for="vierkandle in express" :vierkandle="vierkandle" />
                    </div>
                    <h2 class="text-2xl font-semibold mb-5">Speciale vierkandles:</h2>
                    <template v-for="(sizeVierkandles, size) in vierkandles">
                        <h3 class="text-xl font-semibold mb-5">Vierkandles van {{ size }}:</h3>
                        <div class="flex flex-wrap gap-5 mb-5">
                            <VierkandlePreview v-for="vierkandle in sizeVierkandles" :vierkandle="vierkandle" />
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
