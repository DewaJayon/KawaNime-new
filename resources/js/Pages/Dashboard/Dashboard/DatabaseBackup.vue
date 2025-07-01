<script setup>
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
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
import { Button } from "@/Components/ui/button";
import { onMounted, ref } from "vue";
import { Icon } from "@iconify/vue";
import axios from "axios";
import { toast } from "vue-sonner";
import Spinner from "@/Components/Spinner.vue";

const databaseBackups = ref([]);
const dialogRef = ref(false);
const isDeleting = ref(false);
const isBackuping = ref(false);

const backupDatabase = () => {
    isBackuping.value = true;
    axios
        .post(route("dashboard.create-backup-database"))
        .then((res) => {
            isBackuping.value = false;
            axios.get(route("dashboard.backup-database")).then((res) => {
                databaseBackups.value = res.data.data;
            });
            toast.success("Database berhasil dibackup", {
                description: res.data?.data ?? "Backup selesai!",
            });
        })
        .catch((error) => {
            console.log(error);
        });
};

const destroy = (file) => {
    dialogRef.value = true;
    isDeleting.value = true;
    axios
        .delete(route("dashboard.delete-backup-database", file))
        .then((res) => {
            toast.success(res.data.data);
            isDeleting.value = false;
            dialogRef.value = false;
            axios.get(route("dashboard.backup-database")).then((res) => {
                databaseBackups.value = res.data.data;
            });
        });
};

const download = (file) => {
    window.open(
        route("dashboard.download-backup-database", { file }),
        "_blank"
    );
};

onMounted(() => {
    axios
        .get(route("dashboard.backup-database"))
        .then((res) => {
            databaseBackups.value = res.data.data;
        })
        .catch((error) => {
            console.log(error);
        });
});
</script>

<template>
    <div class="bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold mb-6 text-gray-200">
                Backup Database
            </h2>
            <Button
                @click="backupDatabase"
                variant="outline"
                class="bg-transparent border-accent text-accent hover:bg-slate-500/50 transition-all duration-500 ease-in-out"
            >
                <Spinner v-if="isBackuping" />
                {{ isBackuping ? "Backup Database..." : "Backup Database" }}
            </Button>
        </div>
        <Table>
            <TableCaption> List backup database </TableCaption>
            <TableHeader>
                <TableRow class="hover:bg-transparent">
                    <TableHead class="border-b border-gray-700/90 text-center">
                        Nomor
                    </TableHead>
                    <TableHead class="border-b border-gray-700/90 text-center">
                        Nama
                    </TableHead>
                    <TableHead class="border-b border-gray-700/90 text-center">
                        Ukuran
                    </TableHead>
                    <TableHead class="border-b border-gray-700/90 text-center">
                        Tanggal
                    </TableHead>
                    <TableHead class="border-b border-gray-700/90 text-center">
                        Aksi
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="databaseBackups.length > 0">
                    <TableRow
                        v-for="backup in databaseBackups"
                        :key="backup.date"
                        class="hover:bg-transparent text-white text-center border-b border-gray-700/90"
                    >
                        <TableCell>
                            {{ Number(databaseBackups.indexOf(backup)) + 1 }}
                        </TableCell>
                        <TableCell> {{ backup.name }}</TableCell>
                        <TableCell>{{ backup.size }}</TableCell>
                        <TableCell>{{ backup.date }}</TableCell>
                        <TableCell>
                            <Button
                                @click="download(backup.name)"
                                variant="ghost"
                                size="icon"
                                class="hover:bg-transparent"
                            >
                                <Icon
                                    icon="material-symbols:download-rounded"
                                    class="text-accent text-lg"
                                />
                            </Button>
                            <AlertDialog v-model:open="dialogRef">
                                <AlertDialogTrigger as-child>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="hover:bg-transparent whitespace-nowrap"
                                    >
                                        <Icon
                                            icon="material-symbols:delete-outline-rounded"
                                            class="text-red-500 text-lg"
                                        />
                                    </Button>
                                </AlertDialogTrigger>
                                <AlertDialogContent
                                    class="sm:max-w-lg bg-gray-800 border-none"
                                >
                                    <AlertDialogHeader>
                                        <AlertDialogTitle class="text-white"
                                            >Yakin ingin
                                            menghapus?</AlertDialogTitle
                                        >
                                        <AlertDialogDescription
                                            class="text-white"
                                        >
                                            Data backup database akan dihapus
                                            permanen!
                                        </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                        <AlertDialogCancel
                                            class="hover:bg-white/70 hover:border-white/10"
                                        >
                                            Batal
                                        </AlertDialogCancel>
                                        <Button
                                            @click="destroy(backup.name)"
                                            :disabled="isDeleting"
                                            class="bg-red-500 hover:bg-red-600"
                                        >
                                            <Spinner v-show="isDeleting" />
                                            {{
                                                isDeleting
                                                    ? "Menghapus..."
                                                    : "Hapus"
                                            }}
                                        </Button>
                                    </AlertDialogFooter>
                                </AlertDialogContent>
                            </AlertDialog>
                        </TableCell>
                    </TableRow>
                </template>

                <TableRow
                    v-else
                    class="hover:bg-transparent text-white text-center"
                >
                    <TableCell class="h-24 text-center" colspan="4">
                        Belum ada data.
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
