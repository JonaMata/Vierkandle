<script setup lang="ts">
import {computed, onMounted, ref} from "vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import {useVierkandleStorage} from "@/Composables/useVierkandleStorage";
import {m} from "../../../../public/build/assets/app-__n1wT1D";

const message = ref('Gemigreerde data opslaan. Sluit dit venster niet!');

onMounted(() => {
    if (location.protocol === 'http:') {
        location.replace(`https:${location.href.substring(location.protocol.length)}`);
    }
    const urlData = new URLSearchParams(window.location.search);
    const data = JSON.parse(urlData.get('data'));
    for (const migration of data) {
        console.log(migration);
        const {vierkandleStorage} = useVierkandleStorage(migration.vierkandle)
        for (const word of migration.storage.words) {
            if (!vierkandleStorage.value.words.includes(word)) {
                vierkandleStorage.value.words.push(word);
            }
        }
        for (const word of migration.storage.bonusWords) {
            if (!vierkandleStorage.value.bonusWords.includes(word)) {
                vierkandleStorage.value.bonusWords.push(word);
            }
        }
    }
    localStorage.migrated = true;
    message.value = 'Gemigreerde data opgeslagen. Je kan dit venster sluiten.';
});

</script>

<template>
    <BasicLayout title="Migrating">
        <div class="py-4 dark:text-white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl p-5 sm:rounded-lg h-[85svh] max-h-[85svh]">
                    {{ message }}
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
