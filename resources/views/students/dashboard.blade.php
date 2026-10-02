@extends('layouts.student-layout')

@section('title', 'Dashboard')

@push('styles')
<style>
    .sd, .sd * {
        box-sizing: border-box;
    }

    .sd-heading {
        margin-bottom: 26px;
    }

    .sd-heading h1 {
        margin: 0 0 8px;
        font-size: 30px;
        font-weight: 750;
        letter-spacing: -.7px;
        color: #101f35;
    }

    .sd-heading p {
        margin: 0;
        color: #75859c;
        font-size: 15px;
        line-height: 1.7;
    }

    .sd-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        padding: 20px 24px;
        border: 1px solid #e5ebf3;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(30, 50, 85, .025);
    }

    .sd-banner-start {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
    }

    .sd-banner h2 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 700;
    }

    .sd-banner p {
        margin: 0;
        color: #7a8aa1;
        font-size: 12px;
        line-height: 1.7;
    }

    .sd-icon {
        display: grid;
        place-items: center;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        border-radius: 50%;
        background: #edf3ff;
        color: #294dcc;
    }

    .sd-icon .sp-icon {
        width: 23px;
        height: 23px;
    }

    .sd-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 11px 17px;
        border-radius: 9px;
        background: #2747bb;
        color: #fff;
        text-decoration: none;
        white-space: nowrap;
        font-size: 13px;
        font-weight: 600;
    }

    .sd-button:hover {
        background: #1e399c;
    }

    .sd-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .sd-stat {
        display: flex;
        align-items: center;
        gap: 17px;
        min-height: 128px;
        padding: 24px;
        border: 1px solid #e4eaf2;
        border-left: 4px solid #3156cf;
        border-radius: 14px;
        background: #fff;
        min-width: 0;
    }

    .sd-stat.green {
        border-left-color: #13b987;
    }

    .sd-stat.green .sd-icon {
        background: #eafaF3;
        color: #0b9b70;
    }

    .sd-stat.gold {
        border-left-color: #edb300;
    }

    .sd-stat.gold .sd-icon {
        background: #fff9df;
        color: #c99600;
    }

    .sd-value {
        margin: 0 0 7px;
        font-size: 25px;
        font-weight: 700;
        color: #122741;
        overflow-wrap: anywhere;
    }

    .sd-label {
        margin: 0;
        color: #75859c;
        font-size: 11px;
        font-weight: 650;
        letter-spacing: .6px;
        text-transform: uppercase;
    }

    .sd-panels {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
        gap: 24px;
    }

    .sd-panel {
        padding: 24px;
        border: 1px solid #e4eaf2;
        border-radius: 15px;
        background: #fff;
        min-width: 0;
    }

    .sd-panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 7px;
        color: #2546ab;
        font-size: 16px;
        font-weight: 700;
    }

    .sd-panel-description {
        margin: 0 0 20px;
        padding-bottom: 19px;
        border-bottom: 1px solid #e5eaf3;
        color: #8492a6;
        font-size: 12px;
        line-height: 1.7;
    }

    .sd-action {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 12px;
        border-radius: 12px;
        color: #1b3457;
        text-decoration: none;
    }

    .sd-action + .sd-action {
        margin-top: 7px;
    }

    .sd-action:hover {
        background: #f4f7fc;
    }

    .sd-action-copy {
        flex: 1;
        min-width: 0;
    }

    .sd-action strong {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        font-weight: 650;
    }

    .sd-action small {
        display: block;
        color: #7a8ba3;
        font-size: 12px;
        line-height: 1.6;
    }

    .sd-action > .sp-icon {
        color: #a1afc3;
    }

    .sd-account {
        margin: 0;
    }

    .sd-account div {
        padding: 14px 0;
        border-bottom: 1px solid #edf1f6;
    }

    .sd-account div:last-child {
        border-bottom: 0;
    }

    .sd-account dt {
        margin-bottom: 7px;
        color: #8491a5;
        font-size: 12px;
    }

    .sd-account dd {
        margin: 0;
        color: #29415f;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .sd-reminder {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 24px;
        padding: 18px 21px;
        border: 1px solid #f0dfa0;
        border-radius: 12px;
        background: #fffbed;
        color: #a77610;
    }

    .sd-reminder strong {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
    }

    .sd-reminder p {
        margin: 0;
        font-size: 12px;
        line-height: 1.8;
    }

    .sd a:focus-visible {
        outline: 3px solid #9ab4ff;
        outline-offset: 3px;
    }

    @media (max-width: 1150px) {
        .sd-stats {
            gap: 14px;
        }

        .sd-stat {
            padding: 20px 16px;
            gap: 12px;
        }

        .sd-value {
            font-size: 21px;
        }
    }

    @media (max-width: 700px) {
        .sd-stats,
        .sd-panels {
            grid-template-columns: 1fr;
        }

        .sd-banner {
            align-items: flex-start;
            flex-direction: column;
        }

        .sd-banner .sd-button {
            width: 100%;
        }

        .sd-stat {
            min-height: 100px;
        }

        .sd-heading h1 {
            font-size: 26px;
        }

        .sd-panel {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
@php
    $dashboardStudent = auth('student')->user();
    $visitCount = $dashboardStudent->clinicVisits()->count();
    $accountStatus = ucfirst($dashboardStudent->status ?? 'pending');

    $quickLinks = [
        [
            'route' => 'student.profile',
            'icon' => 'user',
            'title' => 'My Profile',
            'description' => 'Manage your photo, email, and password.',
        ],
        [
            'route' => 'student.visits',
            'icon' => 'clock',
            'title' => 'Clinic Visits',
            'description' => 'Open your clinic visit history.',
        ],
        [
            'route' => 'student.records',
            'icon' => 'file',
            'title' => 'Medical Records',
            'description' => 'Open your medical records page.',
        ],
    ];
@endphp

<div class="sd">
    <div class="sd-heading">
        <h1>Dashboard Overview</h1>
        <p>Welcome, {{ $dashboardStudent->full_name ?: 'Student' }}.</p>
    </div>

    <section class="sd-banner" aria-label="Student account">
        <div class="sd-banner-start">
            <div class="sd-icon" aria-hidden="true">
                <svg class="sp-icon"><use href="#sp-user"/></svg>
            </div>

            <div>
                <h2>Your campus care, in one place.</h2>
                <p>Access your student profile and clinic information.</p>
            </div>
        </div>

        <a href="{{ route('student.profile') }}" class="sd-button">
            View My Profile
            <svg class="sp-icon" aria-hidden="true">
                <use href="#sp-arrow"/>
            </svg>
        </a>
    </section>

    <div class="sd-stats">
        <section class="sd-stat">
            <div class="sd-icon" aria-hidden="true">
                <svg class="sp-icon"><use href="#sp-user"/></svg>
            </div>
            <div>
                <p class="sd-value">{{ $dashboardStudent->student_number }}</p>
                <h2 class="sd-label">Student number</h2>
            </div>
        </section>

        <section class="sd-stat green">
            <div class="sd-icon" aria-hidden="true">
                <svg class="sp-icon"><use href="#sp-clock"/></svg>
            </div>
            <div>
                <p class="sd-value">{{ $visitCount }}</p>
                <h2 class="sd-label">My clinic visits</h2>
            </div>
        </section>

        <section class="sd-stat gold">
            <div class="sd-icon" aria-hidden="true">
                <svg class="sp-icon"><use href="#sp-check"/></svg>
            </div>
            <div>
                <p class="sd-value">{{ $accountStatus }}</p>
                <h2 class="sd-label">Account status</h2>
            </div>
        </section>
    </div>

    <div class="sd-panels">
        <section class="sd-panel">
            <h2 class="sd-panel-title">
                <svg class="sp-icon" aria-hidden="true">
                    <use href="#sp-home"/>
                </svg>
                Quick Access
            </h2>

            <p class="sd-panel-description">
                Find the student services you need.
            </p>

            @foreach ($quickLinks as $item)
                <a href="{{ route($item['route']) }}" class="sd-action">
                    <span class="sd-icon" aria-hidden="true">
                        <svg class="sp-icon">
                            <use href="#sp-{{ $item['icon'] }}"/>
                        </svg>
                    </span>

                    <span class="sd-action-copy">
                        <strong>{{ $item['title'] }}</strong>
                        <small>{{ $item['description'] }}</small>
                    </span>

                    <svg class="sp-icon" aria-hidden="true">
                        <use href="#sp-arrow"/>
                    </svg>
                </a>
            @endforeach
        </section>

        <section class="sd-panel">
            <h2 class="sd-panel-title">
                <svg class="sp-icon" aria-hidden="true">
                    <use href="#sp-user"/>
                </svg>
                Student Information
            </h2>

            <p class="sd-panel-description">
                Your registered account details.
            </p>

            <dl class="sd-account">
                <div>
                    <dt>Full name</dt>
                    <dd>{{ $dashboardStudent->full_name ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt>Student number</dt>
                    <dd>{{ $dashboardStudent->student_number }}</dd>
                </div>
                <div>
                    <dt>Email address</dt>
                    <dd>{{ $dashboardStudent->email }}</dd>
                </div>
            </dl>
        </section>
    </div>

    <aside class="sd-reminder">
        <svg class="sp-icon" aria-hidden="true">
            <use href="#sp-info"/>
        </svg>
        <div>
            <strong>Important Reminder</strong>
            <p>
                Inform the clinic staff about changes in your health condition
                or allergies. Keep your account details up to date.
            </p>
        </div>
    </aside>
</div>
@endsection