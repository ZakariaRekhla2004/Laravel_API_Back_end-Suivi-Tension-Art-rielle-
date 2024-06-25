<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActiviteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;
use Throwable;
use GuzzleHttp\Client;


use OpenAI\Laravel\Facades\OpenAI;

class ActiviteController extends Controller
{
    public ActiviteService $activiteService;
    public function __construct(ActiviteService $activiteService)
    {
        $this->activiteService = $activiteService;
        // $this->middleware('jwt');
    }
    public function addActivite(Request $request)
    {
        $activite = $this->activiteService->add_activite($request);
        return response()->json(['message' => 'Activite added successfully', 'activite' => $activite], 201);
    }
    public function getActivite()
    {
        $Activite = $this->activiteService->get_activite();
        return response()->json([
            'Activite' => $Activite
        ], 201);
    }
    public function getActiviteId(Request $request)
    {
        $Activite = $this->activiteService->get_activiteId($request->id);
        return response()->json([
            'Activite' => $Activite
        ], 201);
    }
    public function chatGpT(Request $request)
    {
        $content = $request->input('content');
        $maxRetries = 1;
        $retryDelay = 5500; 
        $response = null;

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer sk-proj-HPhGFbQR0Jft50ngfr2wT3BlbkFJ3GicFW2k95W0Du67uwTU',
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/completions', [
                'model' => 'gpt-3.5-turbo', 
                'prompt' => $content,
            ]);

            if ($response->status() != 429) {
                break;
            }
            Log::warning('429 Too Many Requests. Retrying in ' . $retryDelay . 'ms...');
            usleep($retryDelay * 1000);
        }

        if ($response->status() == 429) {
            return response()->json(['error' => 'Too Many Requests'], 429);
        }

        return response()->json($response->json());
    
    }
    




    public function updateActivite(Request $request)
    {
        $activite = $this->activiteService->update_activite($request, $request->id);
        return response()->json(["result" => "Activite updated successfully", "Activite" => $activite], 200);
    }
    public function deleteActivite(Request $request)
    {
        if ($this->activiteService->delete_activite($request->id)) {
            return response()->json(['message' => 'Activite deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'faild.'], 400);
        }
    }
}
