<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function translate(Request $request)
    {
        $text = $request->input('text');
        $sourceLanguage = $request->input('sourceLanguage', 'fr');
        $targetLanguage = $request->input('targetLanguage', 'en');

        $client = new Client();

        try {
            // Requête à l'API LibreTranslate
            $response = $client->post('https://libretranslate.de/translate', [
                'form_params' => [
                    'q' => $text,
                    'source' => $sourceLanguage,
                    'target' => $targetLanguage,
                    'format' => 'text',
                ]
            ]);

            $body = json_decode($response->getBody(), true);
            $translatedText = $body['translatedText'];

            return response()->json(['translatedText' => $translatedText]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }
}
