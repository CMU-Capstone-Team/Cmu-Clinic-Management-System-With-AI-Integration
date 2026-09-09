<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicalExcuseRequest;
use App\Models\ClinicVisit;
use App\Models\MedicalExcuse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MedicalExcuseController extends Controller
{
    public function index(): View
    {
        $medicalExcuses = MedicalExcuse::query()
            ->with([
                'clinicVisit.student',
                'issuer',
            ])
            ->latest('issued_at')
            ->paginate(15);

        return view('medical-excuses.index', compact('medicalExcuses'));
    }

    public function create(
        ClinicVisit $clinicVisit
    ): View|RedirectResponse {
        $clinicVisit->load([
            'student',
            'medicalExcuse',
        ]);

        abort_unless(
            in_array($clinicVisit->status, ['completed', 'referred'], true),
            403,
            'The clinic visit must be finalized before issuing a medical excuse.'
        );

        if ($clinicVisit->medicalExcuse) {
            return redirect()
                ->route(
                    'medical-excuses.show',
                    $clinicVisit->medicalExcuse
                )
                ->with(
                    'error',
                    'A medical excuse has already been issued for this visit.'
                );
        }

        return view(
            'medical-excuses.create',
            compact('clinicVisit')
        );
    }

    public function store(
        StoreMedicalExcuseRequest $request,
        ClinicVisit $clinicVisit
    ): RedirectResponse {
        abort_unless(
            in_array($clinicVisit->status, ['completed', 'referred'], true),
            403,
            'The clinic visit must be finalized before issuing a medical excuse.'
        );

        if ($clinicVisit->medicalExcuse()->exists()) {
            return redirect()
                ->route(
                    'medical-excuses.show',
                    $clinicVisit->medicalExcuse
                )
                ->with(
                    'error',
                    'A medical excuse has already been issued for this visit.'
                );
        }

        $medicalExcuse = DB::transaction(
            function () use ($request, $clinicVisit): MedicalExcuse {
                do {
                    $excuseNumber = 'ME-'
                        . now()->format('Ymd')
                        . '-'
                        . strtoupper(Str::random(6));
                } while (
                    MedicalExcuse::where(
                        'excuse_number',
                        $excuseNumber
                    )->exists()
                );

                return $clinicVisit->medicalExcuse()->create(
                    array_merge(
                        $request->validated(),
                        [
                            'issued_by' => $request->user()->id,
                            'excuse_number' => $excuseNumber,
                            'verification_code' => (string) Str::uuid(),
                            'status' => 'issued',
                            'issued_at' => now(),
                        ]
                    )
                );
            }
        );

        return redirect()
            ->route('medical-excuses.show', $medicalExcuse)
            ->with('success', 'Medical excuse issued successfully.');
    }

    public function show(
        MedicalExcuse $medicalExcuse
    ): View {
        $medicalExcuse->load([
            'clinicVisit.student',
            'issuer',
        ]);

        return view(
            'medical-excuses.show',
            compact('medicalExcuse')
        );
    }

    public function printView(
        MedicalExcuse $medicalExcuse
    ): View {
        $medicalExcuse->load([
            'clinicVisit.student',
            'issuer',
        ]);

        return view(
            'medical-excuses.print',
            compact('medicalExcuse')
        );
    }
}