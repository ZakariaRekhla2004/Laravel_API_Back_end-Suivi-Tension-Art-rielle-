<?php

namespace App\Services;
use App\Models\Tension_Exam;
use App\Repository\TensionExamRepository;
use Auth;
use Validator;

class TensionExamService
{
    public TensionExamRepository $tension_ExamRepository;

    public function __construct(TensionExamRepository $tension_ExamRepository)
    {
        $this->tension_ExamRepository = $tension_ExamRepository;
    }
    public function get_Tension_Exam()
    {
        return $this->tension_ExamRepository->get_Tension_Exam();
    }
    public function get_Tension_ExamPatient()
    {
        // print(auth('api')->user()->getAuthIdentifier());
        return Tension_Exam::where('id', '=', auth('api')->user()->getAuthIdentifier())->latest()->take(6)->get();
        // return $this->tension_ExamRepository->get_Tension_Exam();
    }
    public function add_Tension_Exam($request)
    {
        $validator = Validator::make($request->all(), [
            // "user_id" => 'required',
            'Systolique' => 'required|numeric',
            'Diastolique' => 'required|numeric',
            'date_Examen' => 'required|date',
            'heure_Examen' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $tension_exam = new Tension_Exam();

        $tension_exam->id = auth('api')->user()->getAuthIdentifier();
        $tension_exam->Systolique = $request->input('Systolique');
        $tension_exam->Diastolique = $request->input('Diastolique');
        $tension_exam->date_Examen = $request->input('date_Examen');
        $tension_exam->heure_Examen = $request->input('heure_Examen');
        $tension_exam->Etat = 'Medium';
        if($request->input('Systolique')<100 && $request->input('Diastolique')<60  )
        {
            $tension_exam->Etat = 'Low';
            
        }
        if($request->input('Systolique')>140 && $request->input('Diastolique')>90  )
        {
            $tension_exam->Etat = 'High';
        }  
        return $this->tension_ExamRepository->add_Tension_Exam($tension_exam);
    }
    public function update_Tension_Exam($request, $id)
    {
        $tension_Exam = Tension_Exam::where('_id', '=',  $id);
        if (!$tension_Exam) {
            return response()->json(["error" => "Tension_Exam not found"], 404);
        }
        $validator = Validator::make($request->all(), [

            'Systolique' => 'required|numeric',
            'Diastolique' => 'required|numeric',
            'date_Examen' => 'required|date',
            'heure_Examen' => 'required|date_format:H:i:s',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        return $this->tension_ExamRepository->update_Tension_Exam($request, $tension_Exam);
    }
    public function delete_Tension_Exam($id)
    {
        $tension_Exam = Tension_Exam::where('_id', '=',$id)->first();
        if (!$tension_Exam) {
            return response()->json(['message' => 'Tension_Exam not found.'], 404);
        }
        return $this->tension_ExamRepository->delete_Tension_Exam($tension_Exam);
    }
    public function get_Tension_ExamId($request)
    {
        return $this->tension_ExamRepository->get_Tension_ExamID($request);
    }
}

