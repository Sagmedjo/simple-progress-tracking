<?php

namespace App\Http\Controllers;

use App\Enums\TargetType;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class TargetController extends Controller
{
    public function index()
    {
        $targets = Auth::user()->targets()
            ->with('latestProgress')
            ->latest()
            ->get();

        return Inertia::render('Targets/Index', [
            'targets' => $targets,
        ]);
    }

    public function create()
    {
        return Inertia::render('Targets/Form', [
            'targetTypes' => collect(TargetType::cases())->map(fn ($type) => [
                'value' => $type->value,
                'label' => str($type->value)->title(),
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|numeric|min:0',
            'target_type' => 'required|string|in:'.implode(',', array_column(TargetType::cases(), 'value')),
        ]);

        Auth::user()->targets()->create($validated);

        return redirect()->route('targets.index')->with('success', 'Target created successfully.');
    }

    public function edit(Target $target)
    {
        Gate::authorize('update', $target);

        return Inertia::render('Targets/Form', [
            'target' => $target,
            'targetTypes' => collect(TargetType::cases())->map(fn ($type) => [
                'value' => $type->value,
                'label' => str($type->value)->title(),
            ]),
        ]);
    }

    public function update(Request $request, Target $target)
    {
        Gate::authorize('update', $target);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|numeric|min:0',
            'target_type' => 'required|string|in:'.implode(',', array_column(TargetType::cases(), 'value')),
        ]);

        $target->update($validated);

        return redirect()->route('targets.index')->with('success', 'Target updated successfully.');
    }

    public function destroy(Target $target)
    {
        Gate::authorize('delete', $target);
        $target->delete();

        return redirect()->route('targets.index')->with('success', 'Target deleted successfully.');
    }
}
