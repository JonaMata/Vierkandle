<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Vierkand from "@/Components/Vierkandle/Vierkand.vue";
defineProps<{
    vierkandle: { letters: string, solutions: object[] },
}>()
</script>

<template>
    <AppLayout title="Vierkandle">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Vierkandle
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <template v-for="n in 4">
                        <Vierkand v-for="i in 4" :letter="vierkandle.letters.substring((n-1)*4+(i-1),(n-1)*4+i)" :start="n" :includes="n" show-start show-includes />
                        <br>
                    </template>
                    <ul>
                        <li v-for="solution in vierkandle.solutions">{{ solution.word }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
