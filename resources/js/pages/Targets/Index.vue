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

        <div class="mx-auto w-full max-w-7xl p-6 lg:p-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Ziele</h1>
                <Link :href="route('targets.create')">
                    <Button>Ziel hinzufügen</Button>
                </Link>
            </div>

            <div class="space-y-6">
                <Card v-for="target in targets" :key="target.id" class="overflow-hidden">
                    <div class="border-b border-border p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold">{{ target.title }}</h2>
                                <p class="text-sm text-muted-foreground">Typ: {{ target.target_type }}</p>
                                <p class="text-sm text-muted-foreground">Zielwert: {{ target.target_value }}</p>
                            </div>
                            <div class="flex gap-2">
                                <Button @click="toggleTracker(target.id)" variant="secondary">
                                    {{ visibleTrackers.has(target.id) ? 'Tracker ausblenden' : 'Tracker anzeigen' }}
                                </Button>
                                <Link :href="route('targets.edit', target.id)">
                                    <Button variant="ghost" size="sm">Bearbeiten</Button>
                                </Link>
                                <Link :href="route('targets.destroy', target.id)" method="delete" as="button">
                                    <Button variant="destructive" size="sm">Löschen</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                    <CardContent v-if="visibleTrackers.has(target.id)" class="p-6">
                        <ProgressTracker :target-id="target.id" />
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
