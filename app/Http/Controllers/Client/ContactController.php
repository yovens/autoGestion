<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Importez la Facade Mail
use App\Mail\AdminContactNotification; // Importez la classe Mailable

class ContactController extends Controller
{
    public function index(){ return view('client.contact'); }

    public function send(Request $request)
    {
        // Assurez-vous que l'utilisateur est authentifié pour récupérer son info
        if (!auth()->check()) {
            return back()->withErrors(['auth' => 'Vous devez être connecté pour envoyer un message.']);
        }
        set_time_limit(60);
        $data = $request->validate([
            'subject'=>'required|string|max:255',
            'message'=>'required|string'
        ]);
        
        // 1. Récupération des infos client
        $client = auth()->user();
        $clientName = $client->name;
        $clientEmail = $client->email;

        // 2. Adresse de l'administrateur
        $adminEmail = env('ADMIN_EMAIL', 'jocelynyouvens48@gmail.com.com');

        try {
            // 3. Envoi de l'e-mail
            Mail::to($adminEmail)->send(new AdminContactNotification(
                $data['subject'],
                $data['message'],
                $clientName,
                $clientEmail
            ));

        } catch (\Exception $e) {
            // Gérer les erreurs d'envoi d'e-mail (ex: problème de configuration SMTP)
            \Log::error('Erreur envoi contact : ' . $e->getMessage());
            return back()->withInput()->withErrors(['email' => 'Une erreur est survenue lors de l\'envoi de l\'e-mail. Veuillez réessayer plus tard.']);
        }
        
        // 4. Succès
        return back()->with('success', 'Votre message a été envoyé avec succès à l\'administrateur.');
    }
}