<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

use App\Models\Client;
use App\Models\Otp;

use Vonage\Client as ClientVonage;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class SecurityController extends Controller
{
    public function showResetForm(Request $request)
    {
        // Vérifier si l'utilisateur est autorisé et est un client
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')
                ->withErrors(['error' => 'Vous devez être connecté pour modifier votre mot de passe.']);
        }

        return view('reset_password');
    }

    public function resetPassword(Request $request)
    {
        // Validation des champs
        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')
                ->withErrors(['error' => 'Vous devez être connecté pour modifier votre mot de passe.']);
        }

        // Récupérer l’utilisateur actuel
        $client = Client::find($sessionUser['id']);

        if (!$client) {
            return redirect()->route('myaccount')
                ->withErrors(['error' => 'Utilisateur introuvable.']);
        }

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $client->motdepasseuser)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Mettre à jour le mot de passe
        $client->motdepasseuser = Hash::make($request->new_password);
        $client->save();

        return redirect()->route('myaccount')
            ->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }










    public function activateMFA(Request $request)
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Vous devez être connecté pour accéder à cette page.']);
        }

        $user = Client::find($sessionUser['id']);

        if (!$user) {
            return back()->withErrors(['error' => 'Utilisateur introuvable.']);
        }

        // Vérifier si la MFA est déjà activée
        if ($user->mfa_activee) {
            return back()->with('error', 'La MFA est déjà activée sur votre compte.');
        }

        // Activer la MFA
        $user->mfa_activee = true;
        $user->save();

        return back()->with('success', 'MFA activée avec succès.');
    }















    private function sendSmsWithNexmo($recipientPhone, $message)
    {
        $basic  = new Basic("e4cf7363", "QuSONeu3MW7FEEG9");
        $client = new ClientVonage($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($recipientPhone, "Uber", $message)
        );

        /* dd($response); */

        $message = $response->current();

        if ($message->getStatus() == 0) {
            echo "The message was sent successfully\n";
        } else {
            echo "The message failed with status: " . $message->getStatus() . "\n";
        }
    }

    public function sendOtp(Request $request)
    {
        $sessionUser = $request->session()->get('mfa_user');

        if (!$sessionUser) {
            return redirect()->route('login')
                ->withErrors(['Session expirée. Veuillez vous reconnecter.']);
        }

        $user = Client::find($sessionUser['id']);

        /* dd($user); */

        if (!$user) {
            return back()->withErrors(['Utilisateur introuvable.']);
        }

        $existingOtp = Otp::where('idclient', $user->idclient)
            ->where('utilise', false)
            ->where('dateexpiration', '>', now())
            ->first();

        if ($existingOtp) {
            return back()->withErrors(['Un OTP actif existe déjà. Vérifiez votre SMS.']);
        }

        $otpCode = mt_rand(100000, 999999);

        $dateGeneration = now()->addHour(1);
        $dateExpiration = (clone $dateGeneration)->addMinutes(5);

        Otp::create([
            'idclient'       => $user->idclient,
            'codeotp'        => $otpCode,
            'dategeneration' => $dateGeneration,
            'dateexpiration' => $dateExpiration,
            'utilise'        => false,
        ]);

        $message = "Votre code OTP est : $otpCode. Il expire dans 5 minutes.";

        try {
            $formattedPhone = $this->formatPhoneNumber($user->telephone);
            $this->sendSmsWithNexmo($formattedPhone, $message);
        } catch (\Exception $e) {
            return back()->withErrors(['Erreur d\'envoi : ' . $e->getMessage()]);
        }

        return back()->with('success', 'Un code OTP a été envoyé à votre téléphone.');
    }

    private function formatPhoneNumber($phoneNumber, $countryCode = '33')
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (substr($phoneNumber, 0, 1) == '0') {
            $phoneNumber = $countryCode . substr($phoneNumber, 1);
        }

        return '+' . $phoneNumber;
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'codeotp' => 'required|digits:6',
        ]);

        $mfaUser = $request->session()->get('mfa_user');

        if (!$mfaUser) {
            return redirect()->route('login')
                ->withErrors(['Session expirée. Veuillez vous reconnecter.']);
        }

        $user = Client::find($mfaUser['id']);

        /* dd($user); */

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['Utilisateur introuvable.']);
        }

        // Verify OTP
        $otp = Otp::where('idclient', $user->idclient)
            ->where('codeotp', $request->codeotp)
            ->where('utilise', false)
            ->where('dateexpiration', '>', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['codeotp' => 'Code OTP invalide ou expiré.']);
        }

        $otp->update(['utilise' => true]);

        return redirect()->route('myaccount')
            ->with('success', 'Connexion réussie.');
    }

    public function resendOtp(Request $request)
    {
        $mfaUser = $request->session()->get('mfa_user');

        if (!$mfaUser) {
            return redirect()->route('login')
                ->withErrors(['Session expirée. Veuillez vous reconnecter.']);
        }

        $user = Client::find($mfaUser['id']);

        if (!$user) {
            return back()->withErrors(['Utilisateur introuvable.']);
        }

        // Generate a new OTP
        $otpCode = mt_rand(100000, 999999);

        Otp::create([
            'idclient'       => $user->idclient,
            'codeotp'        => $otpCode,
            'dategeneration' => now(),
            'dateexpiration' => now()->addMinutes(5),
            'utilise'        => false,
        ]);

        $message = "Votre nouveau code OTP est : $otpCode. Il expire dans 5 minutes.";

        try {
            $this->sendSmsWithNexmo($user->telephone, $message);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur d\'envoi SMS : ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Un nouveau SMS avec le code OTP a été envoyé.']);
    }
}
