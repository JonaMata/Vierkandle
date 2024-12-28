import Dexie, { type EntityTable } from "dexie";

interface VierkandleSolve {
    id: number,
    vierkandleId: number,
    hints: {showMissing: boolean, showLetters: boolean},
    words: string[],
    bonusWords: string[],
    mistakes: number,
}

const db = new Dexie("VierkandleDatabase") as Dexie & {
    vierkandles: EntityTable<
        VierkandleSolve,
        'id'
    >
};

db.version(1).stores({
    vierkandles: '++id, vierkandleId, hints, words, bonusWords, mistakes',
})

export type { VierkandleSolve };
export { db }
