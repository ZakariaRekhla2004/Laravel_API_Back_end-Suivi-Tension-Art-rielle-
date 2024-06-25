<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TensionExamService;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use robertogallea\LaravelPython\Services\LaravelPython;

class TensionExamController extends Controller
{
   
    public TensionExamService $TensionExamService;
    public function __construct(TensionExamService $TensionExamService)
    {
        $this->TensionExamService = $TensionExamService;
        $this->middleware('jwt');
    }
    public function addTensionExam(Request $request)
    {
        // if($this->TensionExamService->add_Tension_Exam($request)){;
        return response()->json(['message' => 'Tension_Exam added successfully' , "aaa" => $this->TensionExamService->add_Tension_Exam($request) ], 201);
        //  return response()->json(['message' => 'Tension_Exam added successfully'], 401);}
    }
    public function getTensionExam()
    {
        $TensionExam = $this->TensionExamService->get_Tension_ExamPatient();
        return response()->json([
            'TensionExam' => $TensionExam
        ], 200);
    }
    public function getTensionExamId(Request $request)
    {
        $TensionExam = $this->TensionExamService->get_Tension_ExamId($request->id);
        if (!$TensionExam) {
            return response()->json(["error" => "Tension_Exam not found"], 403);
        }
        return response()->json([
            'TensionExam' => $TensionExam
        ], 200);
    }
    public function updateTensionExam(Request $request)
    {
        $tension_Exam = $this->TensionExamService->update_Tension_Exam($request, $request->id);
         return response()->json(["result" => "Tension_Exam updated successfully", $tension_Exam], 201);
    }
    public function deleteTensionExam(Request $request)
    {
        $this->TensionExamService->delete_Tension_Exam($request->id);
        return response()->json(['message' => 'Tension_Exam deleted successfully.'], 200); 
    }
    public function Ocr(Request $request)
    {
        $request->validate([
            'file' => 'required|string', 
        ]);

        // Decode the base64 file
        $fileData = $request->input('file');
        $fileData = base64_decode($fileData);

       
        $fileName = 'uploaded_image.jpg';
        $filePath = storage_path('app/uploads/' . $fileName);

        if (!is_dir(storage_path('app/uploads'))) {
            mkdir(storage_path('app/uploads'), 0777, true);
        }

        file_put_contents($filePath, $fileData);

        $service = new LaravelPython();

        $parameters = [$filePath];

        $result = $service->run(base_path('/model.py'), $parameters);

        unlink($filePath);

        $response = json_decode($result, true);

        $generatedText = $response[0]['generated_text'];
        preg_match('/<s_price>(.*?)<\/s_price>/', $generatedText, $priceMatches);
        preg_match('/<s_total_price>(.*?)<\/s_total_price>/', $generatedText, $totalPriceMatches);

        $price = (int)($priceMatches[1]) ?? null;
        $totalPrice = (int)$totalPriceMatches[1] ?? null;

        
        return response()->json([
            'gen' => $generatedText,
            'Systolique' => $price,
            'Diastolique' => $totalPrice,
        ],200);
    }
}
