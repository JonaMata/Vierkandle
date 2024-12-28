import {Array_to_object_migration} from "@/Helpers/migrations/array_to_object_migration";
import {Add_solve_state_migration} from "@/Helpers/migrations/add_solve_state_migration";

export const migrations = [
    Array_to_object_migration,
    Add_solve_state_migration,
];
