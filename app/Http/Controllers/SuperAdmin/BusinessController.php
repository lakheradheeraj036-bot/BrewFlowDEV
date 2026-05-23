<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\UpdateBusinessRequest;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q', '');
        $status = $request->query('status', '');
        $type = $request->query('type', '');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        $allowedSorts = ['name', 'email', 'city', 'status', 'type', 'subscription_plan', 'created_at'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortDir, ['asc', 'desc'], true)) {
            $sortDir = 'desc';
        }

        $businessQuery = Business::query()
            ->with('owner')
            ->when($search, fn($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            }))
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($type, fn($query) => $query->where('type', $type));

        $businesses = $businessQuery
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        $allBusinessIds = Business::query()
            ->when($search, fn($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            }))
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($type, fn($query) => $query->where('type', $type))
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $stats = [
            'total' => Business::count(),
            'active' => Business::where('status', 'active')->count(),
            'pending' => Business::where('status', 'pending')->count(),
        ];

        return view('super-admin.businesses.index', [
            'businesses' => $businesses,
            'stats' => $stats,
            'allBusinessIds' => $allBusinessIds,
        ]);
    }

    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array'],
            'selected_ids.*' => ['integer', 'exists:businesses,id'],
            'status' => ['required', 'in:active,inactive,pending,suspended'],
        ]);

        $count = Business::whereIn('id', $validated['selected_ids'])
            ->update(['status' => $validated['status']]);

        $message = "{$count} " . str('business')->plural($count) . " updated to {$validated['status']}.";

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count,
        ]);
    }

    public function edit(Business $business)
    {
        return view('super-admin.businesses.edit', [
            'business' => $business,
        ]);
    }

    public function update(UpdateBusinessRequest $request, Business $business)
    {
        $validated = $request->validated();

        $business->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'subscription_plan' => $validated['subscription_plan'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('super-admin.businesses.index')
            ->with('success', 'Business updated successfully.');
    }

    public function destroy(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'business_id' => ['nullable', 'integer', 'exists:businesses,id'],
                'selected_ids' => ['nullable', 'array'],
                'selected_ids.*' => ['integer', 'exists:businesses,id'],
            ]);

            if (!empty($validated['selected_ids'])) {
                $count = Business::whereIn('id', $validated['selected_ids'])->delete();
                $message = "{$count} " . str('business')->plural($count) . " deleted successfully.";

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count' => $count,
                ]);
            }

            if (!empty($validated['business_id'])) {
                Business::findOrFail($validated['business_id'])->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Business deleted successfully.',
                    'count' => 1,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No business was selected for deletion.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting business: ' . $e->getMessage(),
            ], 500);
        }
    }
}
