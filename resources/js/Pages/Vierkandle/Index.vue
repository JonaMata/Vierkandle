<script setup lang="ts">
import Vierkand from "@/Components/Vierkandle/Vierkand.vue";
import {computed, nextTick, onMounted, ref, reactive, watch} from "vue";
import Connection from "@/Components/Vierkandle/Connection.vue";
import Checkbox from "@/Components/Checkbox.vue";
import BasicLayout from "@/Layouts/BasicLayout.vue";
import {usePage} from "@inertiajs/vue3";
import {useVierkandleStorage} from "@/Composables/useVierkandleStorage";
import axios from "axios";

const props = defineProps<{
    vierkandle: App.Vierkandle,
}>()

const page = usePage();
const user = computed(() => page.props.auth.user);

const solutions = ref<{
    [Key: number]: { [Key: string]: VierkandleSolution }
}>({});

const {vierkandleStorage, addWord} = useVierkandleStorage(props.vierkandle);

onMounted(() => {
    document.addEventListener('keydown', () => {
        inputField.value?.focus();
    })

    for (const solution of props.vierkandle.solutions) {
        let length = solution.word.length;
        if (solution.bonus) {
            length = 777;
        }
        if (!(length in solutions.value)) {
            solutions.value[length] = {};
        }
        solutions.value[length][solution.word] = solution;
    }
    for (const num: number of Object.keys(solutions.value)) {
        solutions.value[num] = Object.keys(solutions.value[num]).sort().reduce(
            (obj: { [Key: string]: VierkandleSolution }, key: string) => {
                obj[key] = solutions.value[num][key];
                return obj;
            },
            {}
        );
    }
});


const letters = computed(() => {
    const letters: { letter: string, start: number, includes: number }[] = [];
    for (let i = 0; i < 16; i++) {
        const letter = props.vierkandle.letters[i];
        letters.push({letter, start: 0, includes: 0});
    }
    for (const subSolutions of Object.values(solutions.value)) {
        for (const solution of Object.values(subSolutions)) {
            if (solution.guessed || solution.bonus) {
                continue;
            }
            const chain: number[] = [...solution.chain];
            for (const index of chain) {
                if (index === chain[0]) {
                    letters[index].start++;
                }
                letters[index].includes++;
            }
        }
    }
    return letters;
});

const amountGuessed = computed(() => {
    return Object.values(solutions.value).map((data) => Object.values(data)).flat().filter((data) => data.guessed && !data.bonus).length;
});

const totalWords = computed(() => {
    return props.vierkandle.solutions.filter((data) => !data.bonus).length;
});

const inputField = ref<HTMLInputElement | null>(null);
const input = ref('');
const chain = ref<number[]>([]);
const resultMessage = ref('');
const showResult = ref(false);
const rotation = ref(0);
const renderLines = ref(true);
const lastSolution = ref<{ word: string, url: string }>(null);

const letterElements = ref([]);

const reRenderLines = async () => {
    renderLines.value = false;
    await nextTick();
    setTimeout(() => renderLines.value = true, 510);
}

watch(rotation, reRenderLines);

const guessWord = () => {
    const word = input.value;
    let length = word.length;
    input.value = '';
    chain.value = [];
    let message = '';
    if (word.length >= 4) {
        if (777 in solutions.value && word in solutions.value[777]) {
            length = 777;
            message += 'Bonuswoord: ';
        }
        if (length in solutions.value && word in solutions.value[length]) {
            const solution = solutions.value[length][word];
            if (!solution.guessed) {
                solution.guessed = true;
                if (!message) {
                    message += 'Goed geraden: ';
                }
                message += word;
                lastSolution.value = {word, url: solutions.value[length][word].url};
                addWord(solution);
            } else {
                message = 'Al geraden!';
            }
        } else {
            message = 'Onbekend woord!';
            vierkandleStorage.value.mistakes++;
        }
    } else {
        message = 'Te kort woord!';
    }
    resultMessage.value = message;
    showResult.value = true;
    setTimeout(() => showResult.value = false, 2000);
}

const handleInput = () => {
    lastSolution.value = null;
    showResult.value = false;
    resultMessage.value = '';
    input.value = input.value.replace(/[^a-z]/gi, '');
    input.value = input.value.toUpperCase();
    if (input.value.length == 0) {
        chain.value = [];
        return;
    }
    const newChain = findChain(input.value);
    input.value = newChain.map((index) => props.vierkandle.letters[index]).join('');
    chain.value = newChain;
}

const findChain = (word: string): number[] => {
    const letter = word[0];
    const starts = [...props.vierkandle.letters.matchAll(new RegExp(letter, 'g'))];
    let longestChain: number[] = [];
    for (const start of starts) {
        if (word.length === 1) {
            return [start.index];
        }
        const chain = recFindChain([start.index], word.substring(1));
        if (chain.length > longestChain.length) {
            longestChain = chain;
        }
    }
    return longestChain;
}

const recFindChain = (chain: number[], word: string): number[] => {
    let longestChain: number[] = chain;
    for (const neighbour of findNeighbours(chain[chain.length - 1])) {
        if (props.vierkandle.letters[neighbour] === word[0] && !chain.includes(neighbour)) {
            const newChain = [...chain, neighbour];
            if (word.length === 1) {
                return newChain;
            }
            const result = recFindChain(newChain, word.substring(1));
            if (result.length > longestChain.length) {
                longestChain = result;
            }
        }
    }
    return longestChain;
}

const findNeighbours = (index: number): number[] => {
    const neighbours = [];
    for (let i = -5; i <= 5; i++) {
        if (i === 0) {
            continue;
        }
        const neighbour = index + i;
        if (neighbour >= 0 && neighbour < 16) {
            if (Math.abs(index % 4 - neighbour % 4) <= 1) {
                neighbours.push(neighbour);
            }
        }
    }
    return neighbours;
}

const dragStart = (e: MouseEvent | TouchEvent) => {
    e.preventDefault();
    lastSolution.value = null;
    resultMessage.value = '';
    const index = letterElements.value.findIndex((element: Component) => element.$el == e.target || element.$el.contains(e.target));
    if (index >= 0) {
        chain.value = [index];
    }
    if (e instanceof MouseEvent) {
        window.addEventListener('mousemove', dragMove);
        window.addEventListener('mouseup', dragEnd, {once: true});
    } else {
        window.addEventListener('touchmove', dragMove, {passive: false});
        window.addEventListener('touchend', dragEnd, {passive: false, once: true});
    }
    chainToInput()
}

const dragMove = (e: MouseEvent | TouchEvent) => {
    e.preventDefault();
    let index = -1;
    let target = e.target;
    if (e instanceof TouchEvent) {
        target = document.elementFromPoint(e.touches[0].clientX, e.touches[0].clientY);
    }
    index = letterElements.value.findIndex((element: Component) => element.$el.querySelector('.hitbox') == target || element.$el.querySelector('.hitbox').contains(target));

    if (index >= 0) {
        if (chain.value.length > 0) {
            const neighbours = findNeighbours(chain.value[chain.value.length - 1]);
            if (neighbours.includes(index)) {
                if (!chain.value.includes(index)) {
                    chain.value.push(index);
                } else if (chain.value[chain.value.length - 2] == index) {
                    chain.value.pop();
                }
            }
        } else {
            chain.value = [index];
        }
    }
    chainToInput()
}

const dragEnd = (e: MouseEvent | TouchEvent) => {
    window.removeEventListener('mousemove', dragMove);
    window.removeEventListener('touchmove', dragMove);
    guessWord();
}

const chainToInput = () => {
    input.value = chain.value.map((index) => props.vierkandle.letters[index]).join('');
}
</script>

<template>
    <BasicLayout :title="$page.url == '/' ? 'Vandaag' : vierkandle.date">
        <div class="py-4 dark:text-white absolute w-full h-full overflow-hidden">
            <input class="opacity-0 fixed top-full" v-model="input" ref="inputField" @input="handleInput"
                   @keydown.enter="guessWord"/>
            <div class="h-full max-w-7xl px-auto sm:px-6 lg:px-8">
                <div
                    class="h-full p-5 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                    <div
                        class="h-full grid grid-cols-1 md:grid-cols-3 items-start content-start overflow-y-auto md:overflow-hidden">
                        <div class="mx-auto md:mx-0 md:h-full md:overflow-y-auto">
                            <div class="mb-2" v-if="amountGuessed/totalWords >= .6">
                                <h1 class="text-2xl font-bold mb-2">Hints:</h1>
                                <label class="flex items-center">
                                    <Checkbox v-model:checked="vierkandleStorage.hints.showMissing" name="hint-missing"/>
                                    <span
                                        class="ms-2 text-sm text-gray-600 dark:text-gray-400">Laat missende woorden zien.</span>
                                </label>
                                <label class="flex items-center">
                                    <Checkbox v-model:checked="vierkandleStorage.hints.showLetters" name="hint-letters"/>
                                    <span
                                        class="ms-2 text-sm text-gray-600 dark:text-gray-400">Toon sommige letters.</span>
                                </label>
                            </div>
                            <div class="mb-4" v-for="(subSolutions, num) in solutions">
                                <h1 class="text-2xl font-bold mb-2">
                                    <template v-if="num == 777">
                                        Bonuswoorden:
                                    </template>
                                    <template v-else>
                                        {{ num }} letter woorden:
                                    </template>
                                </h1>
                                <div class="flex flex-wrap gap-2">
                                    <template v-for="(data, word) in subSolutions">
                                        <div v-if="data.guessed">
                                            <a v-if="data.url" :href="data.url" target="_blank"
                                               class="border-b border-black dark:border-white border-dashed">
                                                {{ word }}
                                            </a>
                                            <template v-else>
                                                {{ word }}
                                            </template>
                                        </div>
                                        <div v-else-if="vierkandleStorage.hints.showLetters" class="text-gray-400">{{
                                                word.substring(0, Math.ceil(word.length / 6)) + '*'.repeat((word.length - Math.floor(word.length / 6)) - (Math.ceil(word.length / 6))) + word.substring(word.length - Math.floor(word.length / 6), word.length)
                                            }}
                                        </div>
                                        <div v-else-if="vierkandleStorage.hints.showMissing" class="text-gray-400">
                                            {{ '*'.repeat(word.length) }}
                                        </div>
                                    </template>
                                </div>
                                <span
                                    v-if="!vierkandleStorage.hints.showLetters && !vierkandleStorage.hints.hintMissing && Object.values(subSolutions).filter((data) => !data.guessed).length > 0"
                                    class="text-gray-600 dark:text-gray-400 italic">
                                    +{{ Object.values(subSolutions).filter((data) => !data.guessed).length }} woorden te gaan
                                </span>
                            </div>
                        </div>
                        <div class="order-first mb-10 md:mb-0 md:order-none md:col-span-2 flex flex-col items-center">
                            <div class="w-fit">
                                <h1 class="text-4xl text-center font-bold mb-2">{{ amountGuessed }}/{{ totalWords }}
                                    woorden
                                    <div @click="() => rotation += 1" class="cursor-pointer inline-block -scale-x-100">
                                        🔄
                                    </div>
                                </h1>
                                <div
                                    class="w-full h-7 rounded overflow-hidden border border-black dark:border-white mb-2 relative">
                                    <div class="absolute h-full bg-red-500 transition-all"
                                         :style="`width: ${amountGuessed/totalWords*100}%`"></div>
                                    <div class="absolute transition-all"
                                         :class="amountGuessed/totalWords >= .6 ? 'opacity-0' : ''"
                                         :style="`left: ${amountGuessed/totalWords < .2 ? '20' : amountGuessed/totalWords < .4 ? '40' : '60'}%`">
                                        ⭐️
                                    </div>
                                </div>
                                <div class="flex relative mb-2">
                                    <div
                                        class="flex-grow flex justify-center items-center w-0 h-10 text-4xl font-bold text-center">
                                        <span class="text-2xl transition"
                                              :class="showResult ? '' : 'opacity-0'">
                                    {{ resultMessage }}
                                    </span>
                                        <span :class="input.length < 4 ? 'text-gray-500' : ''">
                                        {{ input }}
                                    </span>
                                    </div>
                                    <a v-if="lastSolution" :href="lastSolution.url" target="_blank"
                                       :class="!showResult && lastSolution ? 'opacity-100 hover:opacity-60 transition-opacity' : ''"
                                       class="opacity-0 absolute text-xl px-2 bg-gray-300 dark:bg-gray-900 border-black dark:border-white border rounded-md left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                                        {{ lastSolution.word }}
                                    </a>
                                </div>
                                <div
                                    class="mx-auto grid grid-cols-4 grid-rows-4 gap-2 w-fit transition-transform duration-500 select-none"
                                    :style="`transform: rotate(${rotation*90}deg)`">
                                    <Vierkand v-for="(letter, i) in letters"
                                              class="transition-transform duration-500"
                                              @mousedown="dragStart"
                                              @touchstart="dragStart"
                                              :style="`transform: rotate(-${rotation*90}deg)`"
                                              ref="letterElements"
                                              :active="chain.includes(i)"
                                              :letter="letter.letter"
                                              :start="letter.start"
                                              :includes="letter.includes"
                                              :show-start="amountGuessed/totalWords >= .2 && letter.start > 0"
                                              :show-includes="amountGuessed/totalWords >= .4 && letter.includes > 0"
                                              :is-start="chain.length > 0 && chain[0] == i"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="opacity-50 pointer-events-none">
                            <div v-if="chain.length > 0 && renderLines" class="fixed rounded-full w-14 h-14"
                                 style="background: red; transform: translate(-50%, -50%);"
                                 :style="`left: ${letterElements[chain[0]].$el.getBoundingClientRect().x+letterElements[chain[0]].$el.getBoundingClientRect().width/2}px; top: ${letterElements[chain[0]].$el.getBoundingClientRect().y+letterElements[chain[0]].$el.getBoundingClientRect().height/2}px`">
                            </div>
                            <Connection v-if="chain.length > 1 && renderLines" v-for="n in chain.length-1"
                                        :start="letterElements[chain[n-1]].$el.getBoundingClientRect()"
                                        :end="letterElements[chain[n]].$el.getBoundingClientRect()"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BasicLayout>
</template>
