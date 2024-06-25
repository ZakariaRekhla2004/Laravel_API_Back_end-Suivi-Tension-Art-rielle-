<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\User;

use App\Models\Medicament;
use App\Repository\DossierRepository;
use Illuminate\Support\Facades\Auth;
use stdClass;

use Validator;

class DossierService
{
    public DossierRepository $dossierMRepository;

    public function __construct(DossierRepository $dossierMRepository)
    {
        $this->dossierMRepository = $dossierMRepository;
    }
    public function get_dossiers()
    {
        return $this->dossierMRepository->get_Dossiers();
    }
    public function get_DossierId($request)
    {
        return $this->dossierMRepository->get_DossierId($request);
    }
    public function create_dossier($request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            "medecin_id" => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->dossierMRepository->create_Dossier($validator);
    }
    public function add_dossier($request)
    {
        // $medecin_id = Auth::id();
        // Merge the medecin_id into the request data
        // $data = array_merge($request->all(), ['medecin_id' => $medecin_id]);
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|numeric',
            'date_naissance' => 'required|date',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'gender' => 'required|in:Male,Female,Other',
            'city' => 'required|string',
            'marital_status' => 'required|string',
            'date_of_birth' => 'required|date',
            'family_history' => 'nullable|array',
            'medications' => 'nullable|array',
            // 'medications.*.Nom' => 'required|string',
            // 'medications.*.doze' => 'nullable|string',
            // 'medications.*.Mg_g' => 'required|numeric',
            // 'medications.*.Qr' => 'required|string',
            // 'medications.*.Temps' => 'nullable|array',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        return $this->dossierMRepository->add_Dossier($validator);
    }
    public function update_Dossier($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'gender' => 'required|in:Male,Female,Other',
            'city' => 'required|string',
            'marital_status' => 'required|string',
            'date_of_birth' => 'required|date',
            'family_history' => 'nullable|array',
            'medications' => 'nullable|array',
            'medications.*.Nom' => 'required|string',
            'medications.*.doze' => 'nullable|string',
            'medications.*.Mg_g' => 'required|numeric',
            'medications.*.Qr' => 'required|string',
            'medications.*.Temps' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $dossier = Dossier::findOrFail($id);
        $dossier->weight = $request->weight;
        $dossier->height = $request->height;
        $dossier->gender = $request->gender;
        $dossier->city = $request->city;
        $dossier->marital_status = $request->marital_status;
        $dossier->date_of_birth = $request->date_of_birth;
        $dossier->family_history = $request->family_history;
        $dossier->medications = $request->medications;
        return $this->dossierMRepository->update_Dossier($dossier);
    }
    public function complete_Dossier($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'weight' => 'required|numeric',
            'height' => 'required',
            'gender' => 'required|in:Male,Female,Other',
            'city' => 'required|string',
            'marital_status' => 'required|string',
            'date_of_birth' => 'required|date',
            'family_history' => 'nullable|array',
            'personal_history' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // if ($request->id1) {
        //     $user = User::find($request->id1);
        //     $dossier = Dossier::where('patient_id', $request->id1)->get();
        // } else {
        // printf($id);
        $user = User::find($id);
        // printf($user->_id);

        // print_r("eeeeeeeeeeeee$user->_id");
        // $dossier = Dossier::where($id)->get();
        // $dossier = Dossier::find( $user->_id);

        // 

        if (!$user) {
            return true;
        }
        // $dossier = Dossier::where('patient_id',$id)->first();
        //
        $dossier = $user->dossierPatient;

        $dossier->weight = $request->weight;
        $dossier->status = 'Completed';

        $dossier->height = $request->height;
        $dossier->gender = $request->gender;
        $dossier->city = $request->city;
        $dossier->marital_status = $request->marital_status;
        $dossier->date_of_birth = $request->date_of_birth;
        $dossier->family_history = $request->family_history;
        $dossier->personal_history = $request->personal_history;
        // $dossier->medications = $request->medications;
        $user->status = 'Completed';
        $user->save();
        return $this->dossierMRepository->update_Dossier($dossier);
    }
    public function delete_Dossier($id)
    {
        return $this->dossierMRepository->delete_Dossier($id);
    }
    public function AddMedicament($request)
    {

        $validator = Validator::make($request->all(), [
            'medications' => 'nullable|array',
            'medications.*.Nom' => 'required|string',
            'medications.*.doze' => 'nullable|numeric',
            'medications.*.Mg_g' => 'required|numeric',
            'medications.*.MedicationType' => 'string',
            'medications.*.Qr' => 'string|nullable',
            'start' => 'date',
            'end' => 'date',
            'medications.*.Temps' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::find(Auth::id());
        if (!$user) {
            return true;
        }
        $dossier = $user->dossierPatient;
        if (!$dossier) {
            return response()->json(['error' => 'Dossier not found'], 404);
        }
        $currentMedications = $dossier->medications ?? [];
        $newMedications = $request->input('medications', []);

        $dossier->medications = array_merge($currentMedications, $newMedications);
        $dossier->save();

        // Return the updated dossier
        return response()->json(['message' => 'Medications added successfully', 'dossier' => $dossier], 200);

        // return $this->dossierMRepository->update_Dossier($dossier);
    }
    public function getDossier_Medicaments($id)
    {
        if($id==null)
        {
        $user = User::find(Auth::id());
        $dossier = $user->dossierPatient;
            $id=$dossier->_id;
        }
        return $this->dossierMRepository->getDossierMedications($id);
    }
    // public function getDossier_Antecedent_Familiaux($id) 
    // {
    //     return $this->dossierMRepository->getDossierMedications($id);
    // }

public function DeleteMedicament($request)
{
    $validator = Validator::make($request->all(), [
        'Nom' => 'required|string',
        // 'start' => '|date',
        // 'end' => 'required|date'

    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }
    print(auth('api')->user()->getAuthIdentifier());
    $user = User::find(auth('api')->user()->getAuthIdentifier());
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }
    $dossier = $user->dossierPatient;
    if (!$dossier) {
        return response()->json(['error' => 'Dossier not found'], 404);
    }
    

    $nom = $request->input('Nom');
    $start = $request->input('start');
    $end = $request->input('end');

    $currentMedications = $dossier->medications ?? [];

    $updatedMedications = array_filter($currentMedications, function($medication) use ($nom, $start, $end) {
        return !($medication['Nom'] === $nom && $medication['start'] === $start && $medication['end'] === $end);
    });

    $dossier->medications = array_values($updatedMedications); // Reindex array
    $dossier->save();
    return response()->json(['message' => 'Medicament deleted successfully', 'dossier' => $dossier], 200);
}
}
// $data = $request->all();
// try {
// // Instantiate your MongoDB model
// $customer = new Dossier();
// $customer->weight = $data['weight'];
// $customer->height = $data['height'];
// $customer->gender = $data['gender'];
// $customer->city = $data['city'];
// $customer->marital_status = $data['marital_status'];
// $customer->date_of_birth = $data['date_of_birth'];

// // Create a stdClass object for family_history
// $family_history = new stdClass;

// // Assign values to the nested properties using stdClass
// $family_history->Antecedent_Familiaux = $data['family_history']['Antecedent_Familiaux'];
// $family_history->{'Antecedents Presonnels'} = $data['family_history']['Antecedents Presonnels'];

// // Assign the stdClass object to the model property
// $customer->family_history = $family_history;

// // Call the save() method to save the data
// $sucess = $customer->save();