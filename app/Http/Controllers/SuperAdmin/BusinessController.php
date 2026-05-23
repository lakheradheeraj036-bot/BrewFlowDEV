<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreBusinessRequest;
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

        try {
            $businessQuery = Business::query()
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
        } catch (\Exception $e) {
            return redirect()
                ->back();
        }
    }

    public function create()
    {
        return view('super-admin.businesses.create');
    }

    public function store(StoreBusinessRequest $request)
    {
        try {
            $validated = $request->validated();

            Business::create([
                'name'              => strip_tags(trim($validated['name'])),
                'type'              => $validated['type'],
                'email'             => filled($validated['email']) ? strtolower(trim($validated['email'])) : null,
                'phone'             => filled($validated['phone']) ? trim($validated['phone']) : null,
                'address'           => filled($validated['address']) ? strip_tags(trim($validated['address'])) : null,
                'city'              => filled($validated['city']) ? strip_tags(trim($validated['city'])) : null,
                'country'           => filled($validated['country']) ? strip_tags(trim($validated['country'])) : null,
                'subscription_plan' => $validated['subscription_plan'],
                'status'            => $validated['status'],
            ]);

            return redirect()
                ->route('super-admin.businesses.index');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput();
        }
    }

    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'selected_ids' => ['required', 'array'],
                'selected_ids.*' => ['integer', 'exists:businesses,id'],
                'status' => ['required', 'in:active,inactive,pending,suspended'],
            ]);

            if (empty($validated['selected_ids'])) {
                return response()->json([
                    'success' => false,
                ], 400);
            }

            $count = Business::whereIn('id', $validated['selected_ids'])
                ->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
            ], 500);
        }
    }

    public function edit(Business $business)
    {
        try {
            return view('super-admin.businesses.edit', [
                'business' => $business,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('super-admin.businesses.index');
        } catch (\Exception $e) {
            return redirect()
                ->route('super-admin.businesses.index');
        }
    }

    public function update(UpdateBusinessRequest $request, Business $business)
    {
        try {
            $validated = $request->validated();

            $business->update([
                'name'              => strip_tags(trim($validated['name'])),
                'type'              => $validated['type'],
                'email'             => filled($validated['email']) ? strtolower(trim($validated['email'])) : null,
                'phone'             => filled($validated['phone']) ? trim($validated['phone']) : null,
                'address'           => filled($validated['address']) ? strip_tags(trim($validated['address'])) : null,
                'city'              => filled($validated['city']) ? strip_tags(trim($validated['city'])) : null,
                'country'           => filled($validated['country']) ? strip_tags(trim($validated['country'])) : null,
                'subscription_plan' => $validated['subscription_plan'],
                'status'            => $validated['status'],
            ]);

            return redirect()
                ->route('super-admin.businesses.index');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('super-admin.businesses.index');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput();
        }
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

                return response()->json([
                    'success' => true,
                    'count' => $count,
                ]);
            }

            if (!empty($validated['business_id'])) {
                Business::findOrFail($validated['business_id'])->delete();

                return response()->json([
                    'success' => true,
                    'count' => 1,
                ]);
            }

            return response()->json([
                'success' => false,
            ], 400);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
            ], 500);
        }
    }
}
