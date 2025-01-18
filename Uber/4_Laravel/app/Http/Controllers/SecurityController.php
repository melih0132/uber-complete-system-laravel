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
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')
                ->withErrors(['error' => 'Vous devez être connecté pour modifier votre mot de passe.']);
        }

        return view('password.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $sessionUser = $request->session()->get('user');

        if (!$sessionUser || $sessionUser['role'] !== 'client') {
            return redirect()->route('login')
                ->withErrors(['error' => 'Vous devez être connecté pour modifier votre mot de passe.']);
        }

        $client = Client::find($sessionUser['id']);

        if (!$client) {
            return redirect()->route('myaccount')
                ->withErrors(['error' => 'Utilisateur introuvable.']);
        }

        if (!Hash::check($request->current_password, $client->motdepasseuser)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $client->motdepasseuser = Hash::make($request->new_password);
        $client->save();

        return redirect()->route('myaccount')
            ->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }

    public function forgetPassword()
    {
        return view('password.forget-password');
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

        if ($user->mfa_activee) {
            return back()->with('error', 'La MFA est déjà activée sur votre compte.');
        }

        $user->mfa_activee = true;
        $user->save();

        return back()->with('success', 'MFA activée avec succès.');
    }















    private function sendSmsWithNexmo($recipientPhone, $message)
    {
        $basic = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
        $client = new ClientVonage($basic);

        $cleanMessage = preg_replace('/\[.*?\]$/', '', $message);

        try {
            $response = $client->sms()->send(
                new SMS($recipientPhone, "Uber", $cleanMessage)
            );

            $sentMessage = $response->current();

            if ($sentMessage->getStatus() == 0) {
                echo "The message was sent successfully.\n";
            } else {
                echo "The message failed with status: " . $sentMessage->getStatus() . "\n";
            }
        } catch (\Exception $e) {
            echo "Error sending SMS: " . $e->getMessage() . "\n";
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

        $reelNow = now();
        $dateGeneration = $reelNow;
        $dateExpiration = (clone $dateGeneration)->addMinutes(5);

        Otp::create([
            'idclient'       => $user->idclient,
            'codeotp'        => $otpCode,
            'dategeneration' => $dateGeneration,
            'dateexpiration' => $dateExpiration,
            'utilise'        => false,
        ]);

        $message = "Votre code de vérification est : $otpCode.";

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

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['Utilisateur introuvable.']);
        }

        $otp = Otp::where('idclient', $user->idclient)
            ->where('codeotp', $request->codeotp)
            ->where('utilise', false)
            ->where('dateexpiration', '>', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['codeotp' => 'Code OTP invalide ou expiré.']);
        }

        $otp->update(['utilise' => true]);

        $request->session()->put('user', [
            'id' => $user->idclient,
            'role' => $mfaUser['role'],
            'typeclient' => $user->typeclient,
        ]);

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

        $reelNow = now();

        // Generate a new OTP
        $otpCode = mt_rand(100000, 999999);

        Otp::create([
            'idclient'       => $user->idclient,
            'codeotp'        => $otpCode,
            'dategeneration' => $reelNow,
            'dateexpiration' => $reelNow->addMinutes(5),
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
