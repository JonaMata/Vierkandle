<script setup lang="ts">
import {useVierkandleStorage} from "@/Composables/useVierkandleStorage";
import {computed} from "vue";

const props = defineProps<{
    routeName?: string,
    vierkandle: App.Vierkandle
}>()

const { vierkandleStorage } = useVierkandleStorage(props.vierkandle);
const solutionsFound = computed(() => vierkandleStorage.value.words.length);
const today = (new Date(props.vierkandle.date)).toDateString() == (new Date()).toDateString();
</script>

<template>
    <a class="relative hover:opacity-70" :href="routeName ? route(routeName) : route('show', {vierkandle: vierkandle.id})">
        <div class="flex flex-col items-center rounded-lg px-4 pt-2 pb-3 bg-gray-200 dark:bg-gray-600 border-2 border-solid border-gray-400 dark:border-gray-500"
            :class="{'opacity-30': solutionsFound == vierkandle.solution_count, 'dark:border-red-500 border-red-500 border-dashed': today}">
            <div class="mb-1">{{ today ? 'Vandaag' : vierkandle.date }}</div>
            <div class="grid vierkandle-grid gap-1">
                <div v-for="letter in vierkandle.letters" class="w-4 h-4 text-xs rounded bg-gray-300 dark:bg-gray-700 align-middle text-center">{{ letter }}</div>
            </div>
            <div
                class="w-full h-4 rounded overflow-hidden border border-gray-500 mt-2 relative">
                <div class="absolute h-full bg-red-500 transition-all"
                     :style="`width: ${solutionsFound/vierkandle.solution_count*100}%`"></div>
            </div>
        </div>
        <div v-if="solutionsFound == vierkandle.solution_count" class="absolute text-4xl top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">✅</div>
    </a>
</template>

<style scoped>
.vierkandle-grid {
    grid-template-rows: repeat(v-bind('props.vierkandle.boardsize'), minmax(0, 1fr));
    grid-template-columns: repeat(v-bind('props.vierkandle.boardsize'), minmax(0, 1fr));
}
</style>
