<script setup lang="ts">
import {router} from "@inertiajs/vue3";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import VierkandlePreview from "@/Components/Vierkandle/VierkandlePreview.vue";
import TabView from "@/Components/Tabs/TabView.vue";
import Tab from "@/Components/Tabs/Tab.vue";
import Pagination from "@/Components/Pagination.vue";
import {TabInfo} from "@/Components/Tabs/TabInfo.interface";

const props = defineProps<{
    type: string,
    types: { title: string, key: string|number }[],
    vierkandles: {data: App.Vierkandle[], links: any},
}>()
</script>

<template>
    <BasicLayout title="Alle">
        <template #header>
            Alle Vierkandles
        </template>
        <div class="py-4 dark:text-white absolute w-full h-full overflow-hidden">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
                <div class="bg-white dark:bg-gray-800 h-full overflow-y-auto shadow-lg p-5 sm:rounded-lg">
                    <TabView :initial-tab="type" :click-action="(arg: any) => router.get(route('list', {'type': arg}))">
<!--                        <Tab title="Dagelijks">-->
<!--                            {{types}}-->
<!--                            <h2 class="text-2xl font-semibold mb-5">Dagelijkse Vierkandles</h2>-->
<!--                            <div class="flex flex-wrap gap-5 mb-10">-->
<!--&lt;!&ndash;                                <VierkandlePreview v-for="vierkandle in vierkandles.data" :vierkandle="vierkandle" />&ndash;&gt;-->
<!--                            </div>-->
<!--                            <Pagination :links="vierkandles.links"/>-->
<!--                        </Tab>-->
<!--                        <Tab title="Per Ongeluk">-->
<!--                            <h2 class="text-2xl font-semibold mb-5">Dagelijkse Vierkandle Per ongeluks</h2>-->
<!--                            <div class="flex flex-wrap gap-5 mb-10">-->
<!--&lt;!&ndash;                                <VierkandlePreview v-for="vierkandle in vierkandles.data" :vierkandle="vierkandle" />&ndash;&gt;-->
<!--                            </div>-->
<!--                            <Pagination :links="vierkandles.links"/>-->
<!--                        </Tab>-->
                        <Tab v-for="type in types" :key="type.key" :title="type.title" :click-argument="type.key">
                            <h3 class="text-xl font-semibold mb-5">{{ type.title }} Vierkandles:</h3>
                            <div class="flex flex-wrap gap-5 mb-5">
                                <VierkandlePreview v-for="vierkandle in vierkandles.data" :vierkandle="vierkandle" />
                            </div>
                            <Pagination :links="vierkandles.links"/>
                        </Tab>
                    </TabView>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
