import { DataTableColumnHeader } from "@/Components/ui/data-table";
import { h } from "vue";
import BannerStatusRow from "./BannerStatusRow.vue";
import BannerRow from "./BannerRow.vue";
import BannerActionRow from "./BannerActionRow.vue";

export const TableColumn = [
    {
        accessorKey: "image",
        header: "Banner",
        cell: ({ row }) => h(BannerRow, { row: row.original }),
    },
    {
        accessorKey: "anime.title",
        header: h("div", { class: "flex items-center space-x-2" }, ["Anime"]),
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
        cell: ({ row }) =>
            h(
                "p",
                { class: "line-clamp-2 max-w-[100px]" },
                row.original.subheadline
            ),
    },
    {
        accessorKey: "is_active",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Status",
            }),
        cell: ({ row }) => h(BannerStatusRow, { row: row.original }),
    },
    {
        accessorKey: "action",
        header: h("div", { class: "flex items-center space-x-2" }, ["Aksi"]),
        cell: ({ row }) => h(BannerActionRow, { row: row.original }),
    },
];
