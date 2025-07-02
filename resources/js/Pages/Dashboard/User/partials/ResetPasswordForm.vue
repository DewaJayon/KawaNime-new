<script setup>
import InputError from "@/Components/InputError.vue";
import Spinner from "@/Components/Spinner.vue";
import { Button } from "@/Components/ui/button";
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
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Icon } from "@iconify/vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { toast } from "vue-sonner";

const props = defineProps({
    row: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    password: "",
    password_confirmation: "",
});

const showPassword = () => {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
};

const isDialogOpen = ref(false);

const submit = (id) => {
    if (props.row.provider === "google") {
        toast.error("Tidak dapat mereset password untuk akun Google");
        return;
    }

    form.patch(route("dashboard.user.reset-password", id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Password berhasil diubah");
            isDialogOpen.value = false;
        },
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger>
            <Button class="bg-red-500"> Reset Password </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-lg bg-gray-800 border-none">
            <DialogHeader>
                <DialogTitle class="text-white">Reset Password</DialogTitle>
                <DialogDescription class="text-muted-foreground">
                    Reset password untuk user {{ row.name }}
                </DialogDescription>
            </DialogHeader>
            <form
                class="space-y-4"
                id="reset-password"
                @submit.prevent="submit(row.id)"
            >
                <div class="relative">
                    <Label for="password" class="text-right text-white">
                        Password
                    </Label>
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
            </form>
            <DialogFooter>
                <DialogClose>
                    <Button
                        variant="ghost"
                        class="mr-2 text-white hover:text-black transition-all duration-200 ease-in-out"
                    >
                        Batal
                    </Button>
                </DialogClose>
                <Button
                    :disabled="form.processing"
                    class="disabled:opacity-50 bg-red-500"
                    type="submit"
                    form="reset-password"
                >
                    <Spinner v-show="form.processing" />
                    {{ form.processing ? "Menyimpan..." : "Reset" }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
