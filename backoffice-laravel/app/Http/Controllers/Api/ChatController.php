<?php

namespace App\Http\Controllers\Api;

use App\Ai\Agents\LaravelAssistant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Ai\Enums\Lab;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        // valido la richiesta
        $request->validate([
            'message' => ['required', 'string', 'max:10000'],
            'conversation_id' => ['nullable', 'string', 'max:255'],
        ]);

        // tramite il middleware auth nella rotta, recupero l'utente autenticato in quel momento
        // $request->user() recupera l'utente tramite il guard di autenticazione utilizzato dalla richiesta
        $client = $request->user();

        // istanzio l'agente AI che gestirà la conversazione
        $agent = new LaravelAssistant();

        // se è stato fornito un ID di conversazione, tento di riprendere quella esistente... 
        if (!empty($request->conversation_id)) {
            // ... allora continuo la conversarzione
            $agent = $agent->continue(
                $request->conversation_id,
                // as: $client indica l'utente nel cui contesto viene ripresa la conversazione, secondo il contratto della libreria utilizzata
                as: $client,
            );
        } else {
            // configuro l'agente per l'utente autenticato, avviando una nuova conversazione
            $agent = $agent->forUser($client);
        }

        // immagazzino il response in una variabile
        // invio il messaggio all'agente, che lo elabora secondo le istruzioni e la configurazione previste (che fa riferimento al prompt specificato nell'agente stesso), ...
        $response = $agent->prompt(
            // ... gli passo il messaggio della richiesta
            $request->message,

            // se abbiamo scelto un provider diverso da "local", specifichiamo quale vogliamo usare tramite la Facade "Lab" (es. provider: Lab::Anthropic o Gemini o eccetera)
            // se invece usiamo il provider "local", usare le seguenti righe
            provider: 'local',
            model: config('ai.providers.local.models.text.default'),
        );

        // ritorno il JSON
        return response()->json([
            'conversation_id' => $response->conversationId,
            'message' => $response->text,
        ]);
    }

    public function history(Request $request, string $conversationId)
    {
        $client = $request->user();

        $conversation = $client->conversations()
            ->findOrFail($conversationId);

        $messages = $conversation->messages()
            ->oldest()
            ->get();

        return response()->json([
            'conversation_id' => $conversationId,
            'history' => $messages,
        ]);
    }

    public function index(Request $request)
    {
        return response()->json(
            $request->user()
                ->conversations()
                ->latest('updated_at')
                ->get()
        );
    }

    public function destroy(Request $request, string $conversationId)
    {
        $conversation = $request->user()
            ->conversations()
            ->findOrFail($conversationId);

        $conversation->delete();

        return response()->json([
            'success' => 'Conversazione eliminata con successo.',
        ]);
    }
}
