<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DossierService;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    public DossierService $dossierService;
    public function __construct(DossierService $dossierService)
    {
        $this->dossierService = $dossierService;
        $this->middleware('jwt');
    }
    public function createDossier(Request $request)
    {
        $dossier = $this->dossierService->create_dossier($request);
        return response()->json(['message' => 'Dossier added successfully', "Dossier" => $dossier], 201);
    }
    
    public function addDossier(Request $request)
    {
        $dossier = $this->dossierService->add_dossier($request);
        return response()->json(['message' => 'Dossier added successfully', "Dossier" => $dossier], 201);
    }

    public function complete_Dossier(Request $request)
    {
        // $id=auth('api')->user()->getAuthIdentifier();
        $dossier = $this->dossierService->complete_Dossier($request,$request->id);
        return response()->json(['message' => 'Dossier completed successfully', "Dossier" => $dossier, "status" =>  201], 201);
    }
    public function getDossier()
    {
        $dossier = $this->dossierService->get_dossiers();
        return response()->json([
            'dossier' => $dossier
        ], 200);
    }
    public function getDossierId(Request $request)
    {
        $dossier = $this->dossierService->get_DossierId($request->id);
        return response()->json([
            'dossier' => $dossier
        ], 200);
    }
    public function updateDossier(Request $request)
    {
        $tension_Exam =$this->dossierService->update_Dossier($request,$request->id);
        return response()->json(["result" => "dossier updated successfully", "dossier" => $tension_Exam], 200);

    }
    public function AddMedicament(Request $request)
    {
        // $tension_Exam =$this->dossierService->AddMedicament($request);
        return response()->json(["result" => "dossier updated successfully", $this->dossierService->AddMedicament($request)], 201);

    }
    public function deleteDossier(Request $request)
    {
        if ($this->dossierService->delete_Dossier($request->id)) {
            return response()->json(['message' => 'Dossier deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'faild.'], 400);
        }
    }
    public function deleteMedicament(Request $request)
    {
        print('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
            return $this->dossierService->DeleteMedicament($request);
    }
    public function getDossier_Medicaments(Request $request)
    {
        
        $medicationNames= $this->dossierService->getDossier_Medicaments($request->id);
        return response()->json(['medications' => $medicationNames], 200);
    }
}
