<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Activite;
use App\Models\notifications;

use App\Repository\DossierRepository;
use App\Services\DossierService;
use Illuminate\Http\Request;
use Psy\Util\Json;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    public DossierService $dossierService;
    public DossierRepository $dossierRepository;

    public function __construct(DossierService $dossierService, DossierRepository $dossierRepository)
    {
        $this->dossierService = $dossierService;
        $this->dossierRepository = $dossierRepository;

        $this->middleware('jwt');
    }

    public function Dashboard(Request $request)
    {
        $medecinId = auth('api')->user()->getAuthIdentifier();
        $totalPatients = User::where('id_medecin', $medecinId)->count();

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $patientsThisMonth = User::where('id_medecin', $medecinId)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Number of patients added in the previous month
        $previousMonth = now()->subMonth()->month;
        $previousMonthYear = now()->subMonth()->year;
        $patientsLastMonth = User::where('id_medecin', $medecinId)
            ->whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousMonthYear)
            ->count();

        // Calculate the percentage change from last month to this month
        $percentageChange = $patientsLastMonth > 0
            ? (($patientsThisMonth - $patientsLastMonth) / $patientsLastMonth) * 100
            : ($patientsThisMonth > 0 ? 100 : 0);

        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->toTimeString();
        //    rendez vous   
        $medecin = User::where('_id', $medecinId)->first();
        if ($medecin) {
            $appointments = $medecin->medecinAppointments()->where('status', "=", 'Accept')->whereDate('date', $today)->count();
            $pass = $medecin->medecinAppointments()->where('status', "=", 'Accept')->whereDate('date', $today)->whereTime('heure', '<=', $currentTime)
                ->count();
        } else {
            print ('Medecin not found');
        }
     
        $notifications = notifications::where('recever', $medecinId)->count();
        $notificationsRead = notifications::where('recever', $medecinId)->where('read', true)->count();

        // print($notifications);
        // print($notificationsRead);
        return response()->json([
            'message' => 'Dashboard statistics retrieved successfully',
            'totalPatients' => $totalPatients,
            'patientsThisMonth' => $patientsThisMonth,
            'percentageChange' => round($percentageChange, 2),
            'Appointments' => ['nbr' => $appointments, 'pass' => $pass],
            'notifications' => ['notification' => $notifications, 'read' => $notificationsRead],
            'status' => 200,
        ], 200);
    }
    public function moreInfo(Request $request)
    {
        // print_r($request);
        if ($request->id != null) {
            $id = $request->id;
        } else {
            $id = auth('api')->user()->getAuthIdentifier();
        }
        $userinfo = User::find($id);
        if (!$userinfo) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $dossier = $userinfo->dossierPatient;

        if (!$dossier) {
            return response()->json(['message' => 'Dossier not found'], 404);
        }
        $medicaments = $this->dossierRepository->getDossierMedications($dossier->id);
        $activites = Activite::where('patient_id', $id)->latest()->take(3)->get();
        $exams = $userinfo->exams()->latest()->take(3)->get();
        $user1 = [
            "user" => $userinfo,
            "activite" => $activites,
            "exams" => $exams
        ];
        return response()->json(
            $user1
        );
    }


    //
}
