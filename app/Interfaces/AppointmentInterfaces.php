<?php

namespace App\Interfaces;

interface AppointmentInterfaces{
    public function create($request);
    public function display($request);
    public function getAppointmentPatient($request);

    public function updateStatus($request);

    public function update($request);





}