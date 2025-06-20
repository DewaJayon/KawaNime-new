<script setup>
import { Button } from "@/Components/ui/button";
import { Icon } from "@iconify/vue";
import Spinner from "@/Components/Spinner.vue";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import { toast } from "vue-sonner";

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

const page = usePage();

const isDialogOpen = ref(false);

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "",
});

const submit = () => {
    form.post(route("user.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success("User berhasil ditambahkan", {
                description: `Oleh ${page.props.auth.user.name}`,
            });
            isDialogOpen.value = false;
        },
        onError: (e) => {
            toast.error("User gagal ditambahkan");
        },
    });
};

const showPassword = () => {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
};

const showPasswordConfirmation = () => {
    var x = document.getElementById("password_confirmation");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger>
            <Button
                variant="outline"
                class="bg-transparent text-white hover:text-black transition-all duration-200 ease-in-out"
            >
                Tambah User
                <Icon icon="tabler:plus" width="20" height="20" />
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-lg bg-gray-800 border-none">
            <DialogHeader>
                <DialogTitle class="text-white">Tambah User</DialogTitle>
                <DialogDescription class="text-muted-foreground">
                    Tambah user baru
                </DialogDescription>
            </DialogHeader>
            <form class="space-y-4" id="form" @submit.prevent="submit">
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
                        class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                    />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="relative">
                    <Label for="password" class="text-right text-white"
                        >Password</Label
                    >
                    <div class="relative">
                        <Input
                            type="password"
                            id="password"
                            placeholder="••••••••"
                            v-model="form.password"
                            required
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                        />
                        <span
                            @click="showPassword"
                            class="absolute top-[50%] right-3 transform -translate-y-[50%] cursor-pointer text-gray-500 hover:text-accent transition-all ease-in-out duration-300"
                        >
                            <Icon icon="mdi-light:eye" width="30" height="30" />
                        </span>

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>
                </div>
                <div class="relative">
                    <Label
                        for="password_confirmation"
                        class="block text-sm font-medium text-gray-300 mb-1"
                        >Ulangi Password</Label
                    >
                    <div class="relative">
                        <Input
                            type="password"
                            id="password_confirmation"
                            placeholder="••••••••"
                            v-model="form.password_confirmation"
                            required
                            class="w-full h-11 py-3 rounded-lg border border-slate-700 focus-visible:ring-accent/50 focus-visible:ring-2 outline-none transition text-white"
                        />
                        <span
                            @click="showPasswordConfirmation"
                            class="absolute top-[50%] right-3 transform -translate-y-[50%] cursor-pointer text-gray-500 hover:text-accent transition-all ease-in-out duration-300"
                        >
                            <Icon icon="mdi-light:eye" width="30" height="30" />
                        </span>

                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>
                </div>
                <div class="items-center gap-4">
                    <Label for="role" class="text-right text-white">Role</Label>
                    <Select v-model="form.role" id="role" class="text-white">
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
            <DialogFooter>
                <DialogClose>
                    <Button
                        variant="ghost"
                        class="mr-2 text-white hover:text-black transition-all duration-200 ease-in-out"
                        >Batal</Button
                    >
                </DialogClose>
                <Button
                    :disabled="form.processing"
                    class="disabled:opacity-50"
                    type="submit"
                    form="form"
                >
                    <Spinner v-show="form.processing" />
                    {{ form.processing ? "Menyimpan..." : "Simpan" }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
