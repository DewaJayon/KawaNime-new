<script setup>
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Button } from "@/Components/ui/button";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Spinner from "@/Components/Spinner.vue";
import { Icon } from "@iconify/vue";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue-sonner";
import ResetPasswordForm from "./ResetPasswordForm.vue";

const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});

const isDialogOpen = ref(false);

const form = useForm({
    name: props.row.name,
    email: props.row.email,
    role: props.row.role,
});

const edit = (id) => {
    form.put(route("user.update", id), {
        onSuccess: () => {
            toast.success("User berhasil diubah");
            isDialogOpen.value = false;
        },
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div class="flex items-center justify-center">
        <Dialog v-model:open="isDialogOpen">
            <DialogTrigger as-child>
                <Button
                    variant="ghost"
                    size="icon"
                    class="hover:bg-transparent whitespace-nowrap"
                >
                    <Icon
                        icon="material-symbols:edit-outline-rounded"
                        class="text-blue-500 text-lg"
                    />
                </Button>
            </DialogTrigger>
            <DialogContent class="sm:max-w-lg bg-gray-800 border-none">
                <DialogHeader>
                    <DialogTitle class="text-white">Edit User</DialogTitle>
                    <DialogDescription class="text-muted-foreground">
                        Edit user
                        <span class="text-white font-semibold">{{
                            row.name
                        }}</span>
                    </DialogDescription>
                </DialogHeader>
                <form
                    class="space-y-4"
                    id="form"
                    @submit.prevent="edit(row.id)"
                >
                    <div class="items-center gap-4">
                        <Label for="name" class="text-right text-white">
                            Nama
                        </Label>
                        <Input
                            placeholder="Nama"
                            autocomplete="off"
                            v-model="form.name"
                            id="name"
                            type="text"
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                        />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="items-center gap-4">
                        <Label for="email" class="text-right text-white">
                            Email
                        </Label>
                        <Input
                            placeholder="Email"
                            autocomplete="off"
                            v-model="form.email"
                            id="email"
                            type="text"
                            disabled
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="items-center gap-4">
                        <Label for="role" class="text-right text-white"
                            >Role</Label
                        >
                        <Select
                            v-model="form.role"
                            id="role"
                            class="text-white"
                        >
                            <SelectTrigger
                                class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                            >
                                <SelectValue placeholder="Pilih Role" />
                            </SelectTrigger>
                            <SelectContent class="bg-slate-800 text-white">
                                <SelectGroup>
                                    <SelectLabel>Role</SelectLabel>
                                    <SelectItem
                                        value="admin"
                                        class="cursor-pointer hover:text-black aria-selected:text-black"
                                        >Admin</SelectItem
                                    >
                                    <SelectItem
                                        value="user"
                                        class="cursor-pointer hover:text-black aria-selected:text-black"
                                        >User</SelectItem
                                    >
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>
                </form>
                <DialogFooter class="flex sm:justify-between items-center">
                    <template v-if="row.provider === 'web'">
                        <ResetPasswordForm :row="row" />
                    </template>
                    <div class="flex items-center space-x-2">
                        <DialogClose>
                            <Button
                                variant="ghost"
                                class="text-white hover:text-black transition-all duration-200 ease-in-out"
                            >
                                Batal
                            </Button>
                        </DialogClose>
                        <Button
                            :disabled="form.processing"
                            class="disabled:opacity-50 bg-zinc-600"
                            type="submit"
                            form="form"
                        >
                            <Spinner v-show="form.processing" />
                            {{ form.processing ? "Menyimpan..." : "Simpan" }}
                        </Button>
                    </div>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
