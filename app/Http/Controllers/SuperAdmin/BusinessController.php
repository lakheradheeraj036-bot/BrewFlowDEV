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
        $subscriptionPlans = \App\Models\SubscriptionPlan::active()->orderBy('sort_order')->get();
        return view('super-admin.businesses.create', compact('subscriptionPlans'));
    }

    public function store(StoreBusinessRequest $request)
    {
        try {
            $validated = $request->validated();

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('business-logos', 'public');
            }

            $business = Business::create([
                'name'                 => strip_tags(trim($validated['name'])),
                'type'                 => $validated['type'],
                'email'                => filled($validated['email']) ? strtolower(trim($validated['email'])) : null,
                'phone'                => filled($validated['phone']) ? trim($validated['phone']) : null,
                'address'              => filled($validated['address']) ? strip_tags(trim($validated['address'])) : null,
                'city'                 => filled($validated['city']) ? strip_tags(trim($validated['city'])) : null,
                'country'              => filled($validated['country']) ? strip_tags(trim($validated['country'])) : null,
                'description'          => filled($validated['description']) ? strip_tags(trim($validated['description'])) : null,
                'logo'                 => $logoPath,
                'subscription_plan'    => $validated['subscription_plan'] ?? null,
                'subscription_plan_id' => filled($validated['subscription_plan_id']) ? (int)$validated['subscription_plan_id'] : null,
                'status'               => $validated['status'],
                'owner_id'             => auth()->id(),
            ]);

            // Attach business to logged-in user
            if (auth()->check()) {
                auth()->user()->update(['business_id' => $business->id]);
            }

            return redirect()
                ->route('super-admin.businesses.index')
                ->with('success', 'Business created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create business. Please try again.');
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
            $subscriptionPlans = \App\Models\SubscriptionPlan::active()->orderBy('sort_order')->get();
            return view('super-admin.businesses.edit', [
                'business' => $business,
                'subscriptionPlans' => $subscriptionPlans,
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

            // Handle logo upload
            $logoPath = $business->logo;
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($business->logo && \Storage::disk('public')->exists($business->logo)) {
                    \Storage::disk('public')->delete($business->logo);
                }
                $logoPath = $request->file('logo')->store('business-logos', 'public');
            }

            $business->update([
                'name'                 => strip_tags(trim($validated['name'])),
                'type'                 => $validated['type'],
                'email'                => filled($validated['email']) ? strtolower(trim($validated['email'])) : null,
                'phone'                => filled($validated['phone']) ? trim($validated['phone']) : null,
                'address'              => filled($validated['address']) ? strip_tags(trim($validated['address'])) : null,
                'city'                 => filled($validated['city']) ? strip_tags(trim($validated['city'])) : null,
                'country'              => filled($validated['country']) ? strip_tags(trim($validated['country'])) : null,
                'description'          => filled($validated['description']) ? strip_tags(trim($validated['description'])) : null,
                'logo'                 => $logoPath,
                'subscription_plan'    => $validated['subscription_plan'] ?? null,
                'subscription_plan_id' => filled($validated['subscription_plan_id']) ? (int)$validated['subscription_plan_id'] : null,
                'status'               => $validated['status'],
            ]);

            return redirect()
                ->route('super-admin.businesses.index')
                ->with('success', 'Business updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('super-admin.businesses.index');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update business. Please try again.');
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
