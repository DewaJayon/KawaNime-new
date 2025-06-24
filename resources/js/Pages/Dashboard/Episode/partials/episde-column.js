import { DataTableColumnHeader } from "@/Components/ui/data-table";
import { h } from "vue";

export const episodeColumn = [
    {
        accessorKey: "thumbnail",
        header: "Thumbnail",
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
    },
    {
        accessorKey: "action",
        header: h("div", { class: "flex items-center space-x-2" }, ["Aksi"]),
    },
];
