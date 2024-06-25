<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class Appointment extends Controller
{
    public AppointmentService $appointmentService;
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
        $this->middleware('jwt');
    }

    public function createAppointment(Request $request)
    {
        return $this->appointmentService->createAppointments($request);
    }
    public function getAppointment()
    {
        $dossier = $this->appointmentService->getAppointments();
        return response()->json($dossier);
  
    }
    public function deleteAppointment(Request $request)
    {
        // print($request->id);
        $dossier = $this->appointmentService->deleteAppointment($request->id);
        return response()->json($dossier);
  
    }
    public function getAppointmentMedecin()
    {
        $dossier = $this->appointmentService->displayAppoint();
        return response()->json( $dossier
        , 200);
  
    }
    public function updateStatus(Request $request){
        return $this->appointmentService->updateStatus($request);
    }

}
