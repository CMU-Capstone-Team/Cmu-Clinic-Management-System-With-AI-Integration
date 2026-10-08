<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Record - {{ $student->student_number }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #eef2f6;
            color: #172033;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .toolbar {
            max-width: 850px;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            padding: 0 16px;
        }

        .toolbar a {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: bold;
        }

        button {
            border: 0;
            border-radius: 6px;
            padding: 12px 20px;
            background: #1e3a8a;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #172554;
        }

        main {
            max-width: 850px;
            margin: 0 auto 30px;
            padding: 36px;
            background: white;
        }

        header {
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .brand {
            margin: 0;
            color: #1e3a8a;
            font-size: 24px;
            font-weight: bold;
        }

        h1 {
            margin: 8px 0 4px;
            font-size: 19px;
        }

        .muted {
            color: #586477;
            margin: 3px 0;
        }

        h2 {
            margin: 22px 0 8px;
            padding: 7px 10px;
            background: #edf2fa;
            border-left: 3px solid #1e3a8a;
            font-size: 13px;
            color: #1e3a8a;
            break-after: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            padding: 7px 10px;
            border: 1px solid #dce2ea;
            text-align: left;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        th {
            width: 30%;
            font-weight: bold;
            background: #fafbfc;
        }

        td {
            white-space: pre-wrap;
        }

        tr {
            break-inside: avoid;
        }

        .visit {
            margin-bottom: 14px;
        }

        .visit-title {
            margin: 12px 0 5px;
            font-weight: bold;
            break-after: avoid;
        }

        footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #dce2ea;
            color: #586477;
            font-size: 10px;
        }

        @page {
            size: A4;
            margin: 14mm;
        }

        @media print {
            body {
                background: white;
                font-size: 10pt;
            }

            .toolbar {
                display: none !important;
            }

            main {
                max-width: none;
                margin: 0;
                padding: 0;
            }

            h2,
            th {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <nav class="toolbar">
        <a href="{{ route('students.show', $student) }}">
            &larr; Back to Student Profile
        </a>

        <button type="button" onclick="window.print()">
            Print / Save as PDF
        </button>
    </nav>

    <main>
        <header>
            <p class="brand">CMU Alaga</p>

            <p class="muted">
                City of Malabon University · Clinic Management
            </p>

            <h1>Student Medical Record</h1>

            <p class="muted">
                {{ $student->full_name }} · {{ $student->student_number }}
            </p>

            <p class="muted">
                Generated: {{ $generatedAt }} (Asia/Manila)
            </p>
        </header>

        @foreach($sections as $title => $fields)
            <section>
                <h2>{{ $title }}</h2>

                <table>
                    <tbody>
                        @foreach($fields as $label => $value)
                            <tr>
                                <th scope="row">{{ $label }}</th>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endforeach

        <section>
            <h2>Clinic Visit History ({{ count($visits) }})</h2>

            @forelse($visits as $visit)
                <div class="visit">
                    <p class="visit-title">
                        Visit {{ $loop->iteration }}
                    </p>

                    <table>
                        <tbody>
                            @foreach($visit as $label => $value)
                                <tr>
                                    <th scope="row">{{ $label }}</th>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <p>No clinic visits recorded yet.</p>
            @endforelse
        </section>

        <footer>
            CMU Alaga · Student Record · {{ $student->student_number }}
        </footer>
    </main>
</body>
</html>