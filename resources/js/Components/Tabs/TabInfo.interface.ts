import {Ref} from "vue";

export interface TabInfo {
    title: string,
    clickArgument?: any,
    isActive: Ref<boolean>,
}
