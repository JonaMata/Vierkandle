<script setup lang="ts">
import {useVierkandleStorage} from "@/Composables/useVierkandleStorage";
import {computed} from "vue";

const props = defineProps<{
    title?: string,
    routeName?: string,
    vierkandle: App.Vierkandle,
}>()

const size = computed(() => Math.sqrt(props.vierkandle.letters.length));

const title = computed(() => {
    if (props.title) return props.title;
    const vierkandleDate = new Date(props.vierkandle.date);
    const today = new Date();
    if (vierkandleDate.toDateString() === today.toDateString()) return 'Vandaag';
    return vierkandleDate.toLocaleDateString('nl-NL', {day: 'numeric', month: 'long', year: vierkandleDate.getFullYear() !== today.getFullYear() ? 'numeric' : undefined});
});

const { vierkandleStorage } = useVierkandleStorage(props.vierkandle);
const solutionsFound = computed(() => vierkandleStorage.value.words.filter((val: string, i: number, arr: string[]) => arr.indexOf(val) === i).length);

const totalWords = computed(() => {
    return props.vierkandle.solutions?.filter((data) => !data.bonus).length ?? 0;
});
</script>

<template>
    <a class="relative hover:opacity-70" :href="routeName ? route(routeName) : route('show', {vierkandle: vierkandle.id})">
        <div class="flex flex-col items-center rounded-lg px-2 pb-2 bg-gray-200 dark:bg-gray-600"
            :class="solutionsFound >= totalWords ? 'opacity-30' : ''">
            <div>{{ title ?? vierkandle.date }}</div>
            <div class="vierkandle-grid">
                <div v-for="letter in vierkandle.letters" class="w-4 h-4 text-xs rounded bg-gray-300 dark:bg-gray-700 align-middle text-center">{{ letter }}</div>
            </div>
            <div
                class="w-full h-4 rounded overflow-hidden border border-black dark:border-white mt-2 relative">
                <div class="absolute h-full bg-red-500 transition-all"
                     :style="`width: ${solutionsFound/totalWords*100}%`"></div>
            </div>
        </div>
        <div v-if="solutionsFound >= vierkandle.solution_count" class="absolute text-4xl top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">✅</div>
    </a>
</template>

<style scoped>
.vierkandle-grid {
    display: grid;
    grid-template-columns: repeat(v-bind(size), 1fr);
    grid-template-rows: repeat(v-bind(size), 1fr);
    gap: .25rem;
}
</style>
