<script setup lang="ts">
import {router, usePage} from "@inertiajs/vue3";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import VierkandlePreview from "@/Components/Vierkandle/VierkandlePreview.vue";
import TabView from "@/Components/Tabs/TabView.vue";
import Tab from "@/Components/Tabs/Tab.vue";
import Pagination from "@/Components/Pagination.vue";
import {TabInfo} from "@/Components/Tabs/TabInfo.interface";
import {onMounted} from "vue";

const page = usePage()
const user = page.props.auth.user;

const props = defineProps<{
    type: string,
    types: { title: string, key: string|number }[],
    vierkandles: {data: App.Vierkandle[], links: any},
    solves?: {[Key: string]: App.VierkandleSolve[]}
}>()

let processedVierkandles: App.Vierkandle[] = props.vierkandles.data

const processSolves = () => {
    for(let vierkandle = 0; vierkandle < processedVierkandles.length; vierkandle++) {
        for (let solution = 0; solution < processedVierkandles[vierkandle].solutions!.length; solution++) {
            if (props.solves[processedVierkandles[vierkandle].id.toString()][0].solution_ids.includes(processedVierkandles[vierkandle].solutions![solution].id)) {
                processedVierkandles[vierkandle].solutions![solution].guessed = true
            }
        }
    }
}

onMounted(() => {
    if (user) {
        processSolves()
    }
})
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
<!--&lt;!&ndash;                                <VierkandlePreview v-for="vierkandle in processedVierkandles.data" :vierkandle="vierkandle" />&ndash;&gt;-->
<!--                            </div>-->
<!--                            <Pagination :links="processedVierkandles.links"/>-->
<!--                        </Tab>-->
<!--                        <Tab title="Per Ongeluk">-->
<!--                            <h2 class="text-2xl font-semibold mb-5">Dagelijkse Vierkandle Per ongeluks</h2>-->
<!--                            <div class="flex flex-wrap gap-5 mb-10">-->
<!--&lt;!&ndash;                                <VierkandlePreview v-for="vierkandle in processedVierkandles.data" :vierkandle="vierkandle" />&ndash;&gt;-->
<!--                            </div>-->
<!--                            <Pagination :links="processedVierkandles.links"/>-->
<!--                        </Tab>-->
                        <Tab v-for="type in types" :key="type.key" :title="type.title" :click-argument="type.key">
                            <h3 class="text-xl font-semibold mb-5">{{ type.title }} Vierkandles:</h3>
                            <div class="flex flex-wrap gap-5 mb-5">
                                <VierkandlePreview v-for="vierkandle in processedVierkandles" :vierkandle="vierkandle" />
                            </div>
                            <Pagination :links="vierkandles.links"/>
                        </Tab>
                    </TabView>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
