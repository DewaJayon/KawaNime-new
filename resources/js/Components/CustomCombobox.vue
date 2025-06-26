<script setup>
import { ref, computed, watch } from "vue";
import {
    Command,
    CommandInput,
    CommandList,
    CommandEmpty,
    CommandGroup,
    CommandItem,
} from "@/Components/ui/command";
import {
    Popover,
    PopoverTrigger,
    PopoverContent,
} from "@/Components/ui/popover";
import { Button } from "@/Components/ui/button";
import { ChevronsUpDown, Check } from "lucide-vue-next";

const props = defineProps({
    modelValue: [String, Number],
    items: { type: Array, default: () => [] },
    valueKey: { type: String, default: "slug" },
    labelKey: { type: String, default: "name" },
    placeholder: { type: String, default: "Pilih item..." },
    searchTerm: String,
    loading: Boolean,
});
const emit = defineEmits(["update:modelValue", "update:searchTerm"]);

const open = ref(false);
const searchTerm = computed({
    get: () => props.searchTerm,
    set: (val) => emit("update:searchTerm", val),
});

defineExpose({ searchTerm });

const selectedItem = computed(() =>
    props.items.find((item) => item[props.valueKey] === props.modelValue)
);

const filteredItems = computed(() => props.items);

function selectItem(item) {
    emit("update:modelValue", item[props.valueKey]);
    open.value = false;
}

watch(searchTerm, (val) => emit("update:searchTerm", val));
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                class="w-full justify-between bg-transparent hover:bg-transparent border-slate-700 h-11 py-3 text-white"
            >
                {{ selectedItem ? selectedItem[labelKey] : placeholder }}
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[--radix-popover-trigger-width] p-0">
            <Command class="bg-slate-800">
                <CommandInput
                    class="border-0 bg-transparent text-sm text-muted-foreground placeholder:text-muted-foreground focus:ring-0"
                    :placeholder="`Cari ${placeholder.toLowerCase()}`"
                    v-model="searchTerm"
                />
                <CommandEmpty>Tidak ditemukan.</CommandEmpty>
                <CommandList>
                    <CommandGroup v-if="!props.loading">
                        <CommandItem
                            v-for="item in filteredItems"
                            :key="item[valueKey]"
                            :value="item[valueKey]"
                            class="text-white hover:text-black cursor-pointer"
                            @select="selectItem(item)"
                        >
                            <Check
                                class="mr-2 h-4 w-4"
                                :class="{
                                    'opacity-100':
                                        item[valueKey] === modelValue,
                                    'opacity-0': item[valueKey] !== modelValue,
                                }"
                            />
                            {{ item[labelKey] }}
                        </CommandItem>
                    </CommandGroup>
                    <div v-else class="text-center text-white p-4 text-sm">
                        Loading...
                    </div>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
