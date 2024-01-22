import {Ref, ref, watch} from "vue";
import {migrateStorage} from "@/Helpers/migrateStorage";
import axios from "axios";
import {usePage} from "@inertiajs/vue3";
import {migrations} from "@/Helpers/migrations";

export function useVierkandleStorage(vierkandle: App.Vierkandle) {

    const page = usePage()
    const user = page.props.auth.user;
    const useLocalStorage = !!localStorage;
    const storageName = 'vierkandle_' + vierkandle.id;

    const addWordLocal = (solution: App.VierkandleSolution) => {
        if (solution.bonus && !vierkandleStorage.value.bonusWords.includes(solution.word)) {
            vierkandleStorage.value.bonusWords.push(solution.word);
        } else if (!vierkandleStorage.value.words.includes(solution.word)) {
            vierkandleStorage.value.words.push(solution.word);
        }
    }

    const addWordsOnline = (solutions: App.VierkandleSolution[]) => {
        axios.post(route('guess'), {solutions: solutions}).then((response) => {
            console.log(response);
        }).catch((error) => {
            console.log(error);
        });
    }

    const newSolutionStorage: App.SolutionStorage = {
        hints: {
            showMissing: false,
            showLetters: false,
        },
        words: [],
        bonusWords: [],
        mistakes: 0,
        version: migrations[migrations.length - 1].version,
    }
    const vierkandleStorage = ref<App.SolutionStorage>(newSolutionStorage);

    if (useLocalStorage && localStorage.getItem(storageName)) {
        vierkandleStorage.value = JSON.parse(localStorage.getItem(storageName));
        migrateStorage(vierkandleStorage, vierkandle);
    } else {
        vierkandleStorage.value = newSolutionStorage;
    }
    if (user) {
        const wordsToAdd: App.VierkandleSolution[] = [];

        for (const solution: App.VierkandleSolution of vierkandle.solutions) {
            if (solution.guessed) {
                addWordLocal(solution);
            } else if (vierkandleStorage.value.words.includes(solution.word) || vierkandleStorage.value.bonusWords.includes(solution.word)) {
                solution.guessed = true;
                wordsToAdd.push(solution);
            }
        }
        if (wordsToAdd.length > 0) {
            addWordsOnline(wordsToAdd);
        }
        const wordsToRemove: string[] = [];
        for (const word of vierkandleStorage.value.words) {
            if (!vierkandle.solutions.find(solution => solution.word == word)) {
                wordsToRemove.push(word);
            }
        }
        for (const word of wordsToRemove) {
            vierkandleStorage.value.words.splice(vierkandleStorage.value.words.indexOf(word), 1);
        }
        const bonusWordsToRemove: string[] = [];
        for (const word of vierkandleStorage.value.bonusWords) {
            if (!vierkandle.solutions.find(solution => solution.word == word)) {
                bonusWordsToRemove.push(word);
            }
        }
        for (const word of bonusWordsToRemove) {
            vierkandleStorage.value.bonusWords.splice(vierkandleStorage.value.words.indexOf(word), 1);
        }
    }

    watch(vierkandleStorage, (value) => {
        if (useLocalStorage) {
            localStorage.setItem(storageName, JSON.stringify({...value}));
        }
    }, {
        deep: true,
        immediate: true,
    });


    const addWord = (solution: App.VierkandleSolution) => {
        addWordLocal(solution);
        if (user) {
            addWordsOnline([solution]);
        }
    }


    return {vierkandleStorage, addWord};
}
