import { DataTableColumnHeader } from "@/Components/ui/data-table";
import { h } from "vue";

export const TableColumn = [
    {
        accessorKey: "image",
        header: "Banner",
    },
    {
        accessorKey: "headline",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Judul",
            }),
    },
    {
        accessorKey: "subheadline",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Sub Judul",
            }),
    },
    {
        accessorKey: "is_active",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Status",
            }),
    },
    {
        accessorKey: "action",
        header: "Aksi",
    },
];
