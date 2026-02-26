<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintController extends Controller
{
    public function downloadPrescription($prescriptionsId)
    {

        $diagnosisId = Prescription::find($prescriptionsId)->diagnosis_id;
        $diagnosis = Diagnosis::with(['visit.patient', 'prescriptions.medicine'])->findOrFail($diagnosisId);

        // This refers to the blade file we will create next
        $pdf = Pdf::loadView('prints.prescription', compact('diagnosis'))->setOption([
            'isRemoteEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'dejavu sans'
        ]);

        // 'stream' opens it in the browser, 'download' forces a file download
        return $pdf->stream('Prescription-' . $diagnosis->visit->patient->name . '.pdf');
    }
}
