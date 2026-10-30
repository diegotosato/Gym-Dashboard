<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Client;
use App\Models\ClientCredential;
use App\Models\MedicalCertificate;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class NewCredentialController extends Controller
{
    public function send_new_credentials(Client $client)
    {
        $credentials = ClientCredential::where('client_id', $client->id)->first();

        $username = strtolower($client->first_name) . '_' . strtolower($client->last_name);
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $newPassword = '';

        for ($i = 0; $i < 8; $i++) {
            $lenght = strlen($characters);
            $index = rand(0, $lenght - 1);
            $newPassword = $newPassword . $characters[$index];
        }

        $credentials->password = Hash::make($newPassword);
        $credentials->save();

        $data_view = [
            'client_id' => $client->id,
            'username' => $username,
            'password' => $newPassword,
        ];

        Mail::to($client->email)->send(new WelcomeMail($client, $data_view));

        $subscriptions = Subscription::where('client_id', '=', $client->id)->orderBy('start_date', 'asc')->get();
        $medical_certificates = MedicalCertificate::where('client_id', '=', $client->id)->orderBy('medical_certificate_start_date', 'asc')->get();
        $client->load(['role']);

        return redirect()->route('show-client', compact('client', 'subscriptions', 'medical_certificates'))->with('success', 'Nuove credenziali inviate');
    }
}
