import { DataTableColumnHeader } from "@/Components/ui/data-table";
import { h } from "vue";
import EpisodeThumbnailRow from "./EpisodeThumbnailRow.vue";
import EpisodeActionRow from "./EpisodeActionRow.vue";

export const episodeColumn = [
    {
        accessorKey: "thumbnail",
        header: "Thumbnail",
        cell: ({ row }) => h(EpisodeThumbnailRow, { row: row.original }),
    },
    {
        accessorKey: "title",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Judul Episode",
            }),
    },
    {
        accessorKey: "duration",
        header: ({ column }) =>
            h(DataTableColumnHeader, {
                column: column,
                title: "Durasi",
            }),
        cell: ({ row }) => row.original.duration + " Menit",
    },
    {
        accessorKey: "action",
        header: h("div", { class: "flex items-center space-x-2" }, ["Aksi"]),
        cell: ({ row }) => h(EpisodeActionRow, { row: row.original }),
    },
];
