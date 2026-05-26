@extends('layouts.user_type.auth')

@section('content')

@push('css')
<style>
    /* ── Cover ─────────────────────────────────────── */
    .profile-cover {
        height: 140px;
        /* background: linear-gradient(135deg, #17ad37 0%, #98ec2d 100%); */
        position: relative;
        border-radius: 1rem 1rem 0 0;
    }

    /* ── Avatar ─────────────────────────────────────── */
    .profile-avatar {
        width: 90px; height: 90px;
        font-size: 2.2rem;
        border: 4px solid #fff;
        margin-top: -45px;
        box-shadow: 0 4px 20px rgba(0,0,0,.15);
    }

    /* ── Info row (label + value) ───────────────────── */
    .info-row {
        display: flex;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .info-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: .75rem;
        margin-right: 12px;
        margin-top: 2px;
    }
    .info-row .info-label {
        font-size: .68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #9e9e9e;
        margin-bottom: 2px;
    }
    .info-row .info-value {
        font-size: .85rem;
        font-weight: 600;
        color: #344767;
        word-break: break-word;
    }
    .info-row .info-value.empty {
        color: #bdbdbd;
        font-weight: 400;
        font-style: italic;
    }

    /* ── Stat pill ──────────────────────────────────── */
    .stat-pill {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 14px 18px;
        text-align: center;
    }
    .stat-pill .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #344767;
        line-height: 1;
    }
    .stat-pill .stat-label {
        font-size: .68rem;
        color: #9e9e9e;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-top: 4px;
    }

    /* ── Section title ──────────────────────────────── */
    .section-title {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9e9e9e;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f0f0f0;
    }

    /* ── Map ────────────────────────────────────────── */
    .map-frame {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e9ecef;
        height: 200px;
    }
    .map-frame iframe { width: 100%; height: 100%; border: 0; }

    /* ── Timeline ───────────────────────────────────── */
    .timeline-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 4px;
    }
</style>
@endpush

<div>

    {{-- ── Breadcrumb ── --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('users.index') }}" class="text-muted text-sm">
            <i class="fas fa-users me-1"></i> Users
        </a>
        <i class="fas fa-chevron-right text-muted" style="font-size:.6rem;"></i>
        <span class="text-sm fw-semibold text-dark">{{ $user->name }}</span>
    </div>

    <div class="row g-4">

        {{-- ════════════════════════════════════════════
             COLUMN LEFT
        ════════════════════════════════════════════ --}}
        <div class="col-xl-4 col-lg-5">

            {{-- ── Profile card ── --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

                {{-- Cover --}}
                <div class="profile-cover"></div>

                <div class="card-body text-center px-4 pt-0 pb-4">

                    {{-- Avatar --}}
                    <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center
                                justify-content-center fw-bold mx-auto profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <h5 class="fw-bold text-dark mt-3 mb-1">{{ $user->name }}</h5>

                    @if ($user->company_name)
                        <p class="text-sm text-muted mb-2">
                            <i class="fas fa-building me-1" style="font-size:.7rem;"></i>
                            {{ $user->company_name }}
                        </p>
                    @endif

                    {{-- Location --}}
                    @if ($user->location)
                        <p class="text-xs text-muted mb-3">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $user->location }}
                        </p>
                    @endif

                    {{-- Badges --}}
                    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                        @foreach ($user->roles as $role)
                            <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                                <i class="fas fa-shield-alt me-1" style="font-size:.65rem;"></i>
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach

                        @if ($user->status === 'Active')
                            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                                <span class="me-1" style="display:inline-block;width:7px;height:7px;
                                      background:#2dce89;border-radius:50%;vertical-align:middle;"></span>
                                Active
                            </span>
                        @else
                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">
                                <span class="me-1" style="display:inline-block;width:7px;height:7px;
                                      background:#f5365c;border-radius:50%;vertical-align:middle;"></span>
                                Inactive
                            </span>
                        @endif
                    </div>

                    {{-- Stats row --}}
                    <div class="row g-2 mb-4">
                        <div class="col-4">
                            <div class="stat-pill">
                                <div class="stat-value">{{ $user->roles->count() }}</div>
                                <div class="stat-label">Roles</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-pill">
                                <div class="stat-value">
                                    {{ $user->email_verified_at ? '✓' : '✗' }}
                                </div>
                                <div class="stat-label">Verified</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-pill">
                                <div class="stat-value">#{{ $user->id }}</div>
                                <div class="stat-label">ID</div>
                            </div>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="d-flex gap-2">
                        <button class="btn bg-gradient-warning flex-fill btn-sm open-edit-btn"
                                data-id="{{ $user->id }}">
                            <i class="fas fa-pen me-1"></i> Edit
                        </button>
                        <button class="btn btn-outline-danger flex-fill btn-sm delete-btn"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>

                </div>
            </div>

            {{-- ── Contact card ── --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body px-4 py-3">

                    <div class="section-title">
                        <i class="fas fa-address-card"></i> Contact
                    </div>

                    {{-- Email --}}
                    <div class="info-row">
                        <div class="info-icon bg-info-subtle text-info">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <div class="info-label">Email</div>
                            <a href="mailto:{{ $user->email }}"
                               class="info-value text-decoration-none text-dark">
                                {{ $user->email }}
                            </a>
                            @if ($user->email_verified_at)
                                <span class="badge bg-success-subtle text-success rounded-pill ms-1"
                                      style="font-size:.6rem;">
                                    <i class="fas fa-check me-1"></i>verified
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill ms-1"
                                      style="font-size:.6rem;">
                                    unverified
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="info-row">
                        <div class="info-icon bg-primary-subtle text-primary">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <div class="info-label">Phone</div>
                            @if ($user->phone)
                                <a href="tel:{{ $user->phone }}"
                                   class="info-value text-decoration-none text-dark">
                                    {{ $user->phone }}
                                </a>
                            @else
                                <div class="info-value empty">Not provided</div>
                            @endif
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="info-row">
                        <div class="info-icon bg-success-subtle text-success">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <div class="info-label">WhatsApp</div>
                            @if ($user->whatsapp_number)
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $user->whatsapp_number) }}"
                                   target="_blank"
                                   class="info-value text-decoration-none text-dark">
                                    {{ $user->whatsapp_number }}
                                </a>
                            @else
                                <div class="info-value empty">Not provided</div>
                            @endif
                        </div>
                    </div>

                    {{-- Website --}}
                    <div class="info-row">
                        <div class="info-icon bg-warning-subtle text-warning">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div style="min-width:0;">
                            <div class="info-label">Website</div>
                            @if ($user->website)
                                <a href="{{ $user->website }}" target="_blank"
                                   class="info-value text-decoration-none text-primary text-truncate d-block">
                                    <i class="fas fa-external-link-alt me-1" style="font-size:.65rem;"></i>
                                    {{ $user->website }}
                                </a>
                            @else
                                <div class="info-value empty">Not provided</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Account timeline card ── --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body px-4 py-3">

                    <div class="section-title">
                        <i class="fas fa-history"></i> Timeline
                    </div>

                    <div class="d-flex flex-column gap-3">

                        <div class="d-flex gap-3">
                            <div class="timeline-dot bg-success mt-1"></div>
                            <div>
                                <div class="text-xs fw-semibold text-dark">Account Created</div>
                                <div class="text-xs text-muted">
                                    {{ $user->created_at->format('d M Y, h:i A') }}
                                </div>
                                <div class="text-xs text-muted">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        @if ($user->email_verified_at)
                            <div class="d-flex gap-3">
                                <div class="timeline-dot bg-info mt-1"></div>
                                <div>
                                    <div class="text-xs fw-semibold text-dark">Email Verified</div>
                                    <div class="text-xs text-muted">
                                        {{ $user->email_verified_at->format('d M Y, h:i A') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex gap-3">
                            <div class="timeline-dot bg-warning mt-1"></div>
                            <div>
                                <div class="text-xs fw-semibold text-dark">Last Updated</div>
                                <div class="text-xs text-muted">
                                    {{ $user->updated_at->format('d M Y, h:i A') }}
                                </div>
                                <div class="text-xs text-muted">
                                    {{ $user->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════════════════
             COLUMN RIGHT
        ════════════════════════════════════════════ --}}
        <div class="col-xl-8 col-lg-7">

            {{-- ── About card ── --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body px-4 py-4">

                    <div class="section-title">
                        <i class="fas fa-user"></i> About
                    </div>

                    @if ($user->about_me)
                        <p class="text-sm text-dark mb-0" style="line-height:1.8;">
                            {{ $user->about_me }}
                        </p>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-pen-to-square text-muted mb-2" style="font-size:1.5rem;opacity:.3;"></i>
                            <p class="text-muted text-sm mb-0">No bio added yet</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ── Personal & Business details ── --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body px-4 py-4">

                    <div class="section-title">
                        <i class="fas fa-id-card"></i> Personal & Business Details
                    </div>

                    <div class="row g-0">

                        {{-- Full Name --}}
                        <div class="col-md-6">
                            <div class="info-row pe-md-4">
                                <div class="info-icon bg-primary-subtle text-primary">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ $user->name }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="info-row ps-md-4">
                                <div class="info-icon bg-info-subtle text-info">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <div class="info-row pe-md-4">
                                <div class="info-icon bg-success-subtle text-success">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-value {{ !$user->phone ? 'empty' : '' }}">
                                        {{ $user->phone ?: 'Not provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- WhatsApp --}}
                        <div class="col-md-6">
                            <div class="info-row ps-md-4">
                                <div class="info-icon bg-success-subtle text-success">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div>
                                    <div class="info-label">WhatsApp</div>
                                    <div class="info-value {{ !$user->whatsapp_number ? 'empty' : '' }}">
                                        {{ $user->whatsapp_number ?: 'Not provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Company Name --}}
                        <div class="col-md-6">
                            <div class="info-row pe-md-4">
                                <div class="info-icon bg-warning-subtle text-warning">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <div class="info-label">Company Name</div>
                                    <div class="info-value {{ !$user->company_name ? 'empty' : '' }}">
                                        {{ $user->company_name ?: 'Not provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Website --}}
                        <div class="col-md-6">
                            <div class="info-row ps-md-4">
                                <div class="info-icon bg-danger-subtle text-danger">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div style="min-width:0;">
                                    <div class="info-label">Website</div>
                                    @if ($user->website)
                                        <a href="{{ $user->website }}" target="_blank"
                                           class="info-value text-decoration-none text-primary text-truncate d-block">
                                            {{ $user->website }}
                                        </a>
                                    @else
                                        <div class="info-value empty">Not provided</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="col-md-6">
                            <div class="info-row pe-md-4">
                                <div class="info-icon bg-primary-subtle text-primary">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <div class="info-label">Role</div>
                                    <div class="info-value">
                                        {{ ucfirst($user->roles->first()?->name ?? 'No Role') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <div class="info-row ps-md-4">
                                <div class="info-icon {{ $user->status === 'Active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    <i class="fas fa-toggle-{{ $user->status === 'Active' ? 'on' : 'off' }}"></i>
                                </div>
                                <div>
                                    <div class="info-label">Account Status</div>
                                    <div class="info-value">
                                        @if ($user->status === 'Active')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Address & Location ── --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body px-4 py-4">

                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Address & Location
                    </div>

                    <div class="row g-0 mb-3">

                        {{-- City / Region --}}
                        <div class="col-md-6">
                            <div class="info-row pe-md-4">
                                <div class="info-icon bg-warning-subtle text-warning">
                                    <i class="fas fa-city"></i>
                                </div>
                                <div>
                                    <div class="info-label">City / Region</div>
                                    <div class="info-value {{ !$user->location ? 'empty' : '' }}">
                                        {{ $user->location ?: 'Not provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Full Address --}}
                        <div class="col-md-6">
                            <div class="info-row ps-md-4">
                                <div class="info-icon bg-danger-subtle text-danger">
                                    <i class="fas fa-map-pin"></i>
                                </div>
                                <div>
                                    <div class="info-label">Full Address</div>
                                    <div class="info-value {{ !$user->address ? 'empty' : '' }}">
                                        {{ $user->address ?: 'Not provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Latitude --}}
                        <div class="col-md-6">
                            <div class="info-row pe-md-4">
                                <div class="info-icon bg-info-subtle text-info">
                                    <i class="fas fa-crosshairs"></i>
                                </div>
                                <div>
                                    <div class="info-label">Latitude</div>
                                    <div class="info-value {{ !$user->latitude ? 'empty' : '' }}">
                                        {{ $user->latitude ?: 'Not provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Longitude --}}
                        <div class="col-md-6">
                            <div class="info-row ps-md-4">
                                <div class="info-icon bg-info-subtle text-info">
                                    <i class="fas fa-location-arrow"></i>
                                </div>
                                <div>
                                    <div class="info-label">Longitude</div>
                                    <div class="info-value {{ !$user->longitude ? 'empty' : '' }}">
                                        {{ $user->longitude ?: 'Not provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Map preview --}}
                    @if ($user->latitude && $user->longitude)
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-light text-dark rounded-3 px-3 py-2 text-xs fw-semibold">
                                <span class="text-muted me-1">LAT</span>{{ $user->latitude }}
                            </span>
                            <span class="badge bg-light text-dark rounded-3 px-3 py-2 text-xs fw-semibold">
                                <span class="text-muted me-1">LNG</span>{{ $user->longitude }}
                            </span>
                            <a href="https://www.google.com/maps?q={{ $user->latitude }},{{ $user->longitude }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary rounded-3 px-3 ms-auto">
                                <i class="fas fa-map me-1"></i> Open in Google Maps
                            </a>
                        </div>
                        <div class="map-frame">
                            <iframe
                                src="https://www.openstreetmap.org/export/embed.html?bbox={{ $user->longitude - 0.01 }},{{ $user->latitude - 0.01 }},{{ $user->longitude + 0.01 }},{{ $user->latitude + 0.01 }}&layer=mapnik&marker={{ $user->latitude }},{{ $user->longitude }}"
                                loading="lazy">
                            </iframe>
                        </div>
                    @else
                        <div class="text-center py-3 bg-light rounded-3">
                            <i class="fas fa-map text-muted mb-2" style="font-size:1.5rem;opacity:.3;"></i>
                            <p class="text-muted text-sm mb-0">No GPS coordinates saved</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ── Permissions & roles breakdown ── --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body px-4 py-4">

                    <div class="section-title">
                        <i class="fas fa-key"></i> Roles & Permissions
                    </div>

                    @if ($user->roles->count())
                        @foreach ($user->roles as $role)
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                                        <i class="fas fa-shield-alt me-1" style="font-size:.65rem;"></i>
                                        {{ ucfirst($role->name) }}
                                    </span>
                                </div>

                                @if ($role->permissions->count())
                                    <div class="d-flex flex-wrap gap-2 ms-1">
                                        @foreach ($role->permissions as $perm)
                                            <span class="badge bg-light text-secondary rounded-3 px-2 py-1"
                                                  style="font-size:.68rem;">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-muted ms-1 mb-0">No specific permissions assigned to this role</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-shield-alt text-muted mb-2" style="font-size:1.5rem;opacity:.3;"></i>
                            <p class="text-muted text-sm mb-0">No roles assigned</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ── Account metadata ── --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body px-4 py-4">

                    <div class="section-title">
                        <i class="fas fa-info-circle"></i> Account Metadata
                    </div>

                    <div class="row g-0">

                        <div class="col-md-6">
                            <div class="info-row pe-md-4">
                                <div class="info-icon bg-light text-secondary">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                                <div>
                                    <div class="info-label">User ID</div>
                                    <div class="info-value">#{{ $user->id }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-row ps-md-4">
                                <div class="info-icon bg-success-subtle text-success">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div>
                                    <div class="info-label">Created At</div>
                                    <div class="info-value">
                                        {{ $user->created_at->format('d M Y') }}
                                        <span class="text-muted fw-normal" style="font-size:.75rem;">
                                            {{ $user->created_at->format('h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-row pe-md-4">
                                <div class="info-icon bg-warning-subtle text-warning">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <div class="info-label">Last Updated</div>
                                    <div class="info-value">
                                        {{ $user->updated_at->format('d M Y') }}
                                        <span class="text-muted fw-normal" style="font-size:.75rem;">
                                            {{ $user->updated_at->format('h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-row ps-md-4">
                                <div class="info-icon {{ $user->email_verified_at ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                    <i class="fas fa-{{ $user->email_verified_at ? 'check-circle' : 'exclamation-circle' }}"></i>
                                </div>
                                <div>
                                    <div class="info-label">Email Verified</div>
                                    <div class="info-value">
                                        @if ($user->email_verified_at)
                                            <span class="text-success">
                                                {{ $user->email_verified_at->format('d M Y, h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-warning">Not verified</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Footer actions ── --}}
            <div class="d-flex gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-light border rounded-3 px-4">
                    <i class="fas fa-arrow-left me-2"></i> Back to List
                </a>
                <button class="btn bg-gradient-warning px-4 open-edit-btn" data-id="{{ $user->id }}">
                    <i class="fas fa-pen me-2"></i> Edit User
                </button>
                <button class="btn btn-outline-danger px-4 ms-auto delete-btn"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}">
                    <i class="fas fa-trash me-2"></i> Delete
                </button>
            </div>

        </div>{{-- /col-right --}}
    </div>{{-- /row --}}

</div>

{{-- ══════════════════════════════════════════════════════
     EDIT MODAL  (same tabbed modal, edit-only)
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-gradient-warning text-white d-flex align-items-center
                                justify-content-center fw-bold shadow-sm"
                         style="width:44px;height:44px;font-size:1.1rem;flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Edit User</h5>
                        <p class="text-xs text-muted mb-0">Leave password blank to keep current</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tabs --}}
            <div class="px-4 pt-2 pb-0 border-bottom">
                <ul class="nav gap-1 pb-2" id="editModalTabs">
                    <li class="nav-item">
                        <a class="nav-link active rounded-3 px-3 py-2 text-xs fw-bold text-secondary"
                           data-tab="tab-account" href="javascript:void(0)">
                            <i class="fas fa-user me-1"></i> Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-3 px-3 py-2 text-xs fw-bold text-secondary"
                           data-tab="tab-business" href="javascript:void(0)">
                            <i class="fas fa-building me-1"></i> Business
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-3 px-3 py-2 text-xs fw-bold text-secondary"
                           data-tab="tab-address" href="javascript:void(0)">
                            <i class="fas fa-map-marker-alt me-1"></i> Address & Location
                        </a>
                    </li>
                </ul>
            </div>

            <div class="modal-body px-4 py-3">
                <input type="hidden" id="user_id">

                {{-- TAB 1: Account --}}
                <div class="modal-tab-pane" id="tab-account">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Account Information</p>
                    <div class="row g-3 mb-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Full Name <span class="text-danger">*</span></label>
                            <input type="text" id="user_name" class="form-control" placeholder="Enter full name">
                            <small class="text-danger d-none" id="err_name"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Email <span class="text-danger">*</span></label>
                            <input type="email" id="user_email" class="form-control" placeholder="Enter email">
                            <small class="text-danger d-none" id="err_email"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">
                                New Password <span class="text-muted fw-normal">(blank = keep current)</span>
                            </label>
                            <div class="input-group">
                                <input type="password" id="user_password" class="form-control" placeholder="Min. 8 characters">
                                <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="user_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-danger d-none" id="err_password"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" id="user_password_confirmation" class="form-control" placeholder="Repeat password">
                                <button type="button" class="btn btn-outline-secondary toggle-pw" data-target="user_password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Role <span class="text-danger">*</span></label>
                            <select id="user_role" class="form-select">
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger d-none" id="err_role"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Status <span class="text-danger">*</span></label>
                            <select id="user_status" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-phone text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="user_phone" class="form-control border-start-0" placeholder="+971 xx xxx xxxx">
                            </div>
                            <small class="text-danger d-none" id="err_phone"></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fab fa-whatsapp text-success" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="user_whatsapp_number" class="form-control border-start-0" placeholder="+971 xx xxx xxxx">
                            </div>
                            <small class="text-danger d-none" id="err_whatsapp_number"></small>
                        </div>

                    </div>
                    <hr class="horizontal dark my-3">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">About</p>
                    <textarea id="user_about_me" class="form-control" rows="3" placeholder="Short bio..."></textarea>
                    <small class="text-danger d-none" id="err_about_me"></small>
                </div>

                {{-- TAB 2: Business --}}
                <div class="modal-tab-pane d-none" id="tab-business">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Business Information</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Company Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-building text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="user_company_name" class="form-control border-start-0" placeholder="Company name">
                            </div>
                            <small class="text-danger d-none" id="err_company_name"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Website</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-globe text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="url" id="user_website" class="form-control border-start-0" placeholder="https://example.com">
                            </div>
                            <small class="text-danger d-none" id="err_website"></small>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: Address & Location --}}
                <div class="modal-tab-pane d-none" id="tab-address">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2 mt-1">Address</p>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">City / Region</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-city text-muted" style="font-size:.75rem;"></i>
                                </span>
                                <input type="text" id="user_location" class="form-control border-start-0" placeholder="City or region">
                            </div>
                            <small class="text-danger d-none" id="err_location"></small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm mb-1">Full Address</label>
                            <textarea id="user_address" class="form-control" rows="2" placeholder="Street, building, area..."></textarea>
                            <small class="text-danger d-none" id="err_address"></small>
                        </div>
                    </div>
                    <hr class="horizontal dark my-3">
                    <p class="text-xs fw-bold text-uppercase text-secondary mb-2">GPS Coordinates <span class="text-muted fw-normal">(optional)</span></p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Latitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs text-muted">LAT</span>
                                <input type="number" id="user_latitude" class="form-control border-start-0"
                                       placeholder="e.g. 25.2048" step="any" min="-90" max="90">
                            </div>
                            <small class="text-danger d-none" id="err_latitude"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-sm mb-1">Longitude</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-xs text-muted">LNG</span>
                                <input type="number" id="user_longitude" class="form-control border-start-0"
                                       placeholder="e.g. 55.2708" step="any" min="-180" max="180">
                            </div>
                            <small class="text-danger d-none" id="err_longitude"></small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button class="btn btn-light border rounded-3 px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancel
                </button>
                <button class="btn bg-gradient-warning px-4" id="saveUserBtn">
                    <span id="saveBtnText"><i class="fas fa-save me-2"></i> Save Changes</span>
                    <span id="saveBtnSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm me-2"></span> Saving…
                    </span>
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

    /*── Tab switching ──────────────────────────────────────*/
    $(document).on('click', '#editModalTabs .nav-link', function () {
        var target = $(this).data('tab');
        $('#editModalTabs .nav-link').removeClass('active')
            .css({ background: '', color: '#6c757d' });
        $(this).addClass('active')
            .css({ background: 'linear-gradient(195deg,#42424a,#191919)', color: '#fff' });
        $('.modal-tab-pane').addClass('d-none');
        $('#' + target).removeClass('d-none');
    });

    /*── Error helpers ──────────────────────────────────────*/
    var fieldTabMap = {
        name: 'tab-account', email: 'tab-account', password: 'tab-account',
        role: 'tab-account',  status: 'tab-account', phone: 'tab-account',
        whatsapp_number: 'tab-account', about_me: 'tab-account',
        company_name: 'tab-business', website: 'tab-business',
        location: 'tab-address', address: 'tab-address',
        latitude: 'tab-address', longitude: 'tab-address',
    };

    function clearErrors() {
        $('.text-danger[id^="err_"]').addClass('d-none').text('');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#editModalTabs .tab-err-dot').remove();
    }

    function showErrors(errors) {
        var firstTab = null, tabsWithErrors = {};
        $.each(errors, function (field, messages) {
            $('#err_' + field).removeClass('d-none').text(messages[0]);
            $('#user_' + field).addClass('is-invalid');
            var tab = fieldTabMap[field] || 'tab-account';
            tabsWithErrors[tab] = true;
            if (!firstTab) firstTab = tab;
        });
        $.each(tabsWithErrors, function (tab) {
            var link = $('#editModalTabs .nav-link[data-tab="' + tab + '"]');
            if (!link.find('.tab-err-dot').length) {
                link.append('<span class="tab-err-dot" style="display:inline-block;width:7px;height:7px;background:#ea0606;border-radius:50%;margin-left:5px;vertical-align:middle;"></span>');
            }
        });
        if (firstTab) $('#editModalTabs .nav-link[data-tab="' + firstTab + '"]').trigger('click');
    }

    /*── Open edit modal ────────────────────────────────────*/
    $(document).on('click', '.open-edit-btn', function () {
        clearErrors();
        $('#user_password, #user_password_confirmation').val('');

        $.ajax({
            url     : '/admin/users/' + userId + '/edit-data',
            type    : 'GET',
            success : function (u) {
                $('#user_id').val(u.id);
                $('#user_name').val(u.name);
                $('#user_email').val(u.email);
                $('#user_phone').val(u.phone             ?? '');
                $('#user_whatsapp_number').val(u.whatsapp_number  ?? '');
                $('#user_about_me').val(u.about_me       ?? '');
                $('#user_status').val(u.status);
                $('#user_role').val(u.role);
                $('#user_company_name').val(u.company_name ?? '');
                $('#user_website').val(u.website         ?? '');
                $('#user_location').val(u.location       ?? '');
                $('#user_address').val(u.address         ?? '');
                $('#user_latitude').val(u.latitude       ?? '');
                $('#user_longitude').val(u.longitude     ?? '');
                // Reset to first tab
                $('#editModalTabs .nav-link').first().trigger('click');
                $('#userModal').modal('show');
            },
            error: function () {
                Swal.fire('Error', 'Could not load user data.', 'error');
            }
        });
    });

    /*── Save ───────────────────────────────────────────────*/
    $('#saveUserBtn').on('click', function () {
        clearErrors();
        $('#saveBtnText').addClass('d-none');
        $('#saveBtnSpinner').removeClass('d-none');
        $('#saveUserBtn').prop('disabled', true);

        $.ajax({
            url  : '/admin/users/' + userId + '/ajax-update',
            type : 'POST',
            data : {
                _token                : '{{ csrf_token() }}',
                name                  : $('#user_name').val(),
                email                 : $('#user_email').val(),
                password              : $('#user_password').val(),
                password_confirmation : $('#user_password_confirmation').val(),
                role                  : $('#user_role').val(),
                status                : $('#user_status').val(),
                phone                 : $('#user_phone').val(),
                whatsapp_number       : $('#user_whatsapp_number').val(),
                about_me              : $('#user_about_me').val(),
                company_name          : $('#user_company_name').val(),
                website               : $('#user_website').val(),
                location              : $('#user_location').val(),
                address               : $('#user_address').val(),
                latitude              : $('#user_latitude').val(),
                longitude             : $('#user_longitude').val(),
            },
            success: function (res) {
                if (res.success) {
                    $('#userModal').modal('hide');
                    Swal.fire({
                        icon: 'success', title: 'Saved!',
                        text: res.message,
                        timer: 1600, showConfirmButton: false,
                    }).then(function () { window.location.reload(); });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    showErrors(xhr.responseJSON.errors);
                } else {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            },
            complete: function () {
                $('#saveBtnText').removeClass('d-none');
                $('#saveBtnSpinner').addClass('d-none');
                $('#saveUserBtn').prop('disabled', false);
            }
        });
    });

    /*── Delete ─────────────────────────────────────────────*/
    $(document).on('click', '.delete-btn', function () {
        var name = $(this).data('name');
        Swal.fire({
            title             : 'Delete User?',
            html              : 'You are about to delete <strong>' + name + '</strong>.<br>This cannot be undone.',
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#d33',
            cancelButtonColor : '#6c757d',
            confirmButtonText : 'Yes, delete',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url  : '/admin/users/' + userId + '/destroy',
                type : 'GET',
                data : { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success', title: 'Deleted!',
                            text: res.message,
                            timer: 1500, showConfirmButton: false,
                        }).then(function () {
                            window.location.href = '{{ route('users.index') }}';
                        });
                    }
                },
                error: function () { Swal.fire('Error', 'Could not delete user.', 'error'); }
            });
        });
    });

    /*── Password toggle ─────────────────────────────────────*/
    $(document).on('click', '.toggle-pw', function () {
        var target = $('#' + $(this).data('target'));
        target.attr('type', target.attr('type') === 'password' ? 'text' : 'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

});
</script>
@endpush