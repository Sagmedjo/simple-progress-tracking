<script setup lang="ts">
import ProgressChart from '@/components/ProgressChart.vue';
import ProgressTracker from '@/components/ProgressTracker.vue';
import Button from '@/components/ui/button/Button.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const $props = defineProps<{
    targets: Array<{
        id: number;
        title: string;
        target_value: number;
        description: string;
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

const updateCharts = ref<Map<number, boolean>>(new Map());

watch(selectedTargetIds, (value) => {
    const missing = [];

    value.forEach((targetId) => {
        if (!updateCharts.value.has(targetId)) {
            updateCharts.value.set(targetId, false);
            triggerChartUpdate(targetId);
        }
    });

    updateCharts.value.forEach((_, key) => {
        if (!value.includes(key)) {
            updateCharts.value.delete(key);
        }
    });
});

const triggerChartUpdate = (targetId: number) => {
    updateCharts.value.set(targetId, true);
};
</script>

<template>
    <Head title="Übersicht" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-6 lg:p-8">
            <Card>
                <CardHeader>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <CardTitle class="text-xl sm:text-2xl">Fortschrittsübersicht</CardTitle>
                        <Select v-model="selectedTargetIds" multiple>
                            <SelectTrigger class="w-[150px]">
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
            </Card>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <Card v-for="targetId in selectedTargetIds" :key="targetId">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger asChild>
                                        <CardTitle>{{ targets.find((t) => t.id === targetId)?.title }}</CardTitle>
                                    </TooltipTrigger>
                                    <TooltipContent>
                                        <p>{{ targets.find((t) => t.id === targetId)?.description }}</p>
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                            <Button @click="toggleTracker(targetId)">
                                {{ visibleTrackers.has(targetId) ? 'Tracker ausblenden' : 'Tracker anzeigen' }}
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <ProgressChart
                            :model-value="updateCharts.get(targetId) as boolean"
                            @update:model-value="(value) => updateCharts.set(targetId, value)"
                            :target-id="targetId"
                        />
                        <ProgressTracker
                            v-if="visibleTrackers.has(targetId)"
                            :target-id="targetId"
                            @progress-tracked="triggerChartUpdate(targetId)"
                        />
                    </CardContent>
                </Card>
            </div>

            <Card v-if="!targets.length">
                <CardContent class="py-6 text-center">
                    Keine Ziele gefunden. Erstellen Sie Ihr erstes Ziel, um den Fortschritt zu verfolgen.
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
