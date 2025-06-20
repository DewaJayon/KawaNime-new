import { DataTableColumnHeader } from "@/Components/ui/data-table";
import { h } from "vue";
import UserRole from "./UserRole.vue";
import UserAction from "./UserAction.vue";

export const UserColumn = [
    {
        accessorKey: "name",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Nama",
            }),
    },
    {
        accessorKey: "email",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Email",
            }),
    },
    {
        accessorKey: "role",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Role",
            }),
        cell: ({ row }) =>
            h(UserRole, {
                row: row.original,
            }),
    },
    {
        accessorKey: "provider",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Provider",
            }),
    },
    {
        accessorKey: "action",
        header: "Aksi",
        cell: ({ row }) =>
            h(UserAction, {
                row: row.original,
            }),
    },
];
