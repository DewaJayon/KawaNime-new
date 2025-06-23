import { DataTableColumnHeader } from "@/Components/ui/data-table";
import { h } from "vue";
import AnimeThumbnail from "./AnimeThumbnail.vue";
import AnimeStatusColumn from "./AnimeStatusColumn.vue";
import AnimeTypeColumn from "./AnimeTypeColumn.vue";
import AnimeActionColumn from "./AnimeActionColumn.vue";

export const AnimeColumn = [
    {
        accessorKey: "thumbnail",
        header: "Thumbnail",
        cell: ({ row }) =>
            h(AnimeThumbnail, {
                row: row.original,
            }),
    },
    {
        accessorKey: "title",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Judul Anime",
            }),
    },
    {
        accessorKey: "type",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Tipe",
            }),
        cell: ({ row }) =>
            h(AnimeTypeColumn, {
                row: row.original,
            }),
    },
    {
        accessorKey: "status",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Status",
            }),
        cell: ({ row }) =>
            h(AnimeStatusColumn, {
                row: row.original,
            }),
    },
    {
        accessorKey: "studio",
        header: h("div", { class: "flex items-center space-x-2" }, ["Studio"]),
    },
    {
        accessorKey: "action",
        header: h("div", { class: "flex items-center space-x-2" }, ["Aksi"]),
        cell: ({ row }) =>
            h(AnimeActionColumn, {
                row: row.original,
            }),
    },
];
