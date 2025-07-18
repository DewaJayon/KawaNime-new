<script setup>
import {
    FlexRender,
    getCoreRowModel,
    getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from "@tanstack/vue-table";

import { router } from "@inertiajs/vue3";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { computed, ref, watch } from "vue";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

import { Input } from "@/Components/ui/input";

import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from "@/Components/ui/pagination";

const props = defineProps({
    data: Array,
    columns: Array,
    routeName: String,
    pagination: Object,
    routeParams: {
        type: Object,
        required: false,
    },
});

const pagination = ref({
    pageIndex: props.pagination.current_page - 1,
    pageSize: props.pagination.per_page,
});

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    manualPagination: true,
    onPaginationChange: (updater) => {
        if (typeof updater === "function") {
            pagination.value = updater(pagination.value);
        } else {
            pagination.value = updater;
        }
        router.get(
            route(props.routeName, props.routeParams),
            {
                page: pagination.value.pageIndex + 1,
                per_page: pagination.value.pageSize,
            },
            { preserveState: false, preserveScroll: true }
        );
    },

    state: {
        get pagination() {
            return pagination.value;
        },
    },
});

const goToNextPage = () => {
    if (!isLastPage.value) {
        pagination.value.pageIndex += 1;
    }
};

const isLastPage = computed(() => {
    return pagination.value.pageIndex + 1 >= props.pagination.last_page;
});

watch(
    () => pagination.value.pageIndex,
    (newPage) => {
        router.get(
            route(props.routeName, props.routeParams),
            {
                page: newPage + 1,
                per_page: pagination.value.pageSize,
            },
            {
                preserveScroll: true,
                preserveState: true,
                replace: true,
            }
        );
    }
);

const search = ref("");

watch(search, (newSearch) => {
    router.get(
        route(props.routeName, props.routeParams),
        {
            page: 1,
            per_page: pagination.value.pageSize,
            search: newSearch,
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        }
    );
});
</script>

<template>
    <div class="w-full">
        <div class="pb-4">
            <Input
                class="max-w-sm h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent/85 focus-visible:ring-2 outline-none transition text-white"
                placeholder="Search..."
                v-model="search"
            />
        </div>
        <div class="rounded-md border border-gray-700/90">
            <Table>
                <TableHeader>
                    <TableRow
                        class="hover:bg-transparent"
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            class="border-b border-gray-700/90 text-center"
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="divide-y text-white">
                    <template v-if="table.getRowModel().rows?.length">
                        <template
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                        >
                            <TableRow
                                :data-state="row.getIsSelected() && 'selected'"
                                class="hover:bg-transparent"
                            >
                                <TableCell
                                    class="border-b border-gray-700/90"
                                    v-for="cell in row.getVisibleCells()"
                                    :key="cell.id"
                                >
                                    <FlexRender
                                        :render="cell.column.columnDef.cell"
                                        :props="cell.getContext()"
                                    />
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="row.getIsExpanded()">
                                <TableCell :colspan="row.getAllCells().length">
                                    {{ JSON.stringify(row.original) }}
                                </TableCell>
                            </TableRow>
                        </template>
                    </template>

                    <TableRow v-else>
                        <TableCell
                            :colspan="columns.length"
                            class="h-24 text-center"
                        >
                            No results.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
        <div class="flex items-center justify-end space-x-2 py-4 sm:space-x-6">
            <div class="flex items-center space-x-2">
                <p class="text-sm font-medium text-white">Rows per page</p>
                <Select
                    :model-value="`${table.getState().pagination.pageSize}`"
                    @update:model-value="table.setPageSize(Number($event))"
                >
                    <SelectTrigger class="h-8 w-[70px] text-white">
                        <SelectValue
                            class="text-white"
                            :placeholder="`${
                                table.getState().pagination.pageSize
                            }`"
                        />
                    </SelectTrigger>
                    <SelectContent side="top">
                        <SelectItem
                            class="cursor-pointer"
                            v-for="pageSize in [10, 20, 30, 40, 50]"
                            :key="pageSize"
                            :value="`${pageSize}`"
                        >
                            {{ pageSize }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <div class="overflow-auto sm:overflow-visible max-w-full">
                <div class="flex items-center space-x-2">
                    <Pagination
                        v-slot="{ page }"
                        :items-per-page="props.pagination.per_page"
                        :total="props.pagination.total"
                        :default-page="props.pagination.current_page"
                    >
                        <PaginationContent v-slot="{ items }" class="flex">
                            <PaginationPrevious
                                class="text-white border hover:text-gray-800 transition-all ease-in-out duration-300"
                                :disabled="!table.getCanPreviousPage()"
                                @click="table.previousPage()"
                            />

                            <template
                                v-for="(item, index) in items"
                                :key="index"
                            >
                                <PaginationItem
                                    class="text-white border hover:text-gray-800 transition-all ease-in-out duration-300 disabled:opacity-50 disabled:bg-gray-800 disabled:cursor-not-allowed"
                                    v-if="item.type === 'page'"
                                    :value="item.value"
                                    :is-active="item.value === page"
                                    :disabled="item.value === page"
                                    @click="table.setPageIndex(item.value - 1)"
                                >
                                    {{ item.value }}
                                </PaginationItem>
                            </template>

                            <PaginationNext
                                class="text-white border hover:text-gray-800 transition-all ease-in-out duration-300"
                                :disabled="isLastPage"
                                @click="goToNextPage"
                            />
                        </PaginationContent>
                    </Pagination>
                </div>
            </div>
        </div>
    </div>
</template>
