<?php

namespace App\Repository;
use App\Interfaces\AppointmentInterfaces;
use App\Models\Appointment;

class AppointementRepository implements AppointmentInterfaces
{
    public function create($request)
    {
        return Appointment::create($request);
    }
    public function display($request){
        return Appointment::where("patient_id", "=",$request)->get();

    }
    public function getAppointmentPatient($request){
        return Appointment::where('medecin_id', "=",$request)->where("status", "Accept")->get();;
    }

    public function updateStatus($request){
        return $request->save();

    }
    public function deleteAppointment($request){
        return  $request->delete();
    }

    public function update($request){
        return $request->save();
    }


}