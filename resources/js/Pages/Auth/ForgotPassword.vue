<script setup>
import InputError from "@/Components/InputError.vue";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import GuestLayout from "@/Layouts/GuestLayout.vue";

import { Head, useForm } from "@inertiajs/vue3";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />
        <Card class="black-card rounded-xl w-full max-w-md relative z-10">
            <CardHeader class="text-center mb-3">
                <CardTitle class="text-3xl font-bold text-white"
                    >Lupa Password</CardTitle
                >
                <CardDescription class="text-gray-400 mt-2"
                    >Lupa kata sandi? Tidak masalah. Cukup beri tahu kami alamat
                    email Anda dan kami akan mengirimkan tautan untuk menyetel
                    ulang kata sandi melalui email yang akan memungkinkan Anda
                    memilih kata sandi baru.</CardDescription
                >
            </CardHeader>
            <CardContent>
                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <Label for="email" value="Email" />

                        <Input
                            id="email"
                            type="email"
                            class="w-full h-11 py-3 rounded-lg border border-gray-800 focus-visible:ring-accent focus-visible:ring-2 outline-none transition text-white"
                            v-model="form.email"
                            required
                            autofocus
                            placeholder="Email"
                            autocomplete="off"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <Button
                            class="w-full bg-accent hover:bg-accent/60 transition-all ease-in-out duration-300 text-black"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Email Password Reset Link
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </GuestLayout>
</template>

<style scoped>
.black-card {
    background: rgba(10, 10, 10, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(12px);
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.8), 0 0 30px rgba(59, 130, 246, 0.1);
}
</style>
