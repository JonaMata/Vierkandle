import {migrations} from "@/Helpers/migrations";
import {useVersion} from "@/Composables/useVersion";
import {Ref} from "vue";

export function migrateStorage(vierkandleStorage: Ref, vierkandle: App.Vierkandle) {
    const version = vierkandleStorage.value.version ?? "0.0.0";
    const current = migrations.findIndex((migration) => migration.version == version);
    const migrationsToRun = migrations.slice(current + 1);
    for (const migration of migrationsToRun) {
        console.log(`Running migration ${migration.version}`);
        migration.up(vierkandleStorage, vierkandle);
        vierkandleStorage.value.version = migration.version;
    }
}
