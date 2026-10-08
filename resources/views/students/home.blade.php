@extends('layouts.student-layout')

@section('title', 'Home')

@push('styles')
<style>
    .sh,
    .sh * {
        box-sizing: border-box;
    }

    .sh {
        color: #0f172a;
        font-family: 'Inter', sans-serif;
    }

    .sh-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .sh-heading h1 {
        margin: 0 0 6px;
        font-size: 30px;
        font-weight: 700;
        line-height: 36px;
    }

    .sh-heading p {
        margin: 0;
        color: #64748b;
        font-size: 16px;
        line-height: 24px;
    }

    .sh-date {
        flex-shrink: 0;
        color: #64748b;
        font-size: 12px;
    }

    .sh-welcome {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
        background: #fff;
        border: 1px solid #edf1f6;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgb(15 23 42 / 4%);
    }

    .sh-welcome-icon,
    .sh-card-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        color: #2341bf;
        background: #eff6ff;
    }

    .sh-icon {
        width: 24px;
        height: 24px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .sh-welcome h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        line-height: 28px;
        overflow-wrap: anywhere;
    }

    .sh-section-title {
        margin: 0 0 14px;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
    }

    .sh-access {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .sh-card {
        --card-color: #2341bf;
        --card-background: #eff6ff;

        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
        min-height: 112px;
        padding: 24px;
        border: 1px solid #edf1f6;
        border-left: 3px solid var(--card-color);
        border-radius: 12px;
        background: #fff;
        color: inherit;
        text-decoration: none;
        box-shadow: 0 1px 2px rgb(15 23 42 / 4%);
        transition: box-shadow .18s ease, background-color .18s ease;
    }

    .sh-card:hover {
        background: #fcfdff;
        box-shadow: 0 5px 16px rgb(15 23 42 / 8%);
    }

    .sh-card:focus-visible {
        outline: 3px solid #93b4fa;
        outline-offset: 4px;
    }

    .sh-card--visits {
        --card-color: #00b984;
        --card-background: #ecfdf5;
    }

    .sh-card--records {
        --card-color: #ef4444;
        --card-background: #fef2f2;
    }

    .sh-card-icon {
        color: var(--card-color);
        background: var(--card-background);
    }

    .sh-card h3 {
        margin: 0 0 6px;
        font-size: 16px;
        font-weight: 600;
        line-height: 24px;
    }

    .sh-card p {
        margin: 0;
        color: #64748b;
        font-size: 12px;
        line-height: 19px;
    }

    .sh-bottom {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
        gap: 24px;
        margin-top: 24px;
    }

    .sh-panel {
        min-width: 0;
        padding: 24px;
        background: #fff;
        border: 1px solid #edf1f6;
        border-radius: 12px;
        box-shadow: 0 1px 2px rgb(15 23 42 / 4%);
    }

    .sh-panel-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
        color: #2341bf;
    }

    .sh-panel-heading .sh-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .sh-panel-heading h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        line-height: 24px;
    }

    .sh-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 260px;
        padding: 32px 16px;
        text-align: center;
    }

    .sh-empty > .sh-icon {
        width: 40px;
        height: 40px;
        margin-bottom: 18px;
        color: #cbd5e1;
        stroke-width: 1.5;
    }

    .sh-empty strong {
        display: block;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        line-height: 22px;
    }

    .sh-empty p {
        max-width: 360px;
        margin: 6px 0 0;
        color: #7b8ba3;
        font-size: 13px;
        line-height: 21px;
    }

    .sh-info {
        margin: 0;
    }

    .sh-info > div {
        padding: 20px 0;
    }

    .sh-info > div + div {
        border-top: 1px solid #edf1f6;
    }

    .sh-info > div:last-child {
        padding-bottom: 0;
    }

    .sh-info dt {
        margin-bottom: 8px;
        color: #64748b;
        font-size: 12px;
        font-weight: 500;
    }

    .sh-info dd {
        margin: 0;
        color: #334155;
        font-size: 13px;
        line-height: 22px;
    }

    @media (max-width: 1100px) {
        .sh-access {
            gap: 14px;
        }

        .sh-card {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
            padding: 20px;
        }

        .sh-bottom {
            grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
        }
    }

    @media (max-width: 700px) {
        .sh-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
        }

        .sh-access,
        .sh-bottom {
            grid-template-columns: minmax(0, 1fr);
        }

        .sh-card {
            align-items: center;
            flex-direction: row;
            gap: 16px;
            min-height: 100px;
        }

        .sh-welcome,
        .sh-panel {
            padding: 20px;
        }

        .sh-welcome h2 {
            font-size: 16px;
            line-height: 25px;
        }

        .sh-empty {
            min-height: 220px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .sh-card {
            transition: none;
        }
    }

    
        .sp-content {
            width: 100%;
            max-width: 1560px;
            padding: 42px 36px 56px;
        }


        .sh-welcome {
            position: relative;
            isolation: isolate;
            display: flex;
            align-items: center;
            min-height: 158px;
            margin-bottom: 28px;
            padding: 30px 36px;
            overflow: hidden;
            border: 1px solid #29478a;
            border-radius: 16px;
            background: linear-gradient(
                115deg,
                #192e59 0%,
                #203d78 60%,
                #294ba0 100%
            );
            box-shadow: 0 8px 24px rgb(25 46 89 / 10%);
            color: #fff;
        }

        /* Subtle background detail */
        .sh-welcome::after {
            content: "";
            position: absolute;
            z-index: -1;
            top: -145px;
            right: -70px;
            width: 380px;
            height: 380px;
            border: 1px solid rgb(255 255 255 / 9%);
            border-radius: 50%;
            box-shadow:
                0 0 0 48px rgb(255 255 255 / 3%),
                0 0 0 96px rgb(255 255 255 / 2%);
            pointer-events: none;
        }

        .sh-welcome-copy {
            min-width: 0;
        }

        .sh-welcome h2 {
            margin: 0;
            color: #fff;
            font-size: clamp(23px, 2vw, 30px);
            font-weight: 700;
            line-height: 1.4;
            letter-spacing: -.6px;
            overflow-wrap: anywhere;
        }

        .sh-student-number {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px 12px;
            margin: 14px 0 0;
            font-size: 13px;
            line-height: 22px;
        }

        .sh-student-number span {
            color: #c5d3ed;
            font-weight: 400;
        }

        .sh-student-number strong {
            padding: 3px 11px;
            border: 1px solid rgb(255 255 255 / 15%);
            border-radius: 6px;
            background: rgb(255 255 255 / 8%);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            letter-spacing: .4px;
            overflow-wrap: anywhere;
        }

        /* Consistent card spacing */
        .sh-section-title {
            margin-bottom: 14px;
            color: #475569;
            font-size: 14px;
            font-weight: 600;
        }

        .sh-access {
            gap: 20px;
        }

        .sh-card {
            min-height: 120px;
            border-radius: 14px;
        }

        .sh-bottom {
            gap: 24px;
        }

        .sh-panel {
            border-radius: 14px;
        }

        @media (max-width: 900px) {
            .sp-content {
                padding: 28px 22px 40px;
            }

            .sh-welcome {
                padding: 28px;
            }
        }

        @media (max-width: 700px) {
            .sh-welcome {
                min-height: 145px;
                padding: 24px;
            }

            .sh-welcome h2 {
                font-size: 23px;
                line-height: 1.4;
            }

            .sh-access {
                gap: 14px;
            }

            .sh-card {
                min-height: 106px;
            }
        }

        @media (max-width: 480px) {
            .sp-content {
                padding: 24px 14px 36px;
            }

            .sh-welcome {
                padding: 24px 20px;
            }

            .sh-welcome h2 {
                font-size: 21px;
            }

            .sh-student-number {
                align-items: flex-start;
                flex-direction: column;
                gap: 6px;
            }
        }

                .sh-student-number {
            display: block;
            margin: 8px 0 0;
            padding: 0;
            border: none;
            border-radius: 0;
            background: none;
            box-shadow: none;
            color: #d5e0f3;
            font-size: 14px;
            font-weight: 400;
            line-height: 22px;
            letter-spacing: .4px;
        }
        
</style>
@endpush

@section('content')
<div class="sh">
    <header class="sh-heading">
        <div>
            <h1>Home</h1>
            <p>Your CMU Alaga student portal.</p>
        </div>

        <time
            class="sh-date"
            datetime="{{ now('Asia/Manila')->toDateString() }}"
        >
            {{ now('Asia/Manila')->format('l, F j, Y') }}
        </time>
    </header>

    <section class="sh-welcome" aria-labelledby="sh-welcome-title">
        <div class="sh-welcome-copy">   
            <h2 id="sh-welcome-title">
                Welcome, {{ $student->full_name ?: 'Student' }}!
            </h2>

            <p class="sh-student-number">
            {{ $student->student_number }}
        </p>

        </div>
    </section>

    <section aria-labelledby="sh-access-title">
        <h2 class="sh-section-title" id="sh-access-title">
            Quick Access
        </h2>

        <div class="sh-access">
            <a class="sh-card" href="{{ route('student.profile') }}">
                <span class="sh-card-icon" aria-hidden="true">
                    <svg class="sh-icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M4 21v-2a8 8 0 0 1 16 0v2Z"/>
                    </svg>
                </span>

                <div>
                    <h3>My Profile</h3>
                    <p>View and update your personal details.</p>
                </div>
            </a>

            <a
                class="sh-card sh-card--visits"
                href="{{ route('student.visits') }}"
            >
                <span class="sh-card-icon" aria-hidden="true">
                    <svg class="sh-icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </span>

                <div>
                    <h3>Clinic Visits</h3>
                    <p>View your previous clinic visits.</p>
                </div>
            </a>

            <a
                class="sh-card sh-card--records"
                href="{{ route('student.records') }}"
            >
                <span class="sh-card-icon" aria-hidden="true">
                    <svg class="sh-icon" viewBox="0 0 24 24">
                        <path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9Z"/>
                        <path d="M14 3v6h6M8 13h8M8 17h6"/>
                    </svg>
                </span>

                <div>
                    <h3>Medical Records</h3>
                    <p>Access your health information.</p>
                </div>
            </a>
        </div>
    </section>

    <div class="sh-bottom">
        <section class="sh-panel" aria-labelledby="sh-announcements">
            <header class="sh-panel-heading">
                <svg class="sh-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                    <path d="M10 21h4"/>
                </svg>

                <h2 id="sh-announcements">Clinic Announcements</h2>
            </header>

            <div class="sh-empty">
                <svg class="sh-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                    <path d="M10 21h4"/>
                </svg>

                <strong>No announcements available yet.</strong>
                <p>Check with the clinic staff for current notices and updates.</p>
            </div>
        </section>

        <section class="sh-panel" aria-labelledby="sh-information">
            <header class="sh-panel-heading">
                <svg class="sh-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 11v6M12 7h.01"/>
                </svg>

                <h2 id="sh-information">Clinic Information</h2>
            </header>

            <dl class="sh-info">
                <div>
                    <dt>Clinic schedule</dt>
                    <dd>Schedule not yet provided.</dd>
                </div>

                <div>
                    <dt>Before your visit</dt>
                    <dd>
                        Keep your contact details updated so clinic staff
                        can reach you.
                    </dd>
                </div>
            </dl>
        </section>
    </div>
</div>
@endsection