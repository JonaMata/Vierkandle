import {BaseMigration} from "@/Helpers/migrations/BaseMigration";
import {Ref} from "vue";

export class Add_solve_state_migration extends BaseMigration {
    public static version = '0.0.2';

    public static up(vierkandleStrorage: Ref, vierkandle: App.Vierkandle) {
        let solveState: App.VierkandleSolveState = App.VierkandleSolveState.UNSOLVED

        if (vierkandleStrorage.value.words.length === vierkandle.solution_count) {
            if (vierkandleStrorage.value.mistakes === 0) {
                solveState = App.VierkandleSolveState.PERFECT
            } else {
                solveState = App.VierkandleSolveState.SOLVED
            }
        }

        vierkandleStrorage.value = {
            ...vierkandleStrorage.value,
            solveState: solveState
        };
    }

    public static down(vierkandleStorage: Ref, vierkandle: App.Vierkandle) {
        delete vierkandleStorage.value.solveState;
        vierkandleStorage.value = {
            ...vierkandleStorage.value,
        };
    }
}
