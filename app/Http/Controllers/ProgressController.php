<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function store(Request $request, Target $target)
    {
        $validated = $request->validate([
            'value' => 'required|numeric|min:0',
            'tracked_at' => 'required|date',
        ]);

        $target->progress()->create($validated);

        return back()->with('success', 'Progress tracked successfully.');
    }

    public function getChartData(Target $target)
    {
        $progress = $target->progress()
            ->orderBy('tracked_at')
            ->get()
            ->map(fn ($p) => [
                'x' => $p->tracked_at,
                'y' => $p->value,
            ]);

        return response()->json([
            'progress' => $progress,
            'target' => [
                'value' => $target->target_value,
                'type' => $target->target_type,
            ],
        ]);
    }
}
