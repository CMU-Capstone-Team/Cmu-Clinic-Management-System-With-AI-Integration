<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentExportController extends Controller
{
    private function recordData(Student $student): array
    {
        $student->load([
            'medicalProfile',
            'clinicVisits' => fn ($query) => $query->latest('visited_at'),
        ]);

        $profile = $student->medicalProfile;

        $display = static function ($value): string {
            return filled($value) ? (string) $value : 'None Recorded';
        };

        $allergyStatus = match ($profile?->allergy_status) {
            'has_allergies' => 'Has Allergies',
            'none_known' => 'None Known',
            default => 'Unconfirmed',
        };

        $sections = [
            'Personal Information' => [
                'Student Number' => $display($student->student_number),
                'Full Name' => $display($student->full_name),
                'Student Status' => $student->is_active
                    ? 'Active Student'
                    : 'Inactive Student',
                'Course' => $display($student->course),
                'Year Level' => $display($student->year_level),
                'Section' => $display($student->section),
                'Birthday' => $student->birth_date?->format('F j, Y')
                    ?? 'None Recorded',
                'Age' => $student->birth_date
                    ? $student->birth_date->age . ' years old'
                    : 'None Recorded',
                'Sex' => $display($student->sex),
                'Contact Number' => $display($student->contact_number),
                'Email' => $display($student->email),
                'Address' => $display($student->address),
            ],

            'Medical Records' => [
                'Allergy Status' => $allergyStatus,
                'Allergy Details' => $display($profile?->allergies),
                'Blood Type' => $display($profile?->blood_type),
                'Existing Conditions' => $display($profile?->existing_conditions),
                'Current Medications' => $display($profile?->current_medications),
            ],

            'Emergency Contact' => [
                'Parent/Guardian' => $display($student->emergency_contact_name),
                'Relationship' => $display($student->emergency_contact_relationship),
                'Contact Number' => $display($student->emergency_contact_number),
            ],

            'Additional Medical Information' => [
                'Past Surgeries' => $display($profile?->past_surgeries),
                'Family Medical History' => $display($profile?->family_medical_history),
                'Immunization Notes' => $display($profile?->immunization_notes),
                'Additional Notes' => $display($profile?->additional_notes),
            ],
        ];

        $visits = $student->clinicVisits->map(function ($visit) use ($display) {
            $symptoms = collect($visit->symptoms ?? [])
                ->map(fn ($symptom) => ucwords(str_replace('_', ' ', (string) $symptom)))
                ->implode(', ');

            return [
                'Visit Number' => $display($visit->visit_number),
                'Date and Time' => $visit->visited_at?->format('F j, Y h:i A')
                    ?? 'None Recorded',
                'Chief Complaint' => $display($visit->chief_complaint),
                'Symptoms' => $display($symptoms),
                'Status' => filled($visit->status)
                    ? ucwords(str_replace('_', ' ', $visit->status))
                    : 'None Recorded',
            ];
        })->all();

        return [
            'student' => $student,
            'sections' => $sections,
            'visits' => $visits,
            'generatedAt' => now()
                ->timezone('Asia/Manila')
                ->format('F j, Y h:i A'),
        ];
    }

    public function printRecord(Student $student): Response
    {
        return response()
            ->view('students.print', $this->recordData($student))
            ->header('Cache-Control', 'private, no-store, max-age=0');
    }

    public function excel(Student $student): StreamedResponse
    {
        $data = $this->recordData($student);
        $workbook = new Spreadsheet();

        $recordSheet = $workbook->getActiveSheet();
        $recordSheet->setTitle('Student Record');

        $rows = [
            ['CMU Alaga - Student Record', '', ''],
            ['Generated (Asia/Manila)', $data['generatedAt'], ''],
            ['', '', ''],
            ['Section', 'Field', 'Information'],
        ];

        foreach ($data['sections'] as $section => $fields) {
            foreach ($fields as $label => $value) {
                $rows[] = [$section, $label, $value];
            }
        }

        foreach ($rows as $rowIndex => $values) {
            foreach ($values as $columnIndex => $value) {
                $column = ['A', 'B', 'C'][$columnIndex];

                $recordSheet->setCellValueExplicit(
                    $column . ($rowIndex + 1),
                    (string) $value,
                    DataType::TYPE_STRING
                );
            }
        }

        $recordSheet->mergeCells('A1:C1');

        $recordSheet->getStyle('A1')
            ->getFont()->setBold(true)->setSize(16);

        $recordSheet->getStyle('A4:C4')
            ->getFont()->setBold(true);

        $recordSheet->getStyle('A4:C4')
            ->getFont()->getColor()->setARGB('FFFFFFFF');

        $recordSheet->getStyle('A4:C4')
            ->getFill()->setFillType('solid')
            ->getStartColor()->setARGB('FF1E3A8A');

        $recordSheet->getColumnDimension('A')->setWidth(32);
        $recordSheet->getColumnDimension('B')->setWidth(27);
        $recordSheet->getColumnDimension('C')->setWidth(75);

        $recordSheet->getStyle('A1:C' . count($rows))
            ->getAlignment()->setWrapText(true)->setVertical('top');

        $recordSheet->freezePane('C5');
        $recordSheet->setAutoFilter('A4:C' . count($rows));

        $visitSheet = $workbook->createSheet();
        $visitSheet->setTitle('Clinic Visits');

        $visitRows = [
            ['Student Number', (string) $student->student_number, '', '', ''],
            ['Full Name', $student->full_name, '', '', ''],
            ['', '', '', '', ''],
            ['Visit Number', 'Date and Time', 'Chief Complaint', 'Symptoms', 'Status'],
        ];

        foreach ($data['visits'] as $visit) {
            $visitRows[] = array_values($visit);
        }

        if (empty($data['visits'])) {
            $visitRows[] = ['No clinic visits recorded yet.', '', '', '', ''];
        }

        foreach ($visitRows as $rowIndex => $values) {
            foreach ($values as $columnIndex => $value) {
                $column = ['A', 'B', 'C', 'D', 'E'][$columnIndex];

                $visitSheet->setCellValueExplicit(
                    $column . ($rowIndex + 1),
                    (string) $value,
                    DataType::TYPE_STRING
                );
            }
        }

        foreach ([
            'A' => 25,
            'B' => 30,
            'C' => 45,
            'D' => 55,
            'E' => 20,
        ] as $column => $width) {
            $visitSheet->getColumnDimension($column)->setWidth($width);
        }

        $visitSheet->getStyle('A4:E4')
            ->getFont()->setBold(true);

        $visitSheet->getStyle('A4:E4')
            ->getFont()->getColor()->setARGB('FFFFFFFF');

        $visitSheet->getStyle('A4:E4')
            ->getFill()->setFillType('solid')
            ->getStartColor()->setARGB('FF1E3A8A');

        $visitSheet->getStyle('A1:E' . count($visitRows))
            ->getAlignment()->setWrapText(true)->setVertical('top');

        $visitSheet->freezePane('A5');

        if (!empty($data['visits'])) {
            $visitSheet->setAutoFilter('A4:E' . count($visitRows));
        }

        $workbook->setActiveSheetIndex(0);

        $filename = 'student-record-' . $student->getKey()
            . '-' . now()->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(
            function () use ($workbook): void {
                try {
                    (new Xlsx($workbook))->save('php://output');
                } finally {
                    $workbook->disconnectWorksheets();
                }
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'private, no-store, max-age=0',
            ]
        );
    }
}