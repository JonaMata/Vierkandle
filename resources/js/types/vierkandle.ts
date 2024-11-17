namespace App {
    export interface Vierkandle {
        id: number;
        letters: string;
        date: Date;
        is_daily: boolean;
        is_express: boolean;
        solution_count: number;
        size: number;
        solutions?: VierkandleSolution[];
    }

    export interface VierkandleSolution {
        id: number;
        word: string;
        url: string;
        bonus: boolean;
        guessed: boolean | null;
        chain: number[];
    }

    export interface VierkandleSolve {
        id: number,
        vierkandle_id: number
        user_id: number,
        solution_ids: number[],
        created_at: Date,
        updated_at: Date,
    }

    export interface VierkandleStorage {
        hints: {showMissing: boolean, showLetters: boolean},
        words: string[],
        bonusWords: string[],
        mistakes: number,
    }

    export interface SolutionStorage {
        hints: {
            showMissing: boolean,
            showLetters: boolean
        },
        words: string[],
        bonusWords: string[],
        mistakes: number,
        version: string,
    }
}
