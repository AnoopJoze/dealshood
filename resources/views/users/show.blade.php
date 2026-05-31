@extends('layouts.user_type.auth')
@section('content')
@push('css')
<style>
:root { --dk:#0f172a; --dk2:#1e293b; --accent:#6366f1; --surface:#f8fafc; --border:#f1f5f9; --muted:#64748b; --muted2:#94a3b8; --r:10px; --sh:0 2px 16px rgba(15,23,42,.07); }

.ps-card { background:#fff; border:1px solid var(--border); border-radius:var(--r); box-shadow:var(--sh); margin-bottom:1rem; overflow:hidden; }
.ps-card-hd { display:flex; align-items:center; justify-content:space-between; padding:.85rem 1.2rem; border-bottom:1px solid var(--border); }
.ps-card-title { font-size:.63rem; font-weight:700; letter-spacing:.11em; text-transform:uppercase; color:var(--muted2); margin:0; display:flex; align-items:center; gap:7px; }
.ps-card-title i { color:var(--accent); }
.ps-card-body { padding:1rem 1.2rem; }

.profile-cover { height:110px; border-radius:var(--r) var(--r) 0 0; background:linear-gradient(135deg,var(--dk) 0%,#312e81 100%); }
.profile-avatar { width:80px; height:80px; font-size:2rem; font-weight:800; border:3px solid #fff; margin-top:-40px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(0,0,0,.18); }

.ps-btn { display:inline-flex; align-items:center; gap:6px; font-size:.77rem; font-weight:600; border-radius:8px; padding:.45rem .9rem; cursor:pointer; border:1.5px solid; transition:all .14s; text-decoration:none; }
.ps-btn-warn { background:linear-gradient(135deg,#d97706,#f59e0b); color:#fff; border-color:transparent; }
.ps-btn-warn:hover { filter:brightness(1.08); color:#fff; }
.ps-btn-ghost { background:#fff; color:var(--muted); border-color:var(--border); }
.ps-btn-ghost:hover { background:var(--surface); color:var(--dk); }
.ps-btn-danger { background:#fff; color:#dc2626; border-color:#fecaca; }
.ps-btn-danger:hover { background:#fef2f2; }

.section-title { font-size:.63rem; font-weight:700; text-transform:uppercase; letter-spacing:.11em; color:var(--muted2); margin-bottom:1rem; display:flex; align-items:center; gap:6px; }
.section-title::after { content:''; flex:1; height:1px; background:var(--border); }

.info-row { display:flex; align-items:flex-start; padding:.55rem 0; border-bottom:1px solid var(--border); }
.info-row:last-child { border-bottom:none; }
.info-icon { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.73rem; margin-right:10px; }
.info-label { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--muted2); margin-bottom:2px; }
.info-value { font-size:.83rem; font-weight:600; color:var(--dk); word-break:break-word; }
.info-value.empty { color:var(--muted2); font-style:italic; font-weight:400; }

.stat-pill { background:var(--surface); border:1px solid var(--border); border-radius:8px; padding:.8rem 1rem; text-align:center; }
.stat-pill .sp-val { font-size:1.2rem; font-weight:800; line-height:1; color:var(--dk); }
.stat-pill .sp-lbl { font-size:.62rem; text-transform:uppercase; letter-spacing:.07em; color:var(--muted2); margin-top:3px; }

.timeline-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; margin-top:3px; }

.map-frame { border-radius:9px; overflow:hidden; border:1px solid var(--border); height:200px; }
.map-frame iframe { width:100%; height:100%; border:none; display:block; }

/* Modal */
.ps-modal .modal-content { border:none; border-radius:14px; box-shadow:0 24px 60px rgba(15,23,42,.18); }
.ps-modal .modal-header { padding:1.2rem 1.4rem .9rem; border-bottom:1px solid var(--border); }
.ps-modal-icon-warn { width:44px; height:44px; border-radius:10px; flex-shrink:0; background:linear-gradient(135deg,#d97706,#f59e0b); color:#fff; display:flex; align-items:center; justify-content:center; font-size:1rem; border-radius:50%; }
.ps-tab-nav { display:flex; gap:2px; padding:0 1.4rem; border-bottom:1px solid var(--border); }
.ps-tab-link { font-size:.75rem; font-weight:600; padding:.6rem .85rem; border:none; background:transparent; cursor:pointer; color:var(--muted); border-bottom:2px solid transparent; margin-bottom:-1px; transition:color .14s,border-color .14s; display:inline-flex; align-items:center; gap:5px; }
.ps-tab-link.active { color:var(--dk); border-bottom-color:var(--dk); }
.ps-modal .form-label { font-size:.78rem; font-weight:600; color:var(--dk); margin-bottom:5px; }
.ps-modal .form-control, .ps-modal .form-select { font-size:.84rem; border-color:var(--border); border-radius:8px; }
.ps-modal .form-control:focus, .ps-modal .form-select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(99,102,241,.1); }
.ps-modal .modal-footer { padding:.9rem 1.4rem; border-top:1px solid var(--border); }
</style>
@endpush

{{-- Breadcrumb --}}
<div class="d-flex align-items-center gap-2 mb-4" style="font-size:.82rem;">
    <a href="{{ route('users.index') }}" style="color:var(--muted);text-decoration:none;"><i class="fas fa-users me-1"></i> Users</a>
    <i class="fas fa-chevron-right" style="font-size:.55rem;color:var(--muted2);"></i>
    <span style="font-weight:700;color:var(--dk);">{{ $user->name }}</span>
</div>

<div class="row g-4">
    {{-- LEFT --}}
    <div class="col-xl-4 col-lg-5">

        {{-- Profile card --}}
        <div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius:var(--r);">
            <div class="profile-cover"></div>
            <div class="card-body text-center pt-0 pb-4 px-4">
                <div class="profile-avatar mx-auto">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <h5 class="fw-bold mt-3 mb-1" style="font-size:1.1rem;color:var(--dk);">{{ $user->name }}</h5>
                @if ($user->company_name)
                    <p style="font-size:.78rem;color:var(--muted);margin-bottom:4px;"><i class="fas fa-building me-1"></i>{{ $user->company_name }}</p>
                @endif
                @if ($user->location)
                    <p style="font-size:.72rem;color:var(--muted2);margin-bottom:.75rem;"><i class="fas fa-map-marker-alt me-1"></i>{{ $user->location }}</p>
                @endif
                <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                    @foreach ($user->roles as $role)
                        <span class="badge rounded-pill px-3 py-2" style="background:#ede9fe;color:#7c3aed;font-size:.68rem;">
                            <i class="fas fa-shield-alt me-1" style="font-size:.6rem;"></i>{{ ucfirst($role->name) }}
                        </span>
                    @endforeach
                    <span class="badge rounded-pill px-3 py-2" style="background:{{ $user->status==='Active' ? '#d1fae5' : '#fef2f2' }};color:{{ $user->status==='Active' ? '#059669' : '#dc2626' }};font-size:.68rem;">
                        <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:currentColor;vertical-align:middle;margin-right:4px;"></span>
                        {{ $user->status }}
                    </span>
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-4"><div class="stat-pill"><div class="sp-val">{{ $user->roles->count() }}</div><div class="sp-lbl">Roles</div></div></div>
                    <div class="col-4"><div class="stat-pill"><div class="sp-val" style="{{ $user->email_verified_at ? 'color:#059669;' : 'color:#dc2626;' }}">{{ $user->email_verified_at ? '✓' : '✗' }}</div><div class="sp-lbl">Verified</div></div></div>
                    <div class="col-4"><div class="stat-pill"><div class="sp-val">#{{ $user->id }}</div><div class="sp-lbl">ID</div></div></div>
                </div>
                <div class="d-flex gap-2">
                    <button class="ps-btn ps-btn-warn flex-fill open-edit-btn" data-id="{{ $user->id }}"><i class="fas fa-pen"></i> Edit</button>
                    <button class="ps-btn ps-btn-danger flex-fill delete-btn" data-id="{{ $user->id }}" data-name="{{ $user->name }}"><i class="fas fa-trash"></i> Delete</button>
                </div>
            </div>
        </div>

        {{-- Contact --}}
        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-address-card"></i> Contact</p></div>
            <div class="ps-card-body" style="padding-top:.4rem;padding-bottom:.4rem;">
                @foreach ([
                    ['fas fa-envelope','bg-info-subtle text-info','Email',$user->email,'mailto:'.$user->email,$user->email_verified_at?'verified':'unverified',$user->email_verified_at?'success':'warning'],
                    ['fas fa-phone','bg-primary-subtle text-primary','Phone',$user->phone,'tel:'.$user->phone,null,null],
                    ['fab fa-whatsapp','bg-success-subtle text-success','WhatsApp',$user->whatsapp_number,'https://wa.me/'.preg_replace('/\D/','',$user->whatsapp_number??''),null,null],
                    ['fas fa-globe','bg-warning-subtle text-warning','Website',$user->website,$user->website,null,null],
                ] as [$icon,$cls,$lbl,$val,$href,$badge,$badgeColor])
                <div class="info-row">
                    <div class="info-icon {{ $cls }}"><i class="{{ $icon }}"></i></div>
                    <div style="min-width:0;">
                        <div class="info-label">{{ $lbl }}</div>
                        @if ($val)
                            <a href="{{ $href }}" target="_blank" class="info-value text-decoration-none text-dark text-truncate d-block">{{ $val }}</a>
                            @if ($badge)
                                <span class="badge rounded-pill px-2" style="background:var(--{{ $badgeColor }}-subtle,#f1f5f9);font-size:.6rem;">{{ $badge }}</span>
                            @endif
                        @else
                            <div class="info-value empty">Not provided</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Timeline --}}
        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-history"></i> Timeline</p></div>
            <div class="ps-card-body" style="padding-top:.5rem;padding-bottom:.5rem;">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3">
                        <div class="timeline-dot mt-1" style="background:#059669;"></div>
                        <div><div class="fw-semibold" style="font-size:.82rem;color:var(--dk);">Account Created</div>
                        <div style="font-size:.7rem;color:var(--muted2);">{{ $user->created_at->format('d M Y, h:i A') }} · {{ $user->created_at->diffForHumans() }}</div></div>
                    </div>
                    @if ($user->email_verified_at)
                    <div class="d-flex gap-3">
                        <div class="timeline-dot mt-1" style="background:var(--accent);"></div>
                        <div><div class="fw-semibold" style="font-size:.82rem;color:var(--dk);">Email Verified</div>
                        <div style="font-size:.7rem;color:var(--muted2);">{{ $user->email_verified_at->format('d M Y, h:i A') }}</div></div>
                    </div>
                    @endif
                    <div class="d-flex gap-3">
                        <div class="timeline-dot mt-1" style="background:#d97706;"></div>
                        <div><div class="fw-semibold" style="font-size:.82rem;color:var(--dk);">Last Updated</div>
                        <div style="font-size:.7rem;color:var(--muted2);">{{ $user->updated_at->format('d M Y, h:i A') }} · {{ $user->updated_at->diffForHumans() }}</div></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-xl-8 col-lg-7">

        {{-- About --}}
        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-user"></i> About</p></div>
            <div class="ps-card-body">
                @if ($user->about_me)
                    <p style="font-size:.88rem;line-height:1.8;color:var(--dk);margin:0;">{{ $user->about_me }}</p>
                @else
                    <div class="text-center py-3" style="color:var(--muted2);font-size:.82rem;">
                        <i class="fas fa-pen mb-2" style="font-size:1.5rem;opacity:.3;display:block;"></i>No bio added yet
                    </div>
                @endif
            </div>
        </div>

        {{-- Personal & Business --}}
        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-id-card"></i> Personal & Business Details</p></div>
            <div class="ps-card-body" style="padding-top:.4rem;padding-bottom:.4rem;">
                <div class="row g-0">
                    @foreach ([
                        ['fas fa-user','bg-primary-subtle text-primary','Full Name',$user->name,'pe-md-4'],
                        ['fas fa-envelope','bg-info-subtle text-info','Email',$user->email,'ps-md-4'],
                        ['fas fa-phone','bg-success-subtle text-success','Phone',$user->phone,'pe-md-4'],
                        ['fab fa-whatsapp','bg-success-subtle text-success','WhatsApp',$user->whatsapp_number,'ps-md-4'],
                        ['fas fa-building','bg-warning-subtle text-warning','Company',$user->company_name,'pe-md-4'],
                        ['fas fa-globe','bg-danger-subtle text-danger','Website',$user->website,'ps-md-4'],
                        ['fas fa-shield-alt','bg-primary-subtle text-primary','Role',ucfirst($user->roles->first()?->name??'No Role'),'pe-md-4'],
                        ['fas fa-toggle-on',($user->status==='Active'?'bg-success-subtle text-success':'bg-danger-subtle text-danger'),'Status',$user->status,'ps-md-4'],
                    ] as [$icon,$cls,$lbl,$val,$pad])
                    <div class="col-md-6">
                        <div class="info-row {{ $pad }}">
                            <div class="info-icon {{ $cls }}"><i class="{{ $icon }}"></i></div>
                            <div><div class="info-label">{{ $lbl }}</div>
                            <div class="info-value {{ !$val ? 'empty' : '' }}">{{ $val ?: 'Not provided' }}</div></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Address & Location --}}
        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-map-marker-alt"></i> Address & Location</p></div>
            <div class="ps-card-body" style="padding-top:.4rem;">
                <div class="row g-0 mb-3">
                    @foreach ([
                        ['fas fa-city','bg-warning-subtle text-warning','City / Region',$user->location,'pe-md-4'],
                        ['fas fa-map-pin','bg-danger-subtle text-danger','Full Address',$user->address,'ps-md-4'],
                        ['fas fa-crosshairs','bg-info-subtle text-info','Latitude',$user->latitude,'pe-md-4'],
                        ['fas fa-location-arrow','bg-info-subtle text-info','Longitude',$user->longitude,'ps-md-4'],
                    ] as [$icon,$cls,$lbl,$val,$pad])
                    <div class="col-md-6">
                        <div class="info-row {{ $pad }}">
                            <div class="info-icon {{ $cls }}"><i class="{{ $icon }}"></i></div>
                            <div><div class="info-label">{{ $lbl }}</div>
                            <div class="info-value {{ !$val ? 'empty' : '' }}">{{ $val ?: 'Not provided' }}</div></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if ($user->latitude && $user->longitude)
                    <div class="d-flex gap-2 flex-wrap mb-3">
                        <span class="badge rounded-pill px-3 py-2" style="background:var(--surface);color:var(--muted);border:1px solid var(--border);font-size:.72rem;"><span style="color:var(--muted2);margin-right:4px;">LAT</span>{{ $user->latitude }}</span>
                        <span class="badge rounded-pill px-3 py-2" style="background:var(--surface);color:var(--muted);border:1px solid var(--border);font-size:.72rem;"><span style="color:var(--muted2);margin-right:4px;">LNG</span>{{ $user->longitude }}</span>
                        <a href="https://www.google.com/maps?q={{ $user->latitude }},{{ $user->longitude }}" target="_blank" class="ps-btn ps-btn-ghost ms-auto" style="font-size:.72rem;padding:.35rem .7rem;"><i class="fas fa-map"></i> Open in Maps</a>
                    </div>
                    <div class="map-frame">
                        <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $user->longitude - 0.01 }},{{ $user->latitude - 0.01 }},{{ $user->longitude + 0.01 }},{{ $user->latitude + 0.01 }}&layer=mapnik&marker={{ $user->latitude }},{{ $user->longitude }}" loading="lazy"></iframe>
                    </div>
                @else
                    <div class="text-center py-3 rounded-3" style="background:var(--surface);">
                        <i class="fas fa-map" style="font-size:1.5rem;opacity:.3;color:var(--muted2);display:block;margin-bottom:4px;"></i>
                        <p style="font-size:.82rem;color:var(--muted2);margin:0;">No GPS coordinates saved</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Roles & Permissions --}}
        <div class="ps-card">
            <div class="ps-card-hd"><p class="ps-card-title"><i class="fas fa-key"></i> Roles & Permissions</p></div>
            <div class="ps-card-body">
                @forelse ($user->roles as $role)
                    <div class="mb-3">
                        <span class="badge rounded-pill px-3 py-2 mb-2" style="background:#ede9fe;color:#7c3aed;font-size:.72rem;">
                            <i class="fas fa-shield-alt me-1" style="font-size:.62rem;"></i>{{ ucfirst($role->name) }}
                        </span>
                        @if ($role->permissions->count())
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($role->permissions as $perm)
                                    <span class="badge rounded-3 px-2 py-1" style="background:var(--surface);color:var(--muted);border:1px solid var(--border);font-size:.68rem;">{{ $perm->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size:.75rem;color:var(--muted2);">No permissions assigned to this role</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-3" style="color:var(--muted2);font-size:.82rem;">
                        <i class="fas fa-shield-alt" style="font-size:1.5rem;opacity:.3;display:block;margin-bottom:4px;"></i>No roles assigned
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Footer actions --}}
        <div class="d-flex gap-2">
            <a href="{{ route('users.index') }}" class="ps-btn ps-btn-ghost"><i class="fas fa-arrow-left"></i> Back to List</a>
            <button class="ps-btn ps-btn-warn open-edit-btn" data-id="{{ $user->id }}"><i class="fas fa-pen"></i> Edit User</button>
            <button class="ps-btn ps-btn-danger ms-auto delete-btn" data-id="{{ $user->id }}" data-name="{{ $user->name }}"><i class="fas fa-trash"></i> Delete</button>
        </div>

    </div>
</div>

{{-- Edit modal --}}
<div class="modal fade ps-modal" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="ps-modal-icon-warn">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" style="font-size:.98rem;color:var(--dk);">Edit User</h5>
                        <p class="mb-0 mt-1" style="font-size:.72rem;color:var(--muted2);">Leave password blank to keep current</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="ps-tab-nav" id="editModalTabs">
                <button class="ps-tab-link active" data-tab="tab-account"><i class="fas fa-user"></i> Account</button>
                <button class="ps-tab-link" data-tab="tab-business"><i class="fas fa-building"></i> Business</button>
                <button class="ps-tab-link" data-tab="tab-address"><i class="fas fa-map-marker-alt"></i> Address</button>
            </div>
            <div class="modal-body px-4 py-3">
                <input type="hidden" id="user_id">
                <div class="modal-tab-pane" id="tab-account">
                    <p style="font-size:.62rem;font-weight:700;letter-spacing:.11em;text-transform:uppercase;color:var(--muted2);margin:0 0 .75rem;">Account Information</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label">Full Name <span class="text-danger">*</span></label><input type="text" id="user_name" class="form-control"><small class="text-danger d-none" id="err_name"></small></div>
                        <div class="col-md-6"><label class="form-label">Email <span class="text-danger">*</span></label><input type="email" id="user_email" class="form-control"><small class="text-danger d-none" id="err_email"></small></div>
                        <div class="col-md-6"><label class="form-label">New Password <span style="color:var(--muted2);font-weight:400;">(blank = keep)</span></label>
                            <div class="input-group"><input type="password" id="user_password" class="form-control" placeholder="Min. 8 characters">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="user_password"><i class="fas fa-eye"></i></button></div>
                            <small class="text-danger d-none" id="err_password"></small></div>
                        <div class="col-md-6"><label class="form-label">Confirm Password</label>
                            <div class="input-group"><input type="password" id="user_password_confirmation" class="form-control">
                            <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="user_password_confirmation"><i class="fas fa-eye"></i></button></div></div>
                        <div class="col-md-6"><label class="form-label">Role <span class="text-danger">*</span></label>
                            <select id="user_role" class="form-select"><option value="">-- Select Role --</option>
                            @foreach ($roles as $role)<option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>@endforeach</select>
                            <small class="text-danger d-none" id="err_role"></small></div>
                        <div class="col-md-6"><label class="form-label">Status</label>
                            <select id="user_status" class="form-select"><option value="Active">Active</option><option value="Inactive">Inactive</option></select></div>
                        <div class="col-md-6"><label class="form-label">Phone</label>
                            <div class="input-group"><span class="input-group-text bg-light border-end-0"><i class="fas fa-phone" style="font-size:.72rem;color:var(--muted2);"></i></span>
                            <input type="text" id="user_phone" class="form-control border-start-0" placeholder="+971 xx xxx xxxx"></div></div>
                        <div class="col-md-6"><label class="form-label">WhatsApp</label>
                            <div class="input-group"><span class="input-group-text bg-light border-end-0"><i class="fab fa-whatsapp text-success" style="font-size:.72rem;"></i></span>
                            <input type="text" id="user_whatsapp_number" class="form-control border-start-0" placeholder="+971 xx xxx xxxx"></div></div>
                    </div>
                    <hr style="border-color:var(--border);"><p style="font-size:.62rem;font-weight:700;letter-spacing:.11em;text-transform:uppercase;color:var(--muted2);margin-bottom:.6rem;">About</p>
                    <textarea id="user_about_me" class="form-control" rows="3" placeholder="Short bio…"></textarea>
                </div>
                <div class="modal-tab-pane d-none" id="tab-business">
                    <p style="font-size:.62rem;font-weight:700;letter-spacing:.11em;text-transform:uppercase;color:var(--muted2);margin:0 0 .75rem;">Business Information</p>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Company Name</label>
                            <div class="input-group"><span class="input-group-text bg-light border-end-0"><i class="fas fa-building" style="font-size:.72rem;color:var(--muted2);"></i></span>
                            <input type="text" id="user_company_name" class="form-control border-start-0" placeholder="Company name"></div></div>
                        <div class="col-md-6"><label class="form-label">Website</label>
                            <div class="input-group"><span class="input-group-text bg-light border-end-0"><i class="fas fa-globe" style="font-size:.72rem;color:var(--muted2);"></i></span>
                            <input type="url" id="user_website" class="form-control border-start-0" placeholder="https://example.com"></div></div>
                    </div>
                </div>
                <div class="modal-tab-pane d-none" id="tab-address">
                    <p style="font-size:.62rem;font-weight:700;letter-spacing:.11em;text-transform:uppercase;color:var(--muted2);margin:0 0 .75rem;">Address</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label class="form-label">City / Region</label>
                            <div class="input-group"><span class="input-group-text bg-light border-end-0"><i class="fas fa-city" style="font-size:.72rem;color:var(--muted2);"></i></span>
                            <input type="text" id="user_location" class="form-control border-start-0"></div></div>
                        <div class="col-12"><label class="form-label">Full Address</label><textarea id="user_address" class="form-control" rows="2"></textarea></div>
                    </div>
                    <hr style="border-color:var(--border);">
                    <p style="font-size:.62rem;font-weight:700;letter-spacing:.11em;text-transform:uppercase;color:var(--muted2);margin-bottom:.6rem;">GPS <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--muted);">(optional)</span></p>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Latitude</label>
                            <div class="input-group"><span class="input-group-text bg-light border-end-0 text-xs" style="color:var(--muted2);">LAT</span>
                            <input type="number" id="user_latitude" class="form-control border-start-0" placeholder="25.2048" step="any"></div></div>
                        <div class="col-md-6"><label class="form-label">Longitude</label>
                            <div class="input-group"><span class="input-group-text bg-light border-end-0 text-xs" style="color:var(--muted2);">LNG</span>
                            <input type="number" id="user_longitude" class="form-control border-start-0" placeholder="55.2708" step="any"></div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="ps-btn ps-btn-ghost" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                <button class="ps-btn ps-btn-warn" id="saveUserBtn">
                    <span id="saveBtnText"><i class="fas fa-save"></i> Save Changes</span>
                    <span id="saveBtnSpinner" class="d-none"><span class="spinner-border spinner-border-sm"></span> Saving…</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script>
$(function () {
    var userId = {{ $user->id }};

    $(document).on('click','#editModalTabs .ps-tab-link',function(){
        var target=$(this).data('tab'); $('#editModalTabs .ps-tab-link').removeClass('active'); $(this).addClass('active');
        $('.modal-tab-pane').addClass('d-none'); $('#'+target).removeClass('d-none');
    });

    var fieldTabMap={ name:'tab-account',email:'tab-account',password:'tab-account',role:'tab-account',status:'tab-account',phone:'tab-account',whatsapp_number:'tab-account',about_me:'tab-account',company_name:'tab-business',website:'tab-business',location:'tab-address',address:'tab-address',latitude:'tab-address',longitude:'tab-address' };

    function clearErrors(){ $('.text-danger[id^="err_"]').addClass('d-none').text(''); $('.form-control,.form-select').removeClass('is-invalid'); $('#editModalTabs .tab-err-dot').remove(); }
    function showErrors(errors){
        var firstTab=null,tabsWithErrors={};
        $.each(errors,function(field,messages){ $('#err_'+field).removeClass('d-none').text(messages[0]); $('#user_'+field).addClass('is-invalid');
            var tab=fieldTabMap[field]||'tab-account'; tabsWithErrors[tab]=true; if(!firstTab) firstTab=tab; });
        $.each(tabsWithErrors,function(tab){ var link=$('#editModalTabs .ps-tab-link[data-tab="'+tab+'"]'); if(!link.find('.tab-err-dot').length) link.append('<span class="tab-err-dot ms-1" style="width:6px;height:6px;border-radius:50%;background:#ef4444;display:inline-block;"></span>'); });
        if(firstTab) $('#editModalTabs .ps-tab-link[data-tab="'+firstTab+'"]').trigger('click');
    }

    $(document).on('click','.open-edit-btn',function(){
        clearErrors(); $('#user_password,#user_password_confirmation').val('');
        $.get('/admin/users/'+userId+'/edit-data',function(u){
            $('#user_id').val(u.id); $('#user_name').val(u.name); $('#user_email').val(u.email);
            $('#user_phone').val(u.phone??''); $('#user_whatsapp_number').val(u.whatsapp_number??'');
            $('#user_about_me').val(u.about_me??''); $('#user_status').val(u.status); $('#user_role').val(u.role);
            $('#user_company_name').val(u.company_name??''); $('#user_website').val(u.website??'');
            $('#user_location').val(u.location??''); $('#user_address').val(u.address??'');
            $('#user_latitude').val(u.latitude??''); $('#user_longitude').val(u.longitude??'');
            $('#editModalTabs .ps-tab-link').first().trigger('click'); $('#userModal').modal('show');
        }).fail(()=>Swal.fire('Error','Could not load user data.','error'));
    });

    $('#saveUserBtn').on('click',function(){
        clearErrors(); $('#saveBtnText').addClass('d-none'); $('#saveBtnSpinner').removeClass('d-none'); $('#saveUserBtn').prop('disabled',true);
        $.ajax({ url:'/admin/users/'+userId+'/ajax-update', type:'POST',
            data:{_token:'{{ csrf_token() }}',name:$('#user_name').val(),email:$('#user_email').val(),password:$('#user_password').val(),password_confirmation:$('#user_password_confirmation').val(),role:$('#user_role').val(),status:$('#user_status').val(),phone:$('#user_phone').val(),whatsapp_number:$('#user_whatsapp_number').val(),about_me:$('#user_about_me').val(),company_name:$('#user_company_name').val(),website:$('#user_website').val(),location:$('#user_location').val(),address:$('#user_address').val(),latitude:$('#user_latitude').val(),longitude:$('#user_longitude').val()},
            success:function(res){ if(res.success){ $('#userModal').modal('hide'); Swal.fire({icon:'success',title:'Saved!',text:res.message,timer:1600,showConfirmButton:false}).then(()=>location.reload()); }},
            error:function(xhr){ if(xhr.status===422) showErrors(xhr.responseJSON.errors); else Swal.fire('Error','Something went wrong.','error'); },
            complete:function(){ $('#saveBtnText').removeClass('d-none'); $('#saveBtnSpinner').addClass('d-none'); $('#saveUserBtn').prop('disabled',false); }
        });
    });

    $(document).on('click','.delete-btn',function(){
        var name=$(this).data('name');
        Swal.fire({title:'Delete User?',html:'You are about to delete <strong>'+name+'</strong>.',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonColor:'#64748b',confirmButtonText:'Yes, delete'})
        .then(r=>{ if(!r.isConfirmed) return;
            $.ajax({url:'/admin/users/'+userId,type:'POST',data:{_token:'{{ csrf_token() }}',_method:'DELETE'},
                success:function(res){ if(res.success){ Swal.fire({icon:'success',title:'Deleted!',text:res.message,timer:1500,showConfirmButton:false}).then(()=>window.location.href='{{ route("users.index") }}'); }}});
        });
    });

    $(document).on('click','.toggle-pw',function(){
        var target=$('#'+$(this).data('target')); target.attr('type',target.attr('type')==='password'?'text':'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
});
</script>
@endpush