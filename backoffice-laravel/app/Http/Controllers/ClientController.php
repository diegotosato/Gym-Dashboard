<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Client;
use App\Models\ClientCredential;
use App\Models\MedicalCertificate;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::query()
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                });
            })
            ->with(['role', 'subscriptions.suspensions', 'medical_certificates'])
            ->paginate(10)
            ->withQueryString();

        return view('clients-views/dashboard', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Client::class],
            'phone' => ['required', 'string', 'unique:' . Client::class],
            'date_of_birth' => ['required', 'date'],
            'address' => ['required', 'string'],
            'tax_code' => ['required', 'string', 'unique:' . Client::class],
            'medical_certificate_start_date' => ['required', 'date'],
            'medical_certificate_expiry_date' => ['required', 'date'],
            'sub_type' => ['required', 'string', 'in:monthly,quarterly,annual'],
            'sub_start_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request) {

            $first_name = ucfirst($request->first_name);
            $last_name = ucfirst($request->last_name);

            $client = Client::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'slug' => Str::slug($first_name . '-' . $last_name),
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'address' => ucfirst($request->address),
                'tax_code' => strtoupper($request->tax_code),
                'role_id' => 3,
                'notes' => $request->notes,
            ]);

            $sub_status = null;
            $start = Carbon::parse($request->sub_start_date);

            $sub_status = $start->isFuture() ? 'pending' : 'active';

            if ($request->sub_type === 'monthly') {
                $sub_end_date = $start->copy()->addMonth();
            } elseif ($request->sub_type === 'quarterly') {
                $sub_end_date = $start->copy()->addMonths(4);
            } else {
                $sub_end_date = $start->copy()->addYear();
            }

            $subscription = Subscription::create([
                'type' => $request->sub_type,
                'start_date' => $start,
                'base_end_date' => $sub_end_date,
                'status' => $sub_status,
                'client_id' => $client->id,
            ]);

            $medical_cert = MedicalCertificate::create([
                'client_id' => $client->id,
                'medical_certificate_start_date' => $request->medical_certificate_start_date,
                'medical_certificate_expiry_date' => $request->medical_certificate_expiry_date,
            ]);

            // $username = strtolower($request->first_name) . '_' . strtolower($request->last_name);
            // $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            // $password = '';

            // for ($i = 0; $i < 8; $i++) {
            //     $lenght = strlen($characters);
            //     $index = rand(0, $lenght - 1);
            //     $password = $password . $characters[$index];
            // }

            // $data_view = [
            //     'client_id' => $client->id,
            //     'username' => $username,
            //     'password' => $password,
            // ];

            // $credentials = ClientCredential::create([
            //     'client_id' => $client->id,
            //     'username' => $username,
            //     'password' => Hash::make($password),
            // ]);

            // Mail::to($client->email)->send(new WelcomeMail($client, $data_view));
        });

        return redirect()->route('index-client')->with('success', 'Nuovo cliente registrato');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $subscriptions = Subscription::where('client_id', '=', $client->id)->orderBy('start_date', 'asc')->get();
        $medical_certificates = MedicalCertificate::where('client_id', '=', $client->id)->orderBy('medical_certificate_start_date', 'asc')->get();
        $client->load(['role']);

        return view('clients-views/show', compact('client', 'subscriptions', 'medical_certificates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $subscriptions = Subscription::where('client_id', '=', $client->id)->orderBy('start_date', 'asc')->get();
        $client->load(['role']);

        return view('clients-views/edit', compact('client', 'subscriptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        // dd($request);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'phone' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'address' => ['required', 'string'],
            'tax_code' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:monthly,quarterly,annual'],
        ]);

        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->slug = Str::slug($request->first_name . '-' . $request->last_name);
        $client->gender = $request->gender;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->date_of_birth = $request->date_of_birth;
        $client->address = $request->address;
        $client->tax_code = strtoupper($request->tax_code);
        $client->role_id = 3;
        $client->notes = $request->notes;

        $client->save();

        return redirect()->route('show-client', $client)->with('success', 'Dati cliente aggiornati');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('index-client')->with('success', 'Cliente eliminato');
    }
}
