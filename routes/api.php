<?php

use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\Appointment;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\MedicamentController;
use App\Http\Controllers\Notifications;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\TensionExamController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/checkCredentials', [AuthController::class, 'checkCredentias']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/forgetPassword', [AuthController::class, 'forgetPassword']);
    Route::get('/getUsers', [AuthController::class, 'getUsers']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::delete('/deletePatient', [AuthController::class, 'deltePatient']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'Chat'
], function ($router) {
    // Route::post('/send-message', [ChatController::class, 'message']);
    Route::post('messages', [ChatController::class, 'message']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'dossier'
], function ($router) {
    Route::post('/addDossier', [DossierController::class, 'addDossier']);
    Route::post('/completeDossier', [DossierController::class, 'complete_Dossier']);
    Route::post('/AddMedicament', [DossierController::class, 'AddMedicament']);


    Route::get('/getDossier', [DossierController::class, 'getDossier']);
    Route::get('/getDossierId', [DossierController::class, 'getDossierId']);
    Route::get('/getDossier_Medicaments', [DossierController::class, 'getDossier_Medicaments']);
    Route::put('/updateDossier', [DossierController::class, 'updateDossier']);
    Route::delete('/deleteDossier', [DossierController::class, 'deleteDossier']);
    Route::post('/deleteMedicament', [DossierController::class, 'deleteMedicament']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'Statistique'
], function ($router) {
    Route::get('/dashboard', [StatistiqueController::class, 'Dashboard']);
    Route::get('/moreInfo', [StatistiqueController::class, 'moreInfo']);


});
Route::group([
    'middleware' => 'api',
    'prefix' => 'apppoint'
], function ($router) {
    Route::get('/getAppointment', [Appointment::class, 'getAppointment']);
    Route::delete('/deleteAppointment', [Appointment::class, 'deleteAppointment']);

    Route::get('/getAppointmentMedecin', [Appointment::class, 'getAppointmentMedecin']);

    Route::post('/createAppointment', [Appointment::class, 'createAppointment']);
    Route::put('/updateStatus', [Appointment::class, 'updateStatus']);

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'Notifications'
], function ($router) {
    Route::get('/getNotifications', [Notifications::class, 'getNotifications']);
    Route::get('/updateNotification', [Notifications::class, 'updateNotification']);

});



Route::group([
    'middleware' => 'api',
    'prefix' => 'medicament'
], function ($router) {
    Route::post('/addMedicament', [MedicamentController::class, 'addMedicament']);
    Route::get('/getMedicament', [MedicamentController::class, 'getMedicament']);
    Route::get('/getMedicamentid', [MedicamentController::class, 'getMedicamentID']);
    Route::put('/updateMedicament', [MedicamentController::class, 'updateMedicament']);
    Route::delete('/deleteMedicament', [MedicamentController::class, 'deleteMedicament']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'TensionExam'
], function ($router) {
    Route::post('/addTension_Exam', [TensionExamController::class, 'addTensionExam']);
    Route::get('/getTensionExam', [TensionExamController::class, 'getTensionExam']);
    Route::get('/getTension_Examid', [TensionExamController::class, 'getTensionExamID']);
    Route::put('/updateTension_Exam', [TensionExamController::class, 'updateTensionExam']);
    Route::delete('/deleteTension_Exam', [TensionExamController::class, 'deleteTensionExam']);
    Route::post('/ocr', [TensionExamController::class, 'Ocr']);

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'Activite'
], function ($router) {
    Route::post('/addActivite', [ActiviteController::class, 'addactivite']);
    Route::get('/getActivite', [ActiviteController::class, 'getactivite']);
    Route::get('/getActiviteid', [ActiviteController::class, 'getactiviteID']);
    Route::put('/updateActivite', [ActiviteController::class, 'updateactivite']);
    Route::delete('/deleteActivite', [ActiviteController::class, 'deleteactivite']);

    Route::post('/chatGpT', [ActiviteController::class, 'chatGpT']);

});