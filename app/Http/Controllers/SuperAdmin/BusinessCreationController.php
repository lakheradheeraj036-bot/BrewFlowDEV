<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreBusinessRequest;
use App\Models\Business;

class BusinessCreationController extends Controller
{
    public function store(StoreBusinessRequest $request)
    {
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
            ->route('super-admin.businesses.index')
            ->with('success', 'Business created successfully.');
    }
}
