<script setup lang="ts">
import ProgressTracker from '@/components/ProgressTracker.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    targets: Array<{
        id: number;
        title: string;
        target_type: string;
        target_value: number;
    }>;
}>();

const visibleTrackers = ref<Set<number>>(new Set());

const toggleTracker = (targetId: number) => {
    if (visibleTrackers.value.has(targetId)) {
        visibleTrackers.value.delete(targetId);
    } else {
        visibleTrackers.value.add(targetId);
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Übersicht',
        href: route('dashboard'),
    },
    {
        title: 'Ziele',
        href: route('targets.index'),
    },
];
</script>

<template>
    <AppLayout>
        <Head title="Ziele" />

        <div class="mx-auto w-full max-w-7xl p-4 sm:p-6 lg:p-8">
            <div class="mb-6 flex flex-col items-center justify-between gap-4 sm:flex-row">
                <h1 class="text-2xl font-semibold">Ziele</h1>
                <Link :href="route('targets.create')">
                    <Button>Ziel hinzufügen</Button>
                </Link>
            </div>

            <div class="space-y-4 sm:space-y-6">
                <Card v-for="target in targets" :key="target.id" class="overflow-hidden">
                    <div class="border-b border-border p-4 sm:p-6">
                        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                            <div class="flex w-full flex-row justify-between sm:w-auto sm:items-center sm:gap-4">
                                <h2 class="text-lg font-semibold">{{ target.title }}</h2>
                                <div class="hidden flex-col sm:flex">
                                    <p class="text-sm text-muted-foreground">Zielwert: {{ target.target_value }}</p>
                                    <p class="text-sm text-muted-foreground">Typ: {{ target.target_type }}</p>
                                </div>
                                <div class="mt-1 flex justify-end sm:hidden">
                                    <div class="flex flex-col items-end">
                                        <p class="text-sm text-muted-foreground">Zielwert: {{ target.target_value }}</p>
                                        <p class="text-sm text-muted-foreground">Typ: {{ target.target_type }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                                <Button @click="toggleTracker(target.id)" variant="secondary" class="w-full sm:w-auto">
                                    {{ visibleTrackers.has(target.id) ? 'Tracker ausblenden' : 'Tracker anzeigen' }}
                                </Button>
                                <div class="flex w-full gap-2 sm:w-auto">
                                    <Link :href="route('targets.edit', target.id)" class="w-full sm:w-auto">
                                        <Button variant="ghost" size="sm" class="w-full">Bearbeiten</Button>
                                    </Link>
                                    <Link :href="route('targets.destroy', target.id)" method="delete" as="button" class="w-full sm:w-auto">
                                        <Button variant="destructive" size="sm" class="w-full">Löschen</Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <CardContent v-if="visibleTrackers.has(target.id)" class="p-4 sm:p-6">
                        <ProgressTracker :target-id="target.id" />
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
