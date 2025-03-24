<script setup lang="ts">
import ProgressChart from '@/components/ProgressChart.vue';
import ProgressTracker from '@/components/ProgressTracker.vue';
import Button from '@/components/ui/button/Button.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const $props = defineProps<{
    targets: Array<{
        id: number;
        title: string;
        target_value: number;
    }>;
}>();

const STORAGE_KEYS = {
    selectedTargets: 'dashboard-selected-targets',
    visibleTrackers: 'dashboard-visible-trackers',
};

const stored = localStorage.getItem(STORAGE_KEYS.selectedTargets);
const storedTrackers = localStorage.getItem(STORAGE_KEYS.visibleTrackers);

const selectedTargetIds = ref<number[]>(
    stored ? JSON.parse(stored).filter((id: number) => $props.targets.some((t) => t.id === id)) : $props.targets.length ? [$props.targets[0].id] : [],
);

const visibleTrackers = ref<Set<number>>(storedTrackers ? new Set(JSON.parse(storedTrackers)) : new Set());

// Save changes to localStorage
watch(
    selectedTargetIds,
    (newIds) => {
        localStorage.setItem(STORAGE_KEYS.selectedTargets, JSON.stringify(newIds));
    },
    { deep: true },
);

watch(
    visibleTrackers,
    (newTrackers) => {
        localStorage.setItem(STORAGE_KEYS.visibleTrackers, JSON.stringify(Array.from(newTrackers)));
    },
    { deep: true },
);

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
];

const updateChart = ref<boolean>(false);
</script>

<template>
    <Head title="Übersicht" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-6 lg:p-8">
            <Card v-if="targets.length">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>Fortschrittsübersicht</CardTitle>
                        <Select v-model="selectedTargetIds" multiple>
                            <SelectTrigger class="w-[300px]">
                                <SelectValue :placeholder="`${selectedTargetIds.length} Ziele ausgewählt`" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="target in targets" :key="target.id" :value="target.id">
                                    {{ target.title }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </CardHeader>
                <CardContent class="space-y-6">
                    <div v-for="targetId in selectedTargetIds" :key="targetId" class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold">{{ targets.find((t) => t.id === targetId)?.title }}</h3>
                            <Button @click="toggleTracker(targetId)">
                                {{ visibleTrackers.has(targetId) ? 'Tracker ausblenden' : 'Tracker anzeigen' }}
                            </Button>
                        </div>
                        <ProgressChart v-model="updateChart" :target-id="targetId" />
                        <ProgressTracker v-if="visibleTrackers.has(targetId)" :target-id="targetId" @progress-tracked="updateChart = true" />
                    </div>
                </CardContent>
            </Card>
            <Card v-else>
                <CardContent class="py-6 text-center"
                    >Keine Ziele gefunden. Erstellen Sie Ihr erstes Ziel, um den Fortschritt zu verfolgen.</CardContent
                >
            </Card>
        </div>
    </AppLayout>
</template>
