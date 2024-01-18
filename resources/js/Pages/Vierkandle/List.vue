<script setup lang="ts">
import BasicLayout from "@/Layouts/BasicLayout.vue";
import VierkandlePreview from "@/Components/Vierkandle/VierkandlePreview.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue"
import {ref} from "vue";

defineProps<{
    vierkandlesBySize: Object[App.Vierkandle[]],
}>()

const migrateData = () => {
    window.location.href = route('migrate.child')
}

let selected = ref(4)
</script>

<template>
    <BasicLayout title="Alle">
        <template #header>
            Alle Vierkandles
        </template>
        <div class="py-4 dark:text-white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col">
                <div class="ml-auto mr-2"><span class="mr-3">Mis je voortgang sinds de update?</span><SecondaryButton @click="migrateData">Migreer data</SecondaryButton></div>
                <div class="mx-auto mt-2 mb-5">
                    <SecondaryButton class="mr-4" :class="{'border-2 border-gray-500 dark:border-gray-300': key == selected}" @click="selected = key" v-for="(vierkandles, key) in vierkandlesBySize">{{key}}×{{key}}</SecondaryButton>
                </div>
                <div v-for="(vierkandles, key) in vierkandlesBySize">
                    <div v-if="key == selected" class="mx-auto bg-white dark:bg-gray-800 shadow-xl p-5 mb-5 sm:rounded-lg">
                        <h1 class="ml-2 text-2xl mb-2 font-bold">Alle {{key}}×{{key}} Vierkandles:</h1>
                        <div class="flex flex-wrap gap-x-3 gap-y-2 max-h-[60svh] overflow-auto">
                            <VierkandlePreview class="min-w-[8rem]" v-for="vierkandle in vierkandles" :vierkandle="vierkandle" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
