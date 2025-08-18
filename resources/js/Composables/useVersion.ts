import {router, usePage} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import axios from "axios";

export function useVersion() {
    const version = ref('0.0.0');
    const storageName = 'version';
    const useLocalStorage = !!localStorage;
    if (useLocalStorage && localStorage.getItem(storageName)) {
        version.value = localStorage.getItem(storageName)!;
    }
    watch(version, (value) => {
        if (useLocalStorage) {
            localStorage.setItem(storageName, value);
        }
    }, {
        immediate: true,
    });
    return version;
}
