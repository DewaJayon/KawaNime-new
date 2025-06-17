<script setup>
import { ref, watch, computed, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import DataTablePagination from "./DataTablePagination.vue";
import ColumnHeader from "./ColumnHeader.vue";

const props = defineProps({
    columns: Array,
    rows: Array,
    pagination: Object,
    search: String,
    filters: Object,
    routeName: String,
    perPage: Number,
    perPageOptions: {
        type: Array,
        default: () => [10, 25, 50, 100],
    },
});

const searchTerm = ref(props.search || "");
const currentPage = ref(props.pagination?.current_page || 1);
const selectedPerPage = ref(props.perPage || 10);

// Inisialisasi nilai dari URL saat pertama kali load
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    searchTerm.value = urlParams.get("search") || "";
    selectedPerPage.value =
        parseInt(urlParams.get("per_page")) || props.perPage || 10;
});

// Debounce untuk pencarian
const debouncedSearch = debounce((newValue) => {
    if (newValue !== props.search) {
        goToPage(1);
    }
}, 500);

watch(searchTerm, (newValue) => {
    debouncedSearch(newValue);
});

watch(selectedPerPage, () => {
    goToPage(currentPage.value);
});

function goToPage(page) {
    router.get(
        route(props.routeName),
        {
            page,
            search: searchTerm.value,
            per_page: selectedPerPage.value,
            ...props.filters,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
    currentPage.value = page;
}
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <label class="text-sm font-medium text-gray-300 mr-2"
                    >Show</label
                >
                <select
                    v-model="selectedPerPage"
                    class="bg-gray-700 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1.5"
                >
                    <option
                        v-for="option in perPageOptions"
                        :key="option"
                        :value="option"
                        class="bg-gray-800"
                    >
                        {{ option }}
                    </option>
                </select>
            </div>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                >
                    <svg
                        class="w-4 h-4 text-gray-400"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 20 20"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                        />
                    </svg>
                </div>
                <input
                    v-model="searchTerm"
                    type="text"
                    class="bg-gray-700 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="Search..."
                />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-700 text-gray-300">
                    <tr>
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            class="px-4 py-3 text-left"
                        >
                            <ColumnHeader :column="col" />
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    <tr
                        v-for="row in rows"
                        :key="row.id"
                        class="border-b border-gray-700 hover:bg-gray-700/50"
                    >
                        <td
                            v-for="col in columns"
                            :key="col.key"
                            class="px-4 py-3"
                        >
                            {{ row[col.key] }}
                        </td>
                    </tr>
                    <tr v-if="rows.length === 0">
                        <td
                            :colspan="columns.length"
                            class="px-4 py-3 text-center text-gray-400"
                        >
                            No data found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <DataTablePagination
            :pagination="pagination"
            @page-changed="goToPage"
        />
    </div>
</template>
