<script setup>
import { ref } from "vue";

const props = defineProps({
    data: Array,
    columns: Array,
});

import {
    useVueTable,
    FlexRender,
    getCoreRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    getFilteredRowModel,
} from "@tanstack/vue-table";
import { Icon } from "@iconify/vue";

const data = ref(props.data);

const sorting = ref([]);
const filter = ref("");

const table = useVueTable({
    data: data.value,
    columns: props.columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    state: {
        get sorting() {
            return sorting.value;
        },
        get globalFilter() {
            return filter.value;
        },
    },
    onSortingChange: (updaterOrValue) => {
        sorting.value =
            typeof updaterOrValue === "function"
                ? updaterOrValue(sorting.value)
                : updaterOrValue;
    },
    initialState: {
        pagination: {
            pageSize: 10,
        },
    },
});
</script>

<template>
    <div class="p-6 bg-gray-900 text-white rounded-xl shadow">
        <!-- Header controls -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4"
        >
            <!-- Entries dropdown -->
            <div class="flex items-center space-x-2 text-sm text-white">
                <label for="pageSize">Show</label>

                <div class="relative">
                    <select
                        id="pageSize"
                        v-model="table.getState().pagination.pageSize"
                        @change="(e) => table.setPageSize(+e.target.value)"
                        class="appearance-none bg-gray-800 border border-gray-600 text-white text-sm rounded px-3 py-1.5 pr-8"
                    >
                        <option
                            v-for="size in [5, 10, 20, 50]"
                            :key="size"
                            :value="size"
                        >
                            {{ size }}
                        </option>
                    </select>
                </div>

                <span>entries</span>
            </div>

            <!-- Search -->
            <div class="relative">
                <input
                    type="text"
                    class="bg-gray-800 border border-gray-600 text-white rounded px-4 py-2 pl-10 w-64 text-sm"
                    placeholder="Search..."
                    v-model="filter"
                />
                <span class="absolute left-3 top-1.5 text-gray-400">
                    <Icon
                        icon="material-symbols:search-rounded"
                        width="24"
                        height="24"
                    />
                </span>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-700">
            <table class="min-w-full divide-y divide-gray-700 text-sm">
                <thead class="bg-gray-800">
                    <tr
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <th
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                            class="px-4 py-3 text-left font-semibold tracking-wider cursor-pointer select-none text-white"
                            @click="
                                header.column.getToggleSortingHandler()?.(
                                    $event
                                )
                            "
                        >
                            <FlexRender
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                            <span v-if="header.column.getIsSorted()">
                                {{
                                    header.column.getIsSorted() === "asc"
                                        ? "▲"
                                        : "▼"
                                }}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="bg-gray-900 divide-y divide-gray-800 text-gray-300"
                >
                    <tr
                        v-for="row in table.getRowModel().rows"
                        :key="row.id"
                        class="hover:bg-gray-800"
                    >
                        <td
                            v-for="cell in row.getVisibleCells()"
                            :key="cell.id"
                            class="px-4 py-3 whitespace-nowrap"
                        >
                            <FlexRender
                                :render="cell.column.columnDef.cell"
                                :props="cell.getContext()"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between mt-6 text-sm text-gray-400 gap-4"
        >
            <div>
                Showing
                <span class="text-white">{{
                    table.getState().pagination.pageIndex *
                        table.getState().pagination.pageSize +
                    1
                }}</span>
                to
                <span class="text-white">{{
                    Math.min(
                        (table.getState().pagination.pageIndex + 1) *
                            table.getState().pagination.pageSize,
                        table.getFilteredRowModel().rows.length
                    )
                }}</span>
                of
                <span class="text-white">{{
                    table.getFilteredRowModel().rows.length
                }}</span>
                entries
            </div>
            <div class="flex gap-2">
                <button
                    @click="table.previousPage()"
                    :disabled="!table.getCanPreviousPage()"
                    class="px-3 py-1 rounded border border-gray-600 text-white hover:bg-gray-700 disabled:opacity-50"
                >
                    Prev
                </button>
                <button
                    @click="table.nextPage()"
                    :disabled="!table.getCanNextPage()"
                    class="px-3 py-1 rounded border border-gray-600 text-white hover:bg-gray-700 disabled:opacity-50"
                >
                    Next
                </button>
            </div>
        </div>
    </div>
</template>
