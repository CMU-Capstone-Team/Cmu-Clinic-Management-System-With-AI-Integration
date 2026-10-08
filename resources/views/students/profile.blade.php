@extends('layouts.student-layout')
@section('title', 'My Profile')
@push('styles')
<style>
    .sp-content {
        width: 100%;
        max-width: none;
        padding: 32px clamp(18px, 2.5vw, 44px) 48px;
        box-sizing: border-box;
    }
    .mp {
        --ink: #19324e;
        --muted: #66788e;
        --line: #e3eaf3;
        --blue: #3156d3;
        width: 100%;
        max-width: none;
        margin: 0;
        color: var(--ink);
    }
    .mp, .mp *, .mp *::before, .mp *::after {
        box-sizing: border-box;
    }
    .mp [hidden] {
        display: none !important;
    }
    .mp button, .mp input {
        font: inherit;
    }
    .mp button {
        cursor: pointer;
    }
    .mp button:disabled {
        opacity: .65;
        cursor: wait;
    }
    .mp button:focus-visible,
    .mp [role="tabpanel"]:focus-visible {
        outline: 3px solid #8cb2ff;
        outline-offset: 4px;
    }
    .mp-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }
    .mp-heading-copy {
        min-width: 0;
    }
    .mp-heading h1 {
        margin: 0 0 8px;
        color: #172b48;
        font-size: clamp(28px, 2.4vw, 36px);
        font-weight: 700;
        letter-spacing: -1px;
        line-height: 1.2;
    }
    .mp-heading p {
        margin: 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.7;
    }
    .mp-layout {
        display: grid;
        grid-template-columns: 320px minmax(0, 1fr);
        gap: 28px;
        align-items: stretch;
    }
    .mp-layout > * {
        min-width: 0;
    }
    .mp-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 8px 30px rgba(21, 47, 80, .04);
    }
    .mp-layout > aside.mp-card {
        display: flex;
        flex-direction: column;
    }
    .mp-main {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    #mp-panel-personal {
        flex: 1;
    }
    .mp-hero {
        padding: 34px 24px 30px;
        color: #fff;
        text-align: center;
        background: linear-gradient(145deg, #234b79, #122b49);
    }
    .mp-kicker {
        margin: 0 0 28px;
        color: #c3d7f0;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2px;
    }
    .mp-photo-trigger {
        position: relative;
        display: block;
        width: 166px;
        height: 166px;
        padding: 0;
        margin: 0 auto 24px;
        border: 4px solid #d9e5f3;
        border-radius: 50%;
        background: #365b83;
        color: #fff;
        box-shadow: 0 10px 30px rgba(6, 28, 57, .2);
        transition: box-shadow .18s ease;
    }
    .mp-photo-trigger:hover {
        box-shadow:
            0 0 0 6px rgba(255, 255, 255, .1),
            0 10px 30px rgba(6, 28, 57, .2);
    }
    .mp-photo-trigger img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    .mp-initial {
        display: grid;
        place-items: center;
        height: 100%;
        font-size: 58px;
        font-weight: 600;
    }
    .mp-camera {
        position: absolute;
        right: 0;
        bottom: 3px;
        display: grid;
        place-items: center;
        width: 37px;
        height: 37px;
        border: 3px solid #19395e;
        border-radius: 50%;
        background: #fff;
        color: #3156d3;
    }
    .mp-camera svg {
        width: 18px;
        height: 18px;
    }
    .mp-name {
        margin: 0 0 10px;
        color: #fff;
        font-size: 25px;
        font-weight: 700;
        line-height: 1.35;
        overflow-wrap: anywhere;
    }
    .mp-number {
        margin: 0;
        color: #cbdcf0;
        font-size: 13px;
    }
    .mp-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 13px;
        margin-top: 22px;
        border-radius: 30px;
        background: #edf1f7;
        color: #3d536c;
        font-size: 12px;
        font-weight: 600;
    }
    .mp-status::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .mp-status.active {
        background: #e4f7ed;
        color: #18714b;
    }
    .mp-status.pending {
        background: #fff3d7;
        color: #805b0d;
    }
    .mp-status.rejected {
        background: #ffe9ed;
        color: #a42f45;
    }
    .mp-summary {
        flex: 1;
        padding: 24px;
    }
    .mp-summary-label {
        margin: 0 0 7px;
        color: var(--muted);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .6px;
    }
    .mp-summary-email {
        margin: 0;
        font-size: 13px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }
    .mp-summary-note {
        padding-top: 17px;
        margin: 20px 0 0;
        border-top: 1px solid var(--line);
        color: var(--muted);
        font-size: 12px;
        line-height: 1.8;
    }
    .mp-photo-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 9px;
        margin-top: 20px;
    }
    .mp-photo-actions button {
        padding: 9px 14px;
        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 9px;
        background: transparent;
        color: #fff;
        font-size: 12px;
    }
    .mp-photo-actions .mp-photo-save {
        background: #fff;
        color: #18385c;
    }
    .mp-photo-message {
        margin: 14px 0 0;
        color: #d8e7f7;
        font-size: 12px;
        line-height: 1.6;
    }
    .mp-photo-error {
        color: #ffe1e6;
    }
    .mp-tabs {
        display: flex;
        gap: 6px;
        width: fit-content;
        max-width: 100%;
        padding: 6px;
        margin: 0;
        flex-shrink: 0;
        border: 1px solid #dce5ef;
        border-radius: 13px;
        background: #e4ebf4;
    }
    .mp-tab {
        padding: 12px 20px;
        border: 0;
        border-radius: 9px;
        background: transparent;
        color: #53667e;
        font-size: 13px !important;
        font-weight: 600 !important;
        line-height: 1.4;
        transition: background .18s ease, color .18s ease;
    }
    .mp-tab[aria-selected="true"] {
        background: #fff;
        color: #234ab8;
        box-shadow: 0 2px 7px rgba(24, 46, 77, .06);
    }
    .mp-section {
        padding: clamp(22px, 2.2vw, 34px);
    }
    .mp-section-heading {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 27px;
    }
    .mp-icon {
        display: grid;
        place-items: center;
        flex: 0 0 44px;
        height: 44px;
        border-radius: 13px;
        background: #eef3ff;
        color: #3156d3;
    }
    .mp-icon svg {
        width: 22px;
        height: 22px;
    }
    .mp-section h2 {
        margin: 1px 0 6px;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -.4px;
        line-height: 1.3;
    }
    .mp-section-heading p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }
    .mp-details {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 23px 26px;
        margin: 0;
    }
    .mp-detail {
        min-width: 0;
    }
    .mp-detail-wide {
        grid-column: 1 / -1;
    }
    .mp-detail dt {
        margin-bottom: 8px;
        color: #5b6e85;
        font-size: 12px;
        font-weight: 600;
    }
    .mp-detail dd {
        min-height: 49px;
        padding: 13px 15px;
        margin: 0;
        border: 1px solid #e8edf4;
        border-radius: 10px;
        background: #f7f9fc;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }
    .mp-detail dd.is-empty {
        color: #738198;
        font-weight: 400;
    }
    .mp-information-note {
        padding-top: 20px;
        margin: 26px 0 0;
        border-top: 1px solid var(--line);
        color: var(--muted);
        font-size: 12px;
        line-height: 1.8;
    }
    .mp-account-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        align-items: start;
    }
    .mp-field {
        margin-bottom: 19px;
    }
    .mp-field label {
        display: block;
        margin-bottom: 8px;
        color: #4c617a;
        font-size: 12px;
        font-weight: 600;
    }
    .mp-field input {
        display: block;
        width: 100%;
        min-width: 0;
        height: 48px;
        padding: 0 13px;
        border: 1px solid #dbe4ef;
        border-radius: 10px;
        background: #fbfcfe;
        color: var(--ink);
        font-size: 14px;
    }
    .mp-field input::placeholder {
        color: #75849a;
    }
    .mp-field input:focus {
        outline: none;
        border-color: #6585e5;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(49, 86, 211, .08);
    }
    .mp-password-wrap {
        position: relative;
    }
    .mp-password-wrap input {
        padding-right: 65px;
    }
    .mp-toggle {
        position: absolute;
        top: 5px;
        right: 5px;
        bottom: 5px;
        padding: 0 10px;
        border: 0;
        border-radius: 6px;
        background: transparent;
        color: #385ca5;
        font-size: 11px !important;
    }
    .mp-submit {
        min-height: 44px;
        padding: 13px 19px;
        border: 0;
        border-radius: 10px;
        background: #3156d3;
        color: #fff;
        font-size: 13px !important;
        font-weight: 600 !important;
    }
    .mp-submit:hover {
        background: #2648bd;
    }
    .mp-alert {
        padding: 14px 17px;
        margin-bottom: 20px;
        border-radius: 11px;
        font-size: 13px;
        line-height: 1.7;
    }
    .mp-success {
        border: 1px solid #c7e9d5;
        background: #edf9f2;
        color: #226c47;
    }
    .mp-error {
        border: 1px solid #f1ccd5;
        background: #fff1f3;
        color: #9e2f45;
    }
    @media (max-width: 1350px) {
        .mp-layout {
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 22px;
        }
        .mp-account-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 1100px) {
        .mp-layout {
            grid-template-columns: 250px minmax(0, 1fr);
            gap: 20px;
        }
        .mp-photo-trigger {
            width: 148px;
            height: 148px;
        }
        .mp-details {
            gap: 19px 16px;
        }
    }
    @media (max-width: 1000px) {
        .mp-heading {
            flex-wrap: wrap;
            gap: 18px;
        }
        .mp-tabs {
            margin-left: auto;
        }
    }
    @media (max-width: 800px) {
        .mp-heading {
            align-items: stretch;
            flex-direction: column;
            margin-bottom: 22px;
        }
        .mp-tabs {
            margin-left: 0;
        }
        .sp-content {
            padding: 24px 18px 36px;
        }
        .mp-layout {
            grid-template-columns: minmax(0, 1fr);
        }
        .mp-tabs {
            width: 100%;
        }
        .mp-tab {
            flex: 1;
            padding: 12px 8px;
        }
        .mp-hero {
            padding: 28px 20px;
        }
    }
    @media (max-width: 540px) {
        .sp-content {
            padding: 22px 12px 32px;
        }
        .mp-details {
            grid-template-columns: minmax(0, 1fr);
        }
        .mp-section {
            padding: 21px 17px;
        }
        .mp-tab {
            font-size: 12px !important;
        }
        .mp-section h2 {
            font-size: 18px;
        }
        .mp-submit {
            width: 100%;
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .mp * {
            transition: none !important;
        }
    }

    .mp-personal-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px 26px; }
    .mp-personal-field { min-width: 0; }
    .mp-personal-field > label, .mp-personal-field > .mp-field-label { display: block; margin-bottom: 8px; color: #5b6e85; font-size: 12px; font-weight: 600; }
    .mp-personal-field input, .mp-personal-field select {
        width: 100%; min-width: 0; height: 50px; padding: 0 14px; border: 1px solid #dbe4ef;
        border-radius: 10px; background: #fff; color: var(--ink); font: inherit; font-size: 14px;
    }
    .mp-personal-field input[readonly] { background: #f7f9fc; color: #4c617a; }
    .mp-personal-field input:focus, .mp-personal-field select:focus { outline: 2px solid #6585e5; outline-offset: 1px; }
    .mp-personal-field input::placeholder { color: #75849a; }
    .mp-personal-field-wide { grid-column: 1 / -1; }
    .mp-personal-actions { display: flex; justify-content: flex-end; margin-top: 24px; }
    @media (max-width: 600px) { .mp-personal-grid { grid-template-columns: minmax(0, 1fr); gap: 18px; } }

    /* Visual refinement; existing form layout and behavior are retained. */
    .mp {
        --ink: #20334d;
        --muted: #65758a;
        --line: #e3e9f1;
        --blue: #2e5bd4;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .mp-heading h1 {
        color: #182e4b;
        font-weight: 700;
        letter-spacing: -.9px;
    }
    .mp-heading p { color: #66778d; font-size: 13px; }
    .mp-card {
        border-color: #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 2px 4px rgba(24,46,75,.025), 0 12px 32px rgba(24,46,75,.045);
    }
    .mp-hero {
        background: linear-gradient(155deg, #24486f 0%, #1d3b60 52%, #182f4e 100%);
        border-bottom: 1px solid rgba(255,255,255,.07);
    }
    .mp-kicker { color: #c4d5ec; font-size: 10px; letter-spacing: 1.65px; }
    .mp-photo-trigger {
        border: 4px solid #eef3fa;
        box-shadow: 0 0 0 1px rgba(255,255,255,.22), 0 9px 24px rgba(7,24,47,.2);
    }
    .mp-photo-trigger:hover {
        box-shadow: 0 0 0 5px rgba(255,255,255,.09), 0 10px 26px rgba(7,24,47,.22);
    }
    .mp-camera { width: 36px; height: 36px; border-color: #1c3a5e; color: #2e5bd4; }
    .mp-name { font-size: 24px; font-weight: 600; letter-spacing: -.45px; }
    .mp-number { color: #d3e0f1; font-size: 13px; }
    .mp-status { padding: 6px 13px; font-size: 12px; font-weight: 600; }
    .mp-status.active { background: #e5f6ed; color: #176745; }
    .mp-summary { padding: 26px 24px; background: #fff; }
    .mp-summary-label { font-size: 10px; font-weight: 700; letter-spacing: 1px; color: #718097; }
    .mp-summary-email { font-size: 13px; font-weight: 500; color: #2d4563; }
    .mp-summary-note { color: #6b7c92; font-size: 12px; border-color: #e7ecf3; }
    .mp-tabs { background: #e7edf5; border-color: #dde5ef; padding: 5px; border-radius: 12px; gap: 4px; }
    .mp-tab { font-weight: 600 !important; color: #53647b; border-radius: 8px; }
    .mp-tab:hover { color: #244ebc; background: rgba(255,255,255,.45); }
    .mp-tab[aria-selected="true"] {
        color: #244ebc;
        background: #fff;
        box-shadow: 0 1px 3px rgba(24,46,75,.07), 0 3px 8px rgba(24,46,75,.035);
    }
    .mp-section-heading { padding-bottom: 21px; border-bottom: 1px solid #edf1f6; margin-bottom: 25px; }
    .mp-section h2 { color: #1c304b; font-size: 20px; font-weight: 600; letter-spacing: -.4px; }
    .mp-section-heading p { color: #6c7d91; font-size: 12px; }
    .mp-icon { background: #eef3ff; color: #345dd1; border: 1px solid #e5ecff; border-radius: 12px; }
    .mp-personal-grid { gap: 24px 26px; }
    .mp-personal-field > label, .mp-field label { color: #4b5e76; font-size: 12px; font-weight: 600; margin-bottom: 9px; }
    .mp-personal-field input, .mp-personal-field select, .mp-field input {
        height: 50px;
        border: 1px solid #dce4ef;
        border-radius: 9px;
        padding: 0 14px;
        color: #233b58;
        background: #fff;
        font-size: 14px;
        line-height: normal;
        box-shadow: 0 1px 2px rgba(24,46,75,.025);
        transition: border-color .16s ease, box-shadow .16s ease;
    }
    .mp-personal-field input[readonly] { color: #53667f; background: #f5f7fb; border-color: #e3e9f2; box-shadow: none; }
    .mp-personal-field input:not([readonly]):hover, .mp-personal-field select:hover, .mp-field input:hover { border-color: #b8c8e1; }
    .mp-personal-field input:focus, .mp-personal-field select:focus, .mp-field input:focus {
        outline: none;
        border-color: #4c75db;
        box-shadow: 0 0 0 3px rgba(46,91,212,.10);
    }
    .mp-personal-field input::placeholder, .mp-field input::placeholder { color: #8592a4; }
    .mp-password-wrap input { padding-right: 65px; }
    .mp-personal-actions { margin-top: 25px; }
    .mp-submit {
        background: #2e58cf;
        border: 1px solid #2950c1;
        border-radius: 9px;
        min-height: 46px;
        padding: 12px 20px;
        font-size: 13px !important;
        font-weight: 600 !important;
        box-shadow: 0 2px 4px rgba(37,72,169,.12);
        transition: background .16s ease, box-shadow .16s ease;
    }
    .mp-submit:hover { background: #264cbc; box-shadow: 0 3px 8px rgba(37,72,169,.17); }
    .mp-submit:active { background: #2244aa; }
    .mp-submit:focus-visible { outline: 3px solid #a8bdf5; outline-offset: 3px; }
    .mp-information-note { border-color: #e8edf4; color: #728196; font-size: 12px; line-height: 1.75; }
    .mp-alert { border-radius: 9px; }
    @media (max-width: 600px) {
        .mp-personal-grid { gap: 19px; }
        .mp-section-heading { padding-bottom: 18px; margin-bottom: 21px; }
        .mp-section h2 { font-size: 18px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .mp input, .mp select, .mp button { transition: none !important; }
    }

    /* Match the supplied admin dashboard's type scale and font families. */
    .mp-heading h1 {
        font-family: 'Inter', sans-serif;
        font-size: 30px;
        font-weight: 700;
        line-height: 36px;
        letter-spacing: normal;
    }
    .mp-heading p { font-size: 16px; font-weight: 400; line-height: 24px; }
    .mp-name { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; }
    .mp-initial { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; }
    .mp-section h2 { font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; letter-spacing: normal; }
    .mp-section-heading p { font-size: 12px; font-weight: 400; line-height: 16px; }
    .mp-personal-field > label, .mp-field label { font-size: 12px; font-weight: 500; line-height: 16px; }
    .mp input, .mp select, .mp button { font-family: 'Inter', sans-serif; }
    .mp-personal-field input, .mp-personal-field select, .mp-field input { font-size: 14px; font-weight: 400; }
    .mp-tab { font-size: 14px !important; font-weight: 500 !important; line-height: 20px; }
    .mp-submit { font-size: 14px !important; font-weight: 500 !important; line-height: 20px; }
    @media (max-width: 540px) { .mp-tab { font-size: 12px !important; } }
</style>
@endpush
@section('content')
<?php
    $status = strtolower($student->status ?? 'pending');
    $statusClass = in_array(
        $status,
        ['active', 'pending', 'rejected'],
        true
    ) ? $status : '';
    $birthday = $student->birth_date;
    $validBirthday = $birthday && !$birthday->isFuture();
    $accountTab = $errors->emailUpdate->any()
        || $errors->passwordUpdate->any();

?>
<div
    class="mp"
    id="mp-page"
    data-initial-tab="{{ $accountTab ? 'account' : 'personal' }}"
>
    <header class="mp-heading">
        <div class="mp-heading-copy">
            <h1>My Profile</h1>
            <p>Your personal information and account settings, all in one place.</p>
        </div>
    <div
        class="mp-tabs"
        role="tablist"
        aria-label="Profile settings"
    >
        <button
            id="mp-tab-personal"
            class="mp-tab"
            type="button"
            role="tab"
            data-tab="personal"
            aria-controls="mp-panel-personal"
            aria-selected="{{ $accountTab ? 'false' : 'true' }}"
            tabindex="{{ $accountTab ? '-1' : '0' }}"
        >
            Personal Information
        </button>
        <button
            id="mp-tab-account"
            class="mp-tab"
            type="button"
            role="tab"
            data-tab="account"
            aria-controls="mp-panel-account"
            aria-selected="{{ $accountTab ? 'true' : 'false' }}"
            tabindex="{{ $accountTab ? '0' : '-1' }}"
        >
            Email &amp; Password
        </button>
    </div>
    </header>
    @if (session('success'))
        <div class="mp-alert mp-success" role="status">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->getBag('default')->any())
        <div class="mp-alert mp-error" role="alert">
            @foreach ($errors->getBag('default')->all() as $message)
                <div>{{ $message }}</div>
            @endforeach
        </div>
    @endif
    <div class="mp-layout">
        <aside class="mp-card" aria-label="Student profile">
            <div class="mp-hero">
                <p class="mp-kicker">CMU ALAGA · STUDENT PORTAL</p>
                <form
                    id="mp-photo-form"
                    method="POST"
                    action="{{ route('student.profile.photo') }}"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @method('PATCH')
                    <input
                        id="mp-photo-input"
                        name="profile_photo"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        hidden
                    >
                    <button
                        id="mp-photo-choose"
                        class="mp-photo-trigger"
                        type="button"
                        aria-label="Change your profile picture"
                        title="Click to change profile picture"
                    >
                        <img
                            id="mp-photo-image"
                            width="166"
                            height="166"
                            alt="Your profile picture"
                            @if ($student->profile_photo_path)
                                src="{{ route('student.profile.photo.show') }}"
                            @else
                                hidden
                            @endif
                        >
                        <span
                            id="mp-photo-initial"
                            class="mp-initial"
                            aria-hidden="true"
                            @if ($student->profile_photo_path) hidden @endif
                        >
                            {{ mb_strtoupper(mb_substr($student->full_name ?: 'S', 0, 1)) }}
                        </span>
                        <span class="mp-camera" aria-hidden="true">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M8 5 6 8H4a2 2 0 0 0-2 2v9h20v-9a2 2 0 0 0-2-2h-2l-2-3Z"/>
                                <circle cx="12" cy="13" r="3.5"/>
                            </svg>
                        </span>
                    </button>
                    <h2 class="mp-name">
                        {{ $student->full_name ?: 'Student' }}
                    </h2>
                    <p class="mp-number">
                        Student No. {{ $student->student_number }}
                    </p>
                    <span class="mp-status {{ $statusClass }}">
                        {{ ucfirst($status) }}
                    </span>
                    <div
                        class="mp-photo-actions"
                        id="mp-photo-actions"
                        hidden
                    >
                        <button
                            id="mp-photo-save"
                            class="mp-photo-save"
                            type="submit"
                            disabled
                        >
                            Save photo
                        </button>
                        <button id="mp-photo-cancel" type="button">
                            Cancel
                        </button>
                    </div>
                    <p
                        id="mp-photo-message"
                        class="mp-photo-message"
                        role="status"
                        hidden
                    ></p>
                    <p
                        id="mp-photo-error"
                        class="mp-photo-message mp-photo-error"
                        role="alert"
                        @if (!$errors->photoUpdate->any()) hidden @endif
                    >{{ implode(' ', $errors->photoUpdate->all()) }}</p>
                </form>
            </div>
            <div class="mp-summary">
                <p class="mp-summary-label">ACCOUNT EMAIL</p>
                <p class="mp-summary-email">{{ $student->email }}</p>
                <p class="mp-summary-note">
                    Keep your details up to date so the clinic can reach you
                    when needed.
                </p>
            </div>
        </aside>
        <div class="mp-main">
            <section
                id="mp-panel-personal"
                class="mp-card mp-section"
                role="tabpanel"
                aria-labelledby="mp-tab-personal"
                tabindex="0"
                @if ($accountTab) hidden @endif
            >
                <div class="mp-section-heading">
                    <span class="mp-icon" aria-hidden="true">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="12" cy="7" r="4"/>
                            <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                        </svg>
                    </span>
                    <div>
                        <h2>Personal information</h2>
                        <p>Your student details on record with the clinic.</p>
                    </div>
                </div>
                @if ($errors->academicUpdate->any())
                    <div class="mp-alert mp-error" role="alert">
                        @foreach ($errors->academicUpdate->all() as $message)
                            <div>{{ $message }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('student.profile.academic') }}">
                    @csrf
                    @method('PATCH')
                    <div class="mp-personal-grid">
                        <div class="mp-personal-field">
                            <label for="mp-full-name">Full name</label>
                            <input id="mp-full-name" value="{{ $student->full_name }}" readonly>
                        </div>
                        <div class="mp-personal-field">
                            <label for="mp-personal-email">Email address</label>
                            <input id="mp-personal-email" value="{{ $student->email }}" readonly>
                        </div>
                        <div class="mp-personal-field">
                            <label for="mp-student-number">Student number</label>
                            <input id="mp-student-number" value="{{ $student->student_number }}" readonly>
                        </div>
                        <div class="mp-personal-field">
                            <label for="mp-year-section">Year &amp; Section</label>
                            <input id="mp-year-section" name="year_section" type="text" maxlength="80" required
                                value="{{ old('year_section', collect([$student->course, collect([$student->year_level, $student->section])->filter(fn ($value) => filled($value))->implode('')])->filter(fn ($value) => filled($value))->implode(' - ')) }}"
                                placeholder="e.g. BSIT - 3D">
                        </div>
                        <div class="mp-personal-field">
                            <label for="mp-sex">Gender</label>
                            <select id="mp-sex" name="sex">
                                <option value="">Select gender</option>
                                @foreach (['male' => 'Male', 'female' => 'Female'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('sex', $student->sex) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mp-personal-field">
                            <label for="mp-birth-date">Birthday</label>
                            <input id="mp-birth-date" name="birth_date" type="date" max="{{ now()->toDateString() }}" autocomplete="bday"
                                value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="mp-personal-field">
                            <label for="mp-contact">Contact number</label>
                            <input id="mp-contact" name="contact_number" type="tel" autocomplete="tel" maxlength="20"
                                value="{{ old('contact_number', $student->contact_number) }}" placeholder="Enter contact number">
                        </div>
                        <div class="mp-personal-field">
                            <label for="mp-address">Address</label>
                            <input id="mp-address" name="address" maxlength="500" autocomplete="street-address"
                                value="{{ old('address', $student->address) }}" placeholder="Enter your address">
                        </div>
                    </div>
                    <div class="mp-personal-actions">
                        <button type="submit" class="mp-submit">Save Personal Information</button>
                    </div>
                </form>
                <p class="mp-information-note">
                    Saved personal information is also available to clinic staff.
                    Change your email in the Email &amp; Password tab.
                </p>
            </section>
            <div
                id="mp-panel-account"
                role="tabpanel"
                aria-labelledby="mp-tab-account"
                tabindex="0"
                @if (!$accountTab) hidden @endif
            >
                <div class="mp-account-grid">
                    <section
                        class="mp-card mp-section"
                        aria-labelledby="mp-email-title"
                    >
                        <div class="mp-section-heading">
                            <span class="mp-icon" aria-hidden="true">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect x="3" y="5" width="18" height="14" rx="3"/>
                                    <path d="m4 7 8 6 8-6"/>
                                </svg>
                            </span>
                            <div>
                                <h2 id="mp-email-title">Email address</h2>
                                <p>Manage the email you use to sign in.</p>
                            </div>
                        </div>
                        @if ($errors->emailUpdate->any())
                            <div class="mp-alert mp-error" role="alert">
                                @foreach ($errors->emailUpdate->all() as $message)
                                    <div>{{ $message }}</div>
                                @endforeach
                            </div>
                        @endif
                        <form
                            method="POST"
                            action="{{ route('student.profile.email') }}"
                            data-account-form
                        >
                            @csrf
                            @method('PATCH')
                            <div class="mp-field">
                                <label for="mp-email">Email address</label>
                                <input
                                    id="mp-email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email', $student->email) }}"
                                    autocomplete="email"
                                    maxlength="255"
                                    required
                                >
                            </div>
                            <div class="mp-field">
                                <label for="mp-email-password">
                                    Current password
                                </label>
                                <div class="mp-password-wrap">
                                    <input
                                        id="mp-email-password"
                                        name="current_password"
                                        type="password"
                                        autocomplete="current-password"
                                        placeholder="Enter current password"
                                        required
                                    >
                                    <button
                                        type="button"
                                        class="mp-toggle"
                                        data-target="mp-email-password"
                                        aria-controls="mp-email-password"
                                        aria-label="Show current password for email update"
                                        aria-pressed="false"
                                    >Show</button>
                                </div>
                            </div>
                            <button type="submit" class="mp-submit">
                                Save email address
                            </button>
                        </form>
                    </section>
                    <section
                        class="mp-card mp-section"
                        aria-labelledby="mp-password-title"
                    >
                        <div class="mp-section-heading">
                            <span class="mp-icon" aria-hidden="true">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <rect x="5" y="10" width="14" height="11" rx="3"/>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/>
                                </svg>
                            </span>
                            <div>
                                <h2 id="mp-password-title">
                                    Password &amp; security
                                </h2>
                                <p>Use a strong, unique password for your account.</p>
                            </div>
                        </div>
                        @if ($errors->passwordUpdate->any())
                            <div class="mp-alert mp-error" role="alert">
                                @foreach ($errors->passwordUpdate->all() as $message)
                                    <div>{{ $message }}</div>
                                @endforeach
                            </div>
                        @endif
                        <form
                            method="POST"
                            action="{{ route('student.profile.password') }}"
                            data-account-form
                        >
                            @csrf
                            @method('PATCH')
                            <div class="mp-field">
                                <label for="mp-current-password">
                                    Current password
                                </label>
                                <div class="mp-password-wrap">
                                    <input
                                        id="mp-current-password"
                                        name="current_password"
                                        type="password"
                                        autocomplete="current-password"
                                        placeholder="Enter current password"
                                        required
                                    >
                                    <button
                                        type="button"
                                        class="mp-toggle"
                                        data-target="mp-current-password"
                                        aria-controls="mp-current-password"
                                        aria-label="Show current password"
                                        aria-pressed="false"
                                    >Show</button>
                                </div>
                            </div>
                            <div class="mp-field">
                                <label for="mp-new-password">New password</label>
                                <div class="mp-password-wrap">
                                    <input
                                        id="mp-new-password"
                                        name="password"
                                        type="password"
                                        autocomplete="new-password"
                                        placeholder="At least 8 characters"
                                        minlength="8"
                                        maxlength="72"
                                        required
                                    >
                                    <button
                                        type="button"
                                        class="mp-toggle"
                                        data-target="mp-new-password"
                                        aria-controls="mp-new-password"
                                        aria-label="Show new password"
                                        aria-pressed="false"
                                    >Show</button>
                                </div>
                            </div>
                            <div class="mp-field">
                                <label for="mp-confirm-password">
                                    Confirm new password
                                </label>
                                <div class="mp-password-wrap">
                                    <input
                                        id="mp-confirm-password"
                                        name="password_confirmation"
                                        type="password"
                                        autocomplete="new-password"
                                        placeholder="Re-enter new password"
                                        minlength="8"
                                        maxlength="72"
                                        required
                                    >
                                    <button
                                        type="button"
                                        class="mp-toggle"
                                        data-target="mp-confirm-password"
                                        aria-controls="mp-confirm-password"
                                        aria-label="Show password confirmation"
                                        aria-pressed="false"
                                    >Show</button>
                                </div>
                            </div>
                            <button type="submit" class="mp-submit">
                                Update password
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
(() => {
    function initProfile() {
        const root = document.getElementById('mp-page');
        if (!root || root.dataset.ready) return;
        root.dataset.ready = 'true';
        const tabs = [...root.querySelectorAll('[role="tab"]')];
        function activateTab(name, focus = false) {
            tabs.forEach(tab => {
                const active = tab.dataset.tab === name;
                const panel = document.getElementById(
                    tab.getAttribute('aria-controls')
                );
                tab.setAttribute('aria-selected', String(active));
                tab.tabIndex = active ? 0 : -1;
                panel.hidden = !active;
                if (active && focus) tab.focus();
            });
        }
        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                activateTab(tab.dataset.tab);
            });
            tab.addEventListener('keydown', event => {
                let next;
                if (event.key === 'ArrowRight') {
                    next = (index + 1) % tabs.length;
                }
                if (event.key === 'ArrowLeft') {
                    next = (index - 1 + tabs.length) % tabs.length;
                }
                if (event.key === 'Home') next = 0;
                if (event.key === 'End') next = tabs.length - 1;
                if (next !== undefined) {
                    event.preventDefault();
                    activateTab(tabs[next].dataset.tab, true);
                }
            });
        });
        try {
            if (sessionStorage.getItem('cmu-profile-return') === 'account') {
                activateTab('account');
            }
            sessionStorage.removeItem('cmu-profile-return');
        } catch (error) {}
        if (root.dataset.initialTab === 'account') {
            activateTab('account');
        }
        root.querySelectorAll('[data-account-form]').forEach(form => {
            form.addEventListener('submit', () => {
                try {
                    sessionStorage.setItem('cmu-profile-return', 'account');
                } catch (error) {}
            });
        });
        root.querySelectorAll('.mp-toggle').forEach(button => {
            const originalLabel = button.getAttribute('aria-label');
            button.addEventListener('click', () => {
                const field = document.getElementById(button.dataset.target);
                const show = field.type === 'password';
                field.type = show ? 'text' : 'password';
                button.textContent = show ? 'Hide' : 'Show';
                button.setAttribute('aria-pressed', String(show));
                button.setAttribute(
                    'aria-label',
                    show
                        ? originalLabel.replace('Show', 'Hide')
                        : originalLabel
                );
            });
        });
        const input = document.getElementById('mp-photo-input');
        const photo = document.getElementById('mp-photo-image');
        const initial = document.getElementById('mp-photo-initial');
        const choose = document.getElementById('mp-photo-choose');
        const actions = document.getElementById('mp-photo-actions');
        const save = document.getElementById('mp-photo-save');
        const cancel = document.getElementById('mp-photo-cancel');
        const message = document.getElementById('mp-photo-message');
        const error = document.getElementById('mp-photo-error');
        const form = document.getElementById('mp-photo-form');
        const originalSource = photo.getAttribute('src');
        let previewUrl = null;
        let selectionVersion = 0;
        let validSelection = false;
        function resetPhoto() {
            selectionVersion++;
            validSelection = false;
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }
            previewUrl = null;
            input.value = '';
            actions.hidden = true;
            save.disabled = true;
            message.hidden = true;
            error.hidden = true;
            error.textContent = '';
            if (originalSource) {
                photo.src = originalSource;
            } else {
                photo.removeAttribute('src');
            }
            photo.hidden = !originalSource;
            initial.hidden = !!originalSource;
        }
        function showPhotoError(text) {
            resetPhoto();
            error.textContent = text;
            error.hidden = false;
        }
        choose.addEventListener('click', () => {
            input.click();
        });
        cancel.addEventListener('click', resetPhoto);
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;
            validSelection = false;
            save.disabled = true;
            error.hidden = true;
            error.textContent = '';
            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];
            if (
                !allowedTypes.includes(file.type)
                || file.size > 2 * 1024 * 1024
            ) {
                showPhotoError(
                    'Please choose a JPG, PNG, or WebP photo up to 2 MB.'
                );
                return;
            }
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }
            const currentVersion = ++selectionVersion;
            previewUrl = URL.createObjectURL(file);
            const candidateUrl = previewUrl;
            const candidate = new Image();
            actions.hidden = false;
            message.hidden = false;
            message.textContent = 'Checking your photo…';
            candidate.onload = () => {
                if (currentVersion !== selectionVersion) return;
                if (
                    candidate.width > 4096
                    || candidate.height > 4096
                ) {
                    showPhotoError(
                        'Please choose a photo no larger than 4096 × 4096 pixels.'
                    );
                    return;
                }
                photo.src = candidateUrl;
                photo.hidden = false;
                initial.hidden = true;
                validSelection = true;
                save.disabled = false;
                message.textContent =
                    'Preview ready. Save your photo to apply it.';
            };
            candidate.onerror = () => {
                if (currentVersion !== selectionVersion) return;
                showPhotoError(
                    'This image could not be opened. Please choose another photo.'
                );
            };
            candidate.src = candidateUrl;
        });
        form.addEventListener('submit', event => {
            if (!validSelection || !input.files.length) {
                event.preventDefault();
                return;
            }
            save.disabled = true;
            cancel.disabled = true;
            choose.disabled = true;
            save.textContent = 'Uploading…';
            message.textContent = 'Saving your profile picture…';
        });
        window.addEventListener('pageshow', event => {
            if (!event.persisted) return;
            resetPhoto();
            cancel.disabled = false;
            choose.disabled = false;
            save.textContent = 'Save photo';
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initProfile,
            { once: true }
        );
    } else {
        initProfile();
    }
})();
</script>
@endpush
