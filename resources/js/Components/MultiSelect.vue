<script setup>
import { ref, watch, computed } from "vue";
import {
    Popover,
    PopoverTrigger,
    PopoverContent,
} from "@/Components/ui/popover";
import { Button } from "@/Components/ui/button";
import { Checkbox } from "@/Components/ui/checkbox";
import { Input } from "@/Components/ui/input";
import { Icon } from "@iconify/vue";

const props = defineProps({
    options: {
        type: Array,
        default: () => [],
    },
    modelValue: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: "Select options",
    },
    dropdownClass: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);
const selected = ref([...props.modelValue]);
const search = ref("");

watch(
    () => props.modelValue,
    (val) => {
        selected.value = [...val];
    }
);

const toggleOption = (value) => {
    if (selected.value.includes(value)) {
        selected.value = selected.value.filter((v) => v !== value);
    } else {
        selected.value.push(value);
    }
    emit("update:modelValue", selected.value);
};

const removeOption = (value) => {
    selected.value = selected.value.filter((v) => v !== value);
    emit("update:modelValue", selected.value);
};

const selectedLabels = computed(() =>
    props.options.filter((opt) => selected.value.includes(opt.value))
);

const filteredOptions = computed(() => {
    if (!search.value) return props.options;
    return props.options.filter((opt) =>
        opt.label.toLowerCase().includes(search.value.toLowerCase())
    );
});
</script>

<template>
    <div class="w-full">
        <Popover>
            <PopoverTrigger as-child>
                <Button
                    variant="outline"
                    class="w-full justify-start min-h-[42px] bg-transparent hover:bg-transparent border-slate-700 h-auto py-2"
                >
                    <div class="flex flex-wrap items-center gap-2 w-full">
                        <template v-if="selectedLabels.length">
                            <div
                                v-for="opt in selectedLabels"
                                :key="opt.value"
                                class="flex items-center px-2 py-0.5 bg-primary text-white rounded-full text-sm"
                            >
                                {{ opt.label }}
                                <Icon
                                    icon="mdi:close"
                                    class="ml-1 cursor-pointer"
                                    @click.stop="removeOption(opt.value)"
                                />
                            </div>
                        </template>
                        <span v-else class="text-muted-foreground">{{
                            placeholder
                        }}</span>
                    </div>
                </Button>
            </PopoverTrigger>
            <PopoverContent
                class="w-[--radix-popover-trigger-width] max-h-72 overflow-y-auto p-2 space-y-2"
                :class="dropdownClass"
            >
                <!-- Search Input -->
                <Input v-model="search" placeholder="Cari..." class="w-full" />

                <!-- Options -->
                <div class="flex flex-col gap-1 max-h-52 overflow-y-auto">
                    <label
                        v-for="opt in filteredOptions"
                        :key="opt.value"
                        class="flex items-center gap-2 cursor-pointer px-2 py-1 rounded hover:bg-accent transition hover:text-black"
                    >
                        <Checkbox
                            :modelValue="selected.includes(opt.value)"
                            @update:modelValue="() => toggleOption(opt.value)"
                        />
                        <span class="text-sm">{{ opt.label }}</span>
                    </label>
                    <div
                        v-if="!filteredOptions.length"
                        class="text-sm text-muted-foreground px-2 py-1"
                    >
                        Tidak ditemukan
                    </div>
                </div>
            </PopoverContent>
        </Popover>
    </div>
</template>
