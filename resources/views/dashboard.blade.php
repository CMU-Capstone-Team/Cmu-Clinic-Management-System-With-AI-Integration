@extends('layouts.app')

@section('title', 'Dashboard | CMU Alaga')

@section('content')

<style>
/* Dashboard Base Styles */
.clinic-dashboard {
    font-family: 'Inter', sans-serif;
    color: #1e293b;
    animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Header Section */
.dashboard-header {
    margin-bottom: 32px;
}

.dashboard-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(28px, 3vw, 36px);
    font-weight: 800;
    background: linear-gradient(135deg, #0f172a 0%, #1e40af 50%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -1px;
    line-height: 1.2;
    margin-bottom: 8px;
}

.dashboard-subtitle {
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #64748b;
    font-weight: 500;
    line-height: 1.6;
}

.dashboard-subtitle span {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    animation: slideIn 0.8s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Action Bar */
.action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-top: 24px;
    padding: 20px 24px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 197, 253, 0.05) 100%);
    border: 1px solid rgba(59, 130, 246, 0.1);
    border-radius: 16px;
}

.action-label {
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2.5px;
    color: #64748b;
    text-transform: uppercase;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-primary svg {
    width: 20px;
    height: 20px;
    transition: transform 0.3s ease;
}

.btn-primary:hover svg {
    transform: rotate(90deg);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 32px;
}

.stat-card {
    position: relative;
    padding: 28px;
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--stat-color) 0%, var(--stat-color-light) 100%);
    transform: scaleX(0);
    transition: transform 0.35s ease;
}

.stat-card:hover::before {
    transform: scaleX(1);
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.1);
    border-color: rgba(59, 130, 246, 0.2);
}

.stat-card.blue { --stat-color: #3b82f6; --stat-color-light: #93c5fd; }
.stat-card.emerald { --stat-color: #10b981; --stat-color-light: #6ee7b7; }
.stat-card.red { --stat-color: #ef4444; --stat-color-light: #fca5a5; }
.stat-card.violet { --stat-color: #8b5cf6; --stat-color-light: #c4b5fd; }

.stat-content {
    display: flex;
    align-items: flex-start;
    gap: 18px;
}

.stat-icon {
    flex-shrink: 0;
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.stat-icon.blue { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; }
.stat-icon.emerald { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); color: #059669; }
.stat-icon.red { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #dc2626; }
.stat-icon.violet { background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); color: #7c3aed; }

.stat-icon svg {
    width: 28px;
    height: 28px;
    stroke-width: 1.8;
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 36px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
    letter-spacing: -1px;
    margin-bottom: 6px;
}

.stat-label {
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1.2px;
    color: #64748b;
    text-transform: uppercase;
}

/* Content Sections */
.content-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
    margin-top: 32px;
}

.content-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.content-card:hover {
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
    transform: translateY(-2px);
}

.content-card.large {
    grid-column: span 2;
}

.card-header {
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-icon.blue { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; }
.card-icon.red { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #dc2626; }
.card-icon.violet { background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); color: #7c3aed; }

.card-icon svg {
    width: 22px;
    height: 22px;
    stroke-width: 2;
}

.card-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
}

.card-body {
    padding: 24px;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin-bottom: 20px;
    color: #cbd5e1;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.empty-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 16px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
}

.empty-text {
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #94a3b8;
    line-height: 1.6;
    max-width: 320px;
}

/* Pending Badge */
.pending-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 112px;
    height: 112px;
    border-radius: 50%;
    border: 8px solid #f1f5f9;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 16px;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(148, 163, 184, 0.2);
    }
    50% {
        box-shadow: 0 0 0 20px rgba(148, 163, 184, 0);
    }
}

/* Chart Container */
.chart-container {
    height: 320px;
    position: relative;
}

/* Data Table Section */
.data-section {
    margin-top: 24px;
}

.data-summary {
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    padding: 12px 0;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: color 0.2s ease;
}

.data-summary:hover {
    color: #2563eb;
}

.data-table-wrapper {
    overflow-x: auto;
    margin-top: 16px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
}

.data-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.data-table th {
    padding: 14px 16px;
    text-align: left;
    font-weight: 700;
    color: #475569;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
}

.data-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    color: #64748b;
}

.data-table tbody tr {
    transition: background 0.2s ease;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

/* Responsive */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .content-card.large {
        grid-column: span 1;
    }
    
    .action-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 24px;
    }
    
    .stat-value {
        font-size: 32px;
    }
}

@media (max-width: 640px) {
    .dashboard-title {
        font-size: 24px;
    }
    
    .card-header,
    .card-body {
        padding: 20px;
    }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    .stat-card,
    .content-card,
    .btn-primary {
        transition: none;
    }
    
    .stat-card:hover,
    .content-card:hover {
        transform: none;
    }
}
</style>

<div class="clinic-dashboard mx-auto" style="max-width: 1600px;">
    {{-- Header Section --}}
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Overview</h1>
        <p class="dashboard-subtitle">
            <span>🌟 Mabuhay! zupp mga nigga.</span>
        </p>
        
        {{-- Action Bar --}}
        <div class="action-bar">
            <span class="action-label"> Student Health · At a Glance </span>
            <a href="{{ route('students.create') }}" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Register Student
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <section class="stats-grid">
        {{-- Total Students --}}
        <article class="stat-card blue">
            <div class="stat-content">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-8v5c3 2 9 2 12 0v-5"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-value">{{ number_format($totalStudents) }}</p>
                    <p class="stat-label">Total Students</p>
                </div>
            </div>
        </article>

        {{-- Visits Today --}}
        <article class="stat-card emerald">
            <div class="stat-content">
                <div class="stat-icon emerald">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-value">{{ number_format($visitsToday) }}</p>
                    <p class="stat-label">Visits Today</p>
                </div>
            </div>
        </article>

        {{-- With Allergies --}}
        <article class="stat-card red">
            <div class="stat-content">
                <div class="stat-icon red">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 4.3L2.6 18a2 2 0 001.7 3h15.4a2 2 0 001.7-3L13.7 4.3a2 2 0 00-3.4 0z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-value">{{ number_format($studentsWithAllergies) }}</p>
                    <p class="stat-label">With Allergies</p>
                </div>
            </div>
        </article>

        {{-- Special Conditions --}}
        <article class="stat-card violet">
            <div class="stat-content">
                <div class="stat-icon violet">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5a3 3 0 016 0m-6 7h6m-6 4h4"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <p class="stat-value">{{ number_format($specialConditions) }}</p>
                    <p class="stat-label">Special Conditions</p>
                </div>
            </div>
        </article>
    </section>

    {{-- Content Grid --}}
    <section class="content-grid">
        {{-- Visit Trends --}}
        <article class="content-card large">
            <div class="card-header">
                <div class="card-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8"/>
                    </svg>
                </div>
                <h2 class="card-title">Visitation Trends</h2>
            </div>
            <div class="card-body">
                <div class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 17l6-6 4 4 8-8M3 21h18"/>
                    </svg>
                    <p class="empty-title">Visit trends are not available yet</p>
                    <p class="empty-text">You can view recorded visits in the Clinic Visits page once data is available.</p>
                </div>
            </div>
        </article>

        {{-- Top Complaints --}}
        <article class="content-card">
            <div class="card-header">
                <div class="card-icon red">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M4 12a8 8 0 1116 0 8 8 0 01-16 0z"/>
                    </svg>
                </div>
                <h2 class="card-title">Top Complaints</h2>
            </div>
            <div class="card-body">
                <div class="empty-state">
                    <div class="pending-badge">Pending</div>
                    <p class="empty-title">No data available</p>
                    <p class="empty-text">Complaint summaries will appear here once records are added.</p>
                </div>
            </div>
        </article>
    </section>

    {{-- Student Distribution by Course --}}
    <section class="content-card" style="margin-top: 24px;">
        <div class="card-header">
            <div class="card-icon violet">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9m5 10V5m5 14v-7m5 7V3"/>
                </svg>
            </div>
            <h2 class="card-title">Registered Students by Course</h2>
        </div>
        <div class="card-body">
            @if(count($courseLabels) > 0)
                <div class="chart-container">
                    <canvas id="courseChart" 
                            role="img" 
                            aria-label="Registered students by course"
                            data-labels="{{ json_encode($courseLabels) }}"
                            data-values="{{ json_encode($courseData) }}">
                    </canvas>
                </div>
                
                <details class="data-section">
                    <summary class="data-summary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5a3 3 0 016 0m-6 7h6m-6 4h4"/>
                        </svg>
                        View course totals
                    </summary>
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th scope="col">Course</th>
                                    <th scope="col">Total Students</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseLabels as $index => $label)
                                    <tr>
                                        <td style="font-weight: 600; color: #475569;">{{ $label }}</td>
                                        <td style="color: #2563eb; font-weight: 700;">{{ number_format($courseData[$index] ?? 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            @else
                <div class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="empty-title">No student data available</p>
                    <p class="empty-text">Register students to see the distribution by course.</p>
                </div>
            @endif
        </div>
    </section>
</div>

@if(count($courseLabels) > 0)
<script>
window.addEventListener('load', function () {
    const canvas = document.getElementById('courseChart');
    if (!canvas) return;

    // Check if Chart.js is loaded
    if (!window.Chart) {
        canvas.parentElement.style.height = 'auto';
        canvas.hidden = true;
        const details = document.querySelector('.data-section');
        if (details) details.open = true;
        return;
    }

    // Destroy existing chart if any
    const existingChart = Chart.getChart(canvas);
    if (existingChart) {
        existingChart.destroy();
    }

    const ctx = canvas.getContext('2d');
    
    // Create gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.8)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0.2)');

    new window.Chart(canvas, {
        type: 'bar',
        data: {
            labels: JSON.parse(canvas.dataset.labels || '[]'),
            datasets: [{
                label: 'Registered Students',
                data: JSON.parse(canvas.dataset.values || '[]'),
                backgroundColor: gradient,
                borderColor: '#2563eb',
                borderWidth: 2,
                borderRadius: 12,
                borderSkipped: false,
                maxBarThickness: 100,
                hoverBackgroundColor: '#1d4ed8',
                hoverBorderColor: '#1e40af',
                hoverBorderWidth: 2
            }]
        },
        options: {
            animation: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? false : {
                duration: 800,
                easing: 'easeOutQuart'
            },
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: {
                        family: 'Plus Jakarta Sans',
                        size: 14,
                        weight: 600
                    },
                    bodyFont: {
                        family: 'Inter',
                        size: 13
                    },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Students: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            family: 'Inter',
                            size: 12
                        },
                        color: '#64748b',
                        padding: 8
                    },
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            family: 'Inter',
                            size: 12
                        },
                        color: '#64748b',
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
});
</script>
@endif

@endsection