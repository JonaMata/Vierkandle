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

    export interface VierkandleStorage {
        hints: {showMissing: boolean, showLetters: boolean},
        words: string[],
        bonusWords: string[],
        mistakes: number,
    }
}
