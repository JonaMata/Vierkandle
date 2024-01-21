namespace App {
    export interface Vierkandle {
        id: number;
        letters: string;
        date: date;
        solutions_count: number;
        solutions?: VierkandleSolution[];
    }

    export interface VierkandleSolution {
        id: number;
        word: string;
        url: string;
        bonus: boolean;
        guessed: boolean | null;
        chain: string;
    }

    export interface VierkandleStorage {
        hints: {showMissing: boolean, showLetters: boolean},
        words: string[],
        bonusWords: string[],
        mistakes: number,
    }
}
