<script setup>
import { Icon } from "@iconify/vue";
import { Button } from "@/Components/ui/button";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import {
    AlertDialog,
    AlertDialogTrigger,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogCancel,
} from "@/Components/ui/alert-dialog";
import Spinner from "@/Components/Spinner.vue";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import InputError from "@/Components/InputError.vue";

const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const dialogRef = ref(false);

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => (dialogRef.value = false),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <AlertDialog v-model:open="dialogRef">
        <AlertDialogTrigger as-child>
            <Button
                variant="outline"
                class="bg-transparent rounded-xl text-red-500 border-red-500 hover:bg-red-600/50 transition-all duration-500 ease-in-out"
            >
                <Icon icon="line-md:account-delete" width="24" height="24" />
                Hapus Akun
            </Button>
        </AlertDialogTrigger>
        <AlertDialogContent class="sm:max-w-lg bg-gray-800 border-none">
            <AlertDialogHeader>
                <AlertDialogTitle class="text-white">
                    Apakah Anda yakin ingin menghapus akun Anda?
                </AlertDialogTitle>
                <AlertDialogDescription class="text-white">
                    Setelah akun Anda dihapus, semua sumber daya dan datanya
                    akan dihapus secara permanen. Masukkan kata sandi Anda untuk
                    mengonfirmasi bahwa Anda ingin menghapus akun Anda secara
                    permanen.
                </AlertDialogDescription>
            </AlertDialogHeader>

            <Label for="password" class="text-white">Password</Label>
            <Input
                id="password"
                ref="passwordInput"
                v-model="form.password"
                type="password"
                placeholder="Password"
                class="w-full h-11 py-3 rounded-lg border border-red-500 focus:outline-none focus-visible:ring-red-500 focus-visible:ring-2 outline-none transition text-white"
                @keyup.enter="deleteUser"
            />
            <InputError :message="form.errors.password" class="mt-2" />

            <AlertDialogFooter>
                <AlertDialogCancel
                    class="hover:bg-white/70 hover:border-white/10"
                >
                    Batal
                </AlertDialogCancel>
                <Button
                    :disabled="form.processing"
                    :class="{ 'opacity-25': form.processing }"
                    @click="deleteUser"
                    class="bg-red-500 hover:bg-red-600 disabled:cursor-not-allowed"
                >
                    <Spinner v-show="form.processing" />
                    {{ form.processing ? "Menghapus..." : "Hapus" }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
