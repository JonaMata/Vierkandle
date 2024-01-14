<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Vierkand from "@/Components/Vierkandle/Vierkand.vue";
import {computed, ref} from "vue";
import Connection from "@/Components/Vierkandle/Connection.vue";

const props = defineProps<{
    vierkandle: { letters: string, solutions: Array<{ word: string, chain: string, bonus: boolean }> },
}>()

const solutions = ref<{ [Key: number]: { [Key: string]: { bonus: boolean, guessed: boolean, chain: number[] } } }>({});
for (const solution of props.vierkandle.solutions) {
    let length = solution.word.length;
    if (solution.bonus) {
        length = 777;
    }
    if (!(length in solutions.value)) {
        solutions.value[length] = {};
    }
    solutions.value[length][solution.word] = {
        bonus: solution.bonus,
        guessed: false,
        chain: solution.chain.split(',').map((n) => parseInt(n))
    };
}

const letters = computed(() => {
    const letters: { letter: string, start: number, includes: number }[] = [];
    for (let i = 0; i < 16; i++) {
        const letter = props.vierkandle.letters[i];
        letters.push({letter, start: 0, includes: 0});
    }
    for (const subSolutions of Object.values(solutions.value)) {
        for (const solution of Object.values(subSolutions)) {
            if (solution.guessed) {
                continue;
            }
            const chain: number[] = [...solution.chain];
            for (const index of chain) {
                if (index === chain[0]) {
                    letters[index].start++;
                } else {
                    letters[index].includes++;
                }
            }
        }
    }
    return letters;
});

const inputField = ref<HTMLInputElement | null>(null);
const input = ref('');
const chain = ref<number[]>([]);

const letterElements = ref([]);

const guessWord = () => {
    const word = input.value;
    setTimeout(() => {
        input.value = '';
        chain.value = [];
    }, 0);
    if (word.length < 4) {
        return;
    }
    if (word in solutions.value[777]) {
        solutions.value[777][word].guessed = true;
        console.log(`Guessed ${word}`);
        return;
    }
    if (!(word.length in solutions.value)) {
        return;
    }
    if (!(word in solutions.value[word.length])) {
        return;
    }
    solutions.value[word.length][word].guessed = true;
    console.log(`Guessed ${word}`);
}

const handleInput = () => {
    input.value = input.value.toUpperCase();
    if (input.value.length == 0) {
        chain.value = [];
        return;
    }
    const newChain = findChain(input.value);
    if (!newChain) {
        input.value = input.value.substring(0, input.value.length - 1);
    } else {
        chain.value = newChain;
    }
}

const findChain = (word: string): false | number[] => {
    const letter = word[0];
    const starts = [...props.vierkandle.letters.matchAll(new RegExp(letter, 'g'))];
    for (const start of starts) {
        if (word.length === 1) {
            return [start.index];
        }
        const chain = recFindChain([start.index], word.substring(1));
        if (chain) {
            return chain;
        }
    }
    return false;
}

const recFindChain = (chain: number[], word: string): false | number[] => {
    for (const neighbour of findNeighbours(chain[chain.length - 1])) {
        if (props.vierkandle.letters[neighbour] === word[0] && !chain.includes(neighbour)) {
            const newChain = [...chain, neighbour];
            if (word.length === 1) {
                return newChain;
            }
            const result = recFindChain(newChain, word.substring(1));
            if (result) {
                return result;
            }
        }
    }
    return false;
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
</script>

<template>
    <AppLayout title="Vierkandle">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Vierkandle
            </h2>
        </template>

        <div class="py-12" @click="() => inputField?.focus()">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <input class="opacity-0 fixed top-[-20]" v-model="input" ref="inputField" @input="handleInput"
                           @keydown.enter="guessWord"/>
                    <br>
                    <div class="m-5 flex justify-between items-start">
                        <ul>
                            <li class="mb-4" v-for="(subSolutions, num) in solutions">
                                <h1 class="text-2xl font-bold mb-2">
                                    <template v-if="num == 777">
                                        Bonus words:
                                    </template>
                                    <template v-else>
                                        {{ num }} letter words:
                                    </template>
                                </h1>
                                <ul>
                                    <template v-for="(data, word) in subSolutions">
                                        <li class="font-bold" v-if="data.guessed">{{ word }}</li>
                                    </template>
                                </ul>
                                <span class="text-gray-600 dark:text-gray-400 italic">
                                    +{{ Object.values(subSolutions).filter((data) => data.guessed == false).length }} words left
                                </span>
                            </li>
                        </ul>
                        <div class="col-span-2">
                            <h1 class="text-4xl font-bold mb-2 w-full text-center"><span class="opacity-0">X</span>{{ input }}</h1>
                            <div class="grid grid-cols-4 grid-rows-4 gap-2" id="field">
                                <Vierkand v-for="(letter, i) in letters"
                                          ref="letterElements"
                                          :letter="letter.letter"
                                          :start="letter.start"
                                          :includes="letter.includes"
                                          show-start show-includes
                                          :is-start="chain.length > 0 && chain[0] == i"
                                />
                            </div>
                        </div>
                        <Connection v-if="chain.length > 1" v-for="n in chain.length-1"
                                    :start="letterElements[chain[n-1]].$el.getBoundingClientRect()"
                                    :end="letterElements[chain[n]].$el.getBoundingClientRect()"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
