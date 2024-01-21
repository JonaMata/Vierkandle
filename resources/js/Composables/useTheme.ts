import {router, usePage} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import axios from "axios";

export function useTheme() {
    const page = usePage()
    const user = page.props.auth.user;
    const theme = ref('auto');
    const storageName = 'theme';
    const useLocalStorage = !!localStorage;
    if(user) {
        theme.value = user.theme;
    } else {
        if (useLocalStorage && localStorage.getItem(storageName)) {
            theme.value = localStorage.getItem(storageName);
        }
    }
    watch(theme, (value) => {
        if (useLocalStorage) {
            localStorage.setItem(storageName, value);
        }
        if (user) {
            router.post(route('user.theme'), {theme: value});
        }
    }, {
        deep: true,
        immediate: true,
    });
    return theme;
}
