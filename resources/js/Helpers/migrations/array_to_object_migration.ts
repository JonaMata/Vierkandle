import axios from "axios";
import {BaseMigration} from "@/Helpers/migrations/BaseMigration";
import {Ref} from "vue";

export class Array_to_object_migration extends BaseMigration {
    public static version = '0.0.1';

    public static up(vierkandleStrorage: Ref, vierkandle: App.Vierkandle) {
        const words = vierkandleStrorage.value;
        const newStorage: {
            hints: {
                showMissing: boolean;
                showLetters: boolean;
            };
            words: string[];
            bonusWords: string[];
            mistakes: number;
        } = {
            hints: {
                showMissing: false,
                showLetters: false,
            },
            words: [],
            bonusWords: [],
            mistakes: 0,
        };
        for (const word of words) {
            const solution = vierkandle.solutions!.filter((data) => data.word == word)[0];
            if (solution && solution.bonus) {
                newStorage.bonusWords.push(word);
            } else if (solution && !solution.bonus) {
                newStorage.words.push(word);
            }
        }
        vierkandleStrorage.value = newStorage;
    }

    public static down(vierkandleStorage: Ref, vierkandle: App.Vierkandle) {
        const words = vierkandleStorage.value.words.concat(vierkandleStorage.value.bonusWords);
        vierkandleStorage.value = words;
    }
}
