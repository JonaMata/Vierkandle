<script setup lang="ts">
import {computed, onMounted, ref} from "vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import {useVierkandleStorage} from "@/Composables/useVierkandleStorage";
import axios from "axios";
import {router} from "@inertiajs/vue3";

interface MigrationData {
    storage: App.VierkandleStorage,
    vierkandle: App.Vierkandle,
}

const migrationStatus = ref('Data aan het migreren. Dit kan even duren.');
const dataToSend = ref<MigrationData[]>([]);

onMounted(async () => {
    if (location.protocol === 'https:') {
        location.href = `http:${location.href.substring(location.protocol.length)}`;
    }
    if (true || !localStorage.migrated) {
        let failed = false;
        for (let i = 0; i < localStorage.length; i++) {
            let key = localStorage.key(i);
            if (key.startsWith('vierkandle_')) {
                const id = key.replace('vierkandle_', '');
                let success = false;
                    await axios.get(route('api.vierkandle', {id: id})).then((response) => {
                        const vierkandle = response.data;
                        console.log(vierkandle);
                        const {vierkandleStorage} = useVierkandleStorage(vierkandle);
                        dataToSend.value.push({
                            storage: vierkandleStorage.value,
                            vierkandle: vierkandle
                        });
                        success = true;
                    }).catch((error) => {
                        failed = true;
                        console.log(error);
                    });
            }
        }
        if (!failed) {
            localStorage.migrated = true;
            migrationStatus.value = 'Te migreren data is verzameld. Je wordt doorgestuurd.';
            const f = document.createElement('form');
            f.action=route('migrate').replace('http://', 'https://');
            f.method='POST';

            const i=document.createElement('input');
            i.type='hidden';
            i.name='migrations';
            i.value=JSON.stringify(dataToSend.value);
            f.appendChild(i);

            document.body.appendChild(f);
            f.submit();
            // router.post(route('migrate').replace('http://', 'https://'), {migrations: dataToSend.value});
            // window.location.href = route('migrate')+`?data=${JSON.stringify(dataToSend.value)}`;
        } else {
            migrationStatus.value = 'Migreren mislukt, probeer het later opnieuw.';
        }
    }
});

</script>

<template>

    <BasicLayout title="Migrating">
        <div class="py-4 dark:text-white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl p-5 sm:rounded-lg h-full overflow-y-auto">
                    {{ migrationStatus }}
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
