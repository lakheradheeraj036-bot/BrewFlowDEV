<x-layouts.super-admin title="Platform Settings">
<div>

    {{-- Page Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h2 style="font-size:18px; font-weight:700; color:#1e293b; margin:0;">System Settings</h2>
            <p style="font-size:13px; color:#64748b; margin-top:2px;">Manage BrewFlow's global brand identity, UI theme, and operational configurations.</p>
        </div>
        <div style="display:flex; gap:8px;">
            <button style="padding:8px 16px; font-size:13px; font-weight:500; border:1px solid #cbd5e1; color:#374151; background:#fff; border-radius:10px; cursor:pointer;">Discard Changes</button>
            <button style="padding:8px 16px; font-size:13px; font-weight:500; background:#0f172a; color:#fff; border:none; border-radius:10px; cursor:pointer;">Save All Settings</button>
        </div>
    </div>

    {{-- Two-column layout --}}
    <div style="display:flex; gap:20px; align-items:flex-start;">

        {{-- ── Left Nav ── --}}
        <div style="width:220px; flex-shrink:0;">

            <nav style="display:flex; flex-direction:column; gap:4px; margin-bottom:16px;">

                <button style="display:flex; align-items:center; justify-content:space-between; width:100%; padding:10px 12px; background:#fff; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 1px 2px rgba(0,0,0,.04); cursor:pointer;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span class="material-symbols-outlined" style="font-size:18px; color:#0f172a; font-variation-settings:'FILL' 1;">brush</span>
                        <span style="font-size:12px; font-weight:600; color:#0f172a; text-transform:uppercase; letter-spacing:.05em;">Branding</span>
                    </div>
                    <span class="material-symbols-outlined" style="font-size:16px; color:#94a3b8;">chevron_right</span>
                </button>

                <button style="display:flex; align-items:center; gap:10px; width:100%; padding:10px 12px; border-radius:12px; cursor:pointer; color:#64748b; border:none; background:transparent;">
                    <span class="material-symbols-outlined" style="font-size:18px;">palette</span>
                    <span style="font-size:13px;">Interface Theme</span>
                </button>

                <button style="display:flex; align-items:center; gap:10px; width:100%; padding:10px 12px; border-radius:12px; cursor:pointer; color:#64748b; border:none; background:transparent;">
                    <span class="material-symbols-outlined" style="font-size:18px;">dns</span>
                    <span style="font-size:13px;">System Config</span>
                </button>

                <button style="display:flex; align-items:center; gap:10px; width:100%; padding:10px 12px; border-radius:12px; cursor:pointer; color:#64748b; border:none; background:transparent;">
                    <span class="material-symbols-outlined" style="font-size:18px;">notifications_active</span>
                    <span style="font-size:13px;">Notifications</span>
                </button>

            </nav>

            {{-- Help Center --}}
            <div style="padding:16px; background:#f8fafc; border-radius:14px; border:1px solid #e2e8f0; word-break:break-word;">
                <p style="font-size:13px; font-weight:600; color:#1e293b; margin-bottom:6px;">Help Center</p>
                <p style="font-size:12px; color:#64748b; line-height:1.6; margin-bottom:12px;">Need help configuring your platform? Our technical docs cover everything.</p>
                <a href="#" style="font-size:12px; font-weight:600; color:#d97706; text-decoration:underline;">View Documentation</a>
            </div>

        </div>

        {{-- ── Right Panels ── --}}
        <div style="flex:1; min-width:0; display:flex; flex-direction:column; gap:16px;">

            {{-- Platform Branding Card --}}
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">
                <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:1px solid #f1f5f9;">
                    <div>
                        <p style="font-size:13px; font-weight:600; color:#1e293b;">Platform Branding</p>
                        <p style="font-size:12px; color:#94a3b8; margin-top:2px;">Configure your brand name and logo for the enterprise shell.</p>
                    </div>
                    <span class="material-symbols-outlined" style="font-size:18px; color:#cbd5e1;">info</span>
                </div>
                <div style="padding:20px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                        {{-- Fields --}}
                        <div style="display:flex; flex-direction:column; gap:14px;">
                            <div>
                                <label style="display:block; font-size:11px; font-weight:500; color:#64748b; margin-bottom:5px;">Platform Name</label>
                                <input type="text" value="BrewFlow" style="width:100%; padding:8px 12px; font-size:13px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#1e293b; outline:none; box-sizing:border-box;"/>
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight:500; color:#64748b; margin-bottom:5px;">Admin Domain</label>
                                <div style="display:flex;">
                                    <span style="padding:8px 12px; font-size:13px; background:#f8fafc; border:1px solid #cbd5e1; border-right:none; border-radius:10px 0 0 10px; color:#94a3b8;">https://</span>
                                    <input type="text" value="admin.brewflow.com" style="flex:1; padding:8px 12px; font-size:13px; border:1px solid #cbd5e1; border-radius:0 10px 10px 0; background:#fff; color:#1e293b; outline:none; min-width:0;"/>
                                </div>
                            </div>
                            <div>
                                <label style="display:block; font-size:11px; font-weight:500; color:#64748b; margin-bottom:5px;">Platform Email</label>
                                <input type="email" value="admin@brewflow.com" style="width:100%; padding:8px 12px; font-size:13px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#1e293b; outline:none; box-sizing:border-box;"/>
                            </div>
                        </div>

                        {{-- Logo Upload --}}
                        <div>
                            <label style="display:block; font-size:11px; font-weight:500; color:#64748b; margin-bottom:5px;">Brand Logo</label>
                            <div style="border:2px dashed #e2e8f0; border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center; background:#f8fafc; cursor:pointer; height: 184px; gap:6px;">
                                <svg width="24" height="24" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p style="font-size:12px; font-weight:500; color:#475569;">Click to upload</p>
                                <p style="font-size:11px; color:#94a3b8;">SVG, PNG or JPG · Max 5MB</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Interface Theme Card --}}
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">
                <div style="padding:14px 20px; border-bottom:1px solid #f1f5f9;">
                    <p style="font-size:13px; font-weight:600; color:#1e293b;">Interface Theme</p>
                </div>
                <div style="padding:20px;">

                    {{-- Theme options --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:16px;">
                        <div style="padding:12px; border:2px solid #0f172a; border-radius:12px; cursor:pointer; position:relative; background:#fff;">
                            <div style="width:100%; height:32px; background:#f1f5f9; border-radius:6px; margin-bottom:8px;"></div>
                            <p style="font-size:11px; font-weight:600; color:#1e293b;">Light Mode</p>
                            <span class="material-symbols-outlined" style="position:absolute; top:8px; right:8px; font-size:16px; color:#0f172a; font-variation-settings:'FILL' 1;">check_circle</span>
                        </div>
                        <div style="padding:12px; border:2px solid #e2e8f0; border-radius:12px; cursor:pointer; background:#1e293b;">
                            <div style="width:100%; height:32px; background:#475569; border-radius:6px; margin-bottom:8px;"></div>
                            <p style="font-size:11px; font-weight:600; color:#94a3b8;">Dark Mode</p>
                        </div>
                        <div style="padding:12px; border:2px solid #e2e8f0; border-radius:12px; cursor:pointer; background:#fff;">
                            <div style="display:flex; gap:4px; height:32px; margin-bottom:8px;">
                                <div style="flex:1; background:#f1f5f9; border-radius:6px;"></div>
                                <div style="flex:1; background:#334155; border-radius:6px;"></div>
                            </div>
                            <p style="font-size:11px; font-weight:600; color:#475569;">System Sync</p>
                        </div>
                    </div>

                    {{-- Brand color --}}
                    <div>
                        <label style="display:block; font-size:11px; font-weight:500; color:#64748b; margin-bottom:6px;">Primary Brand Color</label>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="display:flex; align-items:center; gap:8px; background:#f8fafc; border:1px solid #e2e8f0; padding:6px 10px; border-radius:8px; flex:1;">
                                <div style="width:22px; height:22px; background:#0f172a; border-radius:5px; flex-shrink:0;"></div>
                                <input type="text" value="#0F0F0F" style="background:transparent; border:none; font-size:12px; color:#1e293b; width:100%; outline:none;"/>
                            </div>
                            <div style="display:flex; gap:6px;">
                                <div style="width:26px; height:26px; border-radius:50%; background:#0f172a; cursor:pointer; outline:2px solid #0f172a; outline-offset:2px;"></div>
                                <div style="width:26px; height:26px; border-radius:50%; background:#1d4ed8; cursor:pointer;"></div>
                                <div style="width:26px; height:26px; border-radius:50%; background:#059669; cursor:pointer;"></div>
                                <div style="width:26px; height:26px; border-radius:50%; background:#dc2626; cursor:pointer;"></div>
                                <div style="width:26px; height:26px; border-radius:50%; background:#d97706; cursor:pointer;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Notifications + System Config --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

                {{-- Notifications --}}
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">
                    <div style="display:flex; align-items:center; gap:8px; padding:14px 20px; border-bottom:1px solid #f1f5f9;">
                        <span class="material-symbols-outlined" style="font-size:16px; color:#94a3b8;">notifications</span>
                        <p style="font-size:13px; font-weight:600; color:#1e293b;">Notifications</p>
                    </div>
                    <div style="padding:16px; display:flex; flex-direction:column; gap:8px;">
                        @foreach([
                            ['label' => 'Email Alerts',   'desc' => 'Critical system notifications', 'on' => true],
                            ['label' => 'Slack Webhooks', 'desc' => 'Operational updates',           'on' => false],
                            ['label' => 'SMS Gateway',    'desc' => 'High-priority outages',         'on' => true],
                        ] as $item)
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:#f8fafc; border-radius:10px;">
                            <div>
                                <p style="font-size:12px; font-weight:600; color:#1e293b;">{{ $item['label'] }}</p>
                                <p style="font-size:11px; color:#94a3b8; margin-top:1px;">{{ $item['desc'] }}</p>
                            </div>
                            <div style="width:38px; height:20px; background:{{ $item['on'] ? '#0f172a' : '#e2e8f0' }}; border-radius:999px; position:relative; flex-shrink:0; cursor:pointer;">
                                <div style="width:14px; height:14px; background:#fff; border-radius:50%; position:absolute; top:3px; {{ $item['on'] ? 'right:3px' : 'left:3px' }};"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- System Config --}}
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">
                    <div style="display:flex; align-items:center; gap:8px; padding:14px 20px; border-bottom:1px solid #f1f5f9;">
                        <span class="material-symbols-outlined" style="font-size:16px; color:#94a3b8;">dns</span>
                        <p style="font-size:13px; font-weight:600; color:#1e293b;">System Config</p>
                    </div>
                    <div style="padding:16px; display:flex; flex-direction:column; gap:12px;">
                        <div>
                            <label style="display:block; font-size:11px; font-weight:500; color:#64748b; margin-bottom:5px;">Server Region</label>
                            <select style="width:100%; padding:8px 12px; font-size:13px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#374151; outline:none;">
                                <option>US East (N. Virginia)</option>
                                <option>EU West (Ireland)</option>
                                <option>Asia Pacific (Singapore)</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:11px; font-weight:500; color:#64748b; margin-bottom:5px;">Default Currency</label>
                            <select style="width:100%; padding:8px 12px; font-size:13px; border:1px solid #cbd5e1; border-radius:10px; background:#fff; color:#374151; outline:none;">
                                <option>USD - United States Dollar</option>
                                <option>EUR - Euro</option>
                                <option>GBP - British Pound</option>
                            </select>
                        </div>
                        <button style="width:100%; padding:8px; font-size:12px; font-weight:600; color:#dc2626; border:1px solid #fecaca; border-radius:10px; background:#fff; cursor:pointer;">
                            Clear System Cache
                        </button>
                    </div>
                </div>

            </div>

            {{-- Hero Banner --}}
             {{--<div style="position:relative; border-radius:16px; overflow:hidden; background:#0f172a; padding:28px 32px; height:150px; display:flex; flex-direction:column; justify-content:flex-end;">
                <div style="position:absolute; inset:0; background:linear-gradient(135deg, #334155, #020617); opacity:.9;"></div>
                <div style="position:relative; z-index:1;">
                    <h4 style="font-size:20px; font-weight:700; color:#fff; margin-bottom:4px;">Platform Integrity</h4>
                    <p style="font-size:12px; color:#94a3b8; max-width:420px;">BrewFlow is built on a global infrastructure ensuring 99.9% uptime for all connected venues.</p>
                </div>
            </div>--}}

        </div>{{-- /right panels --}}

    </div>

</div>
</x-layouts.super-admin>
