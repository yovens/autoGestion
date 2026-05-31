<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanCart;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // Commencer une location
    public function start(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        // 🚨 Tcheke si machin nan disponib nan stock oswa si estati l deja sou 0
        if ($vehicle->quantity <= 0 || $vehicle->status == 0) {
            $vehicle->quantity = 0;
            $vehicle->status = 0;
            $vehicle->save();
            
            return back()->with('error', 'Ce véhicule est indisponible pour le moment.');
        }

        $duration = $request->input('duration', 5);
        $total = $vehicle->loan_price * $duration;

        // 🔻 Diminue stock la
        $vehicle->quantity -= 1;

        if ($vehicle->quantity == 0) {
            $vehicle->status = 0;
        }

        $vehicle->save();

        // KORIJE ISIT LA: Nou mete 'pending' (En attente) pou Admin nan ka jere l
        LoanCart::create([
            'user_id'       => Auth::id(),
            'vehicle_id'    => $vehicle->id,
            'status'        => 'pending', 
            'duration_days' => $duration,
            'total_amount'  => $total,
            'start_date'    => null, // Li fenk mande, li poko kòmanse
            'end_date'      => null,
        ]);

        return redirect()->route('client.loan')
            ->with('success', "Votre demande de location a été soumise. En attente d'approbation.");
    }

    public function index()
    {
        $loans = LoanCart::where('user_id', Auth::id())
                         ->with('vehicle')
                         ->get();

        return view('client.loan', compact('loans'));
    }

    public function transactions()
    {
        // ✅ Ranplase >get() pa ->get()
        $transactions = \App\Models\Transaction::where('user_id', auth()->id())
            ->with('vehicle')
            ->get();

        return view('client.transactions', compact('transactions'));
    }

    public function renew(Request $request, $id)
    {
        $loan = LoanCart::findOrFail($id);

        // Kliyan an ka mande renouvle sèlman si lokasyon an te apwouve oswa si l ekspire
        if (!in_array($loan->status, ['approved', 'expired'])) {
            return back()->with('error', 'Vous nou pouvez pas renouveler cette location.');
        }

        $request->validate([
            'additional_days' => 'required|integer|min:1'
        ]);

        $days = $request->input('additional_days');
        $vehicle = $loan->vehicle;
        $additional_price = $vehicle->loan_price * $days;

        // Nou ogmante jou ak pri a dirèkteman
        $loan->duration_days += $days;
        $loan->total_amount += $additional_price;

        // Nou ka swa mete l an "pending" pou admin aksepte, oswa tou apwouve l otomatikman. 
        if ($loan->isExpired()) {
            $loan->start_date = \Carbon\Carbon::now();
            $loan->end_date = \Carbon\Carbon::now()->addDays($days);
        } else {
            $loan->end_date = \Carbon\Carbon::parse($loan->end_date)->addDays($days);
        }

        $loan->status = 'approved';
        $loan->save();

        return back()->with('success', "Votre location a été renouvelée de $days jours !");
    }
}