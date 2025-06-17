<script setup>
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from "@/Components/ui/pagination";

const props = defineProps({
    pagination: Object,
});

const emit = defineEmits(["page-changed"]);

function goToPage(page) {
    if (
        page >= 1 &&
        page <= props.pagination.last_page &&
        page !== props.pagination.current_page
    ) {
        emit("page-changed", page);
    }
}
</script>

<template>
    <div class="flex items-center justify-between text-sm mt-4">
        <!-- Info halaman -->
        <div class="text-white">
            Showing
            <strong>{{ pagination.from }}</strong>
            to
            <strong>{{ pagination.to }}</strong>
            of
            <strong>{{ pagination.total }}</strong>
            entries
        </div>

        <!-- Tombol navigasi -->
        <div class="flex space-x-1">
            <Pagination
                v-slot="{ page }"
                :page="pagination.current_page"
                :total="pagination.last_page"
            >
                <PaginationContent v-slot="{ items }">
                    <PaginationPrevious
                        class="text-white hover:text-gray-800 transition-all ease-in-out duration-300"
                        :disabled="pagination.current_page === 1"
                        @click="goToPage(pagination.current_page - 1)"
                    />

                    <template v-for="(item, index) in items" :key="index">
                        <PaginationItem
                            class="text-white hover:text-gray-800 transition-all ease-in-out duration-300"
                            :class="{
                                'bg-gray-800 text-white':
                                    item.type === 'page' && item.value === page,
                            }"
                            v-if="item.type === 'page'"
                            :value="item.value"
                            :isActive="item.value === page"
                            @click="goToPage(item.value)"
                        >
                            {{ item.value }}
                        </PaginationItem>
                    </template>

                    <PaginationEllipsis :index="4" class="text-white" />

                    <PaginationNext
                        class="text-white hover:text-gray-800 transition-all ease-in-out duration-300"
                        :disabled="
                            pagination.current_page === pagination.last_page
                        "
                        @click="goToPage(pagination.current_page + 1)"
                    />
                </PaginationContent>
            </Pagination>
        </div>
    </div>
</template>
