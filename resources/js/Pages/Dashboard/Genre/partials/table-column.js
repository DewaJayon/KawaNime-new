import { DataTableColumnHeader } from "@/Components/ui/data-table";
import { h } from "vue";
import GenreAction from "./GenreAction.vue";

export const TableColumn = [
    {
        accessorKey: "name",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Nama Genre",
            }),
    },
    {
        accessorKey: "action",
        header: "Aksi",
        cell: ({ row }) =>
            h(GenreAction, {
                row: row.original,
            }),
    },
];
