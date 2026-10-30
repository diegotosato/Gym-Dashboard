<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\MedicalCertificate;
use Illuminate\Http\Request;

class MedicalCertificateController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $request->validate([
            'medical_certificate_start_date' => ['required', 'date'],
            'medical_certificate_expiry_date' => ['required', 'date'],
        ]);

        MedicalCertificate::create([
            'client_id' => $client->id,
            'medical_certificate_start_date' => $request->medical_certificate_start_date,
            'medical_certificate_expiry_date' => $request->medical_certificate_expiry_date,
        ]);

        return redirect()->route('show-medical-cert', $client)->with('success', 'Nuova consegna registrata');
    }

    public function show(Client $client)
    {
        $meds = MedicalCertificate::where('client_id', '=', $client->id)->orderBy('medical_certificate_expiry_date')->get();

        return view('medicals-views/show', compact('meds', 'client'));
    }

    public function edit(MedicalCertificate $medical_certificate)
    {
        $client = $medical_certificate->client;

        return view('medicals-views/edit', compact('medical_certificate', 'client'));
    }

    public function update(Request $request, MedicalCertificate $medical_certificate)
    {
        $request->validate([
            'medical_certificate_start_date' => ['required', 'date'],
            'medical_certificate_expiry_date' => ['required', 'date'],
        ], [
            'medical_certificate_start_date.required' => 'La data di rilascio è obbligatoria',
            'medical_certificate_expiry_date.required' => 'La data di scadenza è obbligatoria',
        ]);

        $client = $medical_certificate->client;

        $medical_certificate->client_id = $medical_certificate->client_id;
        $medical_certificate->medical_certificate_start_date = $request->medical_certificate_start_date;
        $medical_certificate->medical_certificate_expiry_date = $request->medical_certificate_expiry_date;
        $medical_certificate->save();

        return redirect()->route('show-medical-cert', $client)->with('success', 'Certificato medico aggiornato');
    }

    public function destroy(MedicalCertificate $medical_certificate)
    {
        $client = $medical_certificate->client;

        $medical_certificate->delete();

        return redirect()->route('show-medical-cert', $client)->with('success', 'Certificato medico eliminato');
    }
}
