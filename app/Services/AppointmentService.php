<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\notifications;
use App\Models\User;
use App\Repository\AppointementRepository;
use Illuminate\Support\Facades\Auth;
use App\Repository\NotiRepository;
use Validator;

class AppointmentService
{
    public AppointementRepository $appointmentRepository;


    public function __construct(AppointementRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        // $this->notificationRepository = $notificationRepository;

    }
    public function getAppointments()
    {
        return $this->appointmentRepository->display(auth('api')->user()->getAuthIdentifier());
    }
    public function deleteAppointment($request)
    { 
        // print($request);

        $appointment =  Appointment::where('_id',$request)->first();
        $notifications = notifications::where('appoint_id',$request)->get();
        // print($notifications);
        $notifications->each->delete();
        $appointment->delete();
        // return$appointment;

        return $this->appointmentRepository->deleteAppointment($appointment);
    }
    public function createAppointments($request)
    {
        $validator = Validator::make($request->all(), [
            'heure' => 'required|date_format:H:i',
            'date' => 'required|date',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $appointmentExists = Appointment::where('date', $request->input('date'))
            ->where('heure', $request->input('heure'))
            ->exists();

        if ($appointmentExists) {
            return response()->json(['error' => 'Appointment slot is already booked'], 409);
        }

        $appointment = new Appointment();

        $patient = User::find(auth('api')->user()->getAuthIdentifier());
        $medecin = $patient->medecin;
        $appointment->patient_id = auth('api')->user()->getAuthIdentifier();
        $appointment->medecin_id = $medecin->_id;
        $appointment->heure = $request->input('heure');
        $appointment->date = $request->input('date');
        $appointment->status = $request->input('status');
        if (!$appointment->save()) {
            return response()->json(['message' => 'Appointment not saved'], 504);
        }

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        $notification = new notifications();
        $notification->recever = $medecin->_id;
        $notification->sender = auth('api')->user()->getAuthIdentifier();
        $notification->message = $patient->name . ' has an appointment on ' . $request->date . ' at ' . $request->heure;
        $notification->appoint_id = $appointment->id;

        $notification->save();

        return response()->json(['message' => 'Appointment created successfully', "appointment" => $appointment, "notification" => $notification], 201);
    }


    public function updateStatus($request)
    {
        // id of notification 
        $notification = notifications::findOrFail($request->id);
        $appoint = Appointment::findOrFail($notification->appoint_id);
        $notification2 = new notifications();
        $notification2->sender = $notification->recever;
        $notification2->recever = $notification->sender;
        if ($request->status == "Accept") {
            $notification2->title = 'Appointment Accepted';
            $notification2->message = 'Your appointment was accepted  on ' . $appoint->date . ' at ' . $appoint->heure;
        } else {
            $notification2->title = 'Appointment Rejected';
            $notification2->message = 'Your appointment was rejected  on ' . $appoint->date . ' at ' . $appoint->heure;
        }
        $notification2->appoint_id = $notification->appoint_id;
        $notification->read = true;
        $notification2->read = false;
        $notification->save();

        $notification2->save();
        $appoint->status = $request->input('status');
        $appoint->save();
        return response()->json(['message' => 'Appointment createdd not found', "appointment" => $appoint, "notification" => $notification2], 201);
    }

    public function displayAppoint()
    {
        $appoints = $this->appointmentRepository->getAppointmentPatient(auth('api')->user()->getAuthIdentifier());
        // print($appoints);
        $patientIds = $appoints->pluck('patient_id')->unique();
        $patients = User::whereIn('_id', $patientIds)->get()->keyBy('_id');

        $appointmentDetails = $appoints->map(function ($appointment) use ($patients) {
            return [
                'appointment_id' => $appointment->_id,
                'patient_id' => $appointment->patient_id,
                'patient_name' => $patients[$appointment->patient_id]->name ?? 'N/A', 
                'heure' => $appointment->heure,
                'date' => $appointment->date,
                'status' => $appointment->status,
                'created_at' => $appointment->created_at,
                'updated_at' => $appointment->updated_at,
            ];
        });
        $calendarEvents = $appointmentDetails->map(function ($appointment) {
            return [
                'title' => $appointment['patient_name'],
                'start' => $appointment['date'] . 'T' . $appointment['heure'],
            ];
        });
        return $calendarEvents;
    }
    

}