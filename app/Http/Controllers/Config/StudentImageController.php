<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentImageRequest;
use App\Models\CorsightQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BreadcrumbService;
use App\Services\ValidationService;
use App\Models\Student;
use App\Models\School;
use App\Models\Image;

class StudentImageController extends Controller
{

    protected $validationService;
    protected $breadcrumbService;

    public function __construct(ValidationService $validationService, BreadcrumbService $breadcrumbService)
    {
        $this->validationService = $validationService;
        $this->breadcrumbService = $breadcrumbService;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Imagens' => 'studentImage.create',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Imagens';

        return view('pages.student.studentUpload-create', compact('schools', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentImageRequest $request)
    {
        $responses = array();

        $school_id = $request->input('school_id');
        $schoolIdPath = str_pad($school_id, 6, '0', STR_PAD_LEFT);
        $class = Str::slug($request->input('class'), '_');

        $school = School::find($school_id);
        $schoolName = Str::slug($school->name, '_');
        $folderPath = "public/images/{$schoolIdPath}_{$schoolName}/$class";
        $frontPathFolder = "storage/images/{$schoolIdPath}_{$schoolName}/$class";

        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        $images = $request->file('file');
        foreach ($images as $image) {
            $originalName = $image->getClientOriginalName();
            $name = $this->getName($originalName);
            $cpf = $this->getCpf($originalName);

            if (is_object($cpf)) {
                if ($cpf->getStatusCode() == 500) {
                    $message = "Erro. O CPF é inválido.";
                    $default_photo = asset('images/logos/profile-default.jpg');
                    $student_data = array(
                        'name' => $name,
                        'school' => $school->name,
                        'class' => $class,
                        'image' => $default_photo,
                        'status' => $message,
                        'cod' => 'error'
                    );
                    array_push($responses, $student_data);
                }
            } else {
                if (is_array($cpf)) {
                    if ($cpf[2]->getStatusCode() == 400) {
                        $pathImage = $this->uploadPhoto($cpf[0], $name, $cpf[1], $image, $folderPath, $frontPathFolder, $schoolIdPath, $schoolName, $class);
                        $student_data = array(
                            'name' => $name,
                            'school' => $school->name,
                            'class' => $class,
                            'status' => 'Sucesso. Foto atualizada.',
                            'image' => $pathImage,
                            'cod' => 'success'
                        );
                        array_push($responses, $student_data);
                    }
                } else {
                    $student = Student::create([
                        'name' => $name,
                        'school_id' => $school_id,
                        'cpf' => $cpf,
                        'class' => $class
                    ]);

                    $pathImage = $this->uploadPhoto($student, $name, $cpf, $image, $folderPath, $frontPathFolder, $schoolIdPath, $schoolName, $class);
                    $student_data = array(
                        'name' => $name,
                        'school' => $school->name,
                        'class' => $class,
                        'status' => 'Sucesso. Estudante criado.',
                        'image' => $pathImage,
                        'cod' => 'success'
                    );
                    array_push($responses, $student_data);
                }
            }
        }

        return json_encode($responses);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Validate realtime request
     */
    public function validateStudentImageRequest(StoreStudentImageRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules(), $request->messages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        return response()->json(['success' => true], 200);
    }

    public function getCpf($originalName)
    {
        $pattern = '/^(.+?)\s*(?:-|_)?\s*(\d+)\.jpg$/';
        preg_match($pattern, $originalName, $matches);
        if (is_array($matches) && empty($matches)) {
            $cpf = '';
        } else {
            $cpf = $matches[2];
        }

        if (!preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$|^\d{11}$/', $cpf)) {
            return response()->json(['error' => "O CPF {$cpf} é inválido."], 500);
        } else {
            $repeated_students = Student::where('cpf', $cpf)->get();
            if ($repeated_students->isNotEmpty()) {
                return array($repeated_students->first(), $cpf, response()->json(['error' => "O aluno já está cadastrado."], 400));
            } else {
                return $cpf;
            }
        }
    }

    public function getName($originalName)
    {
        $pattern = '/^(.+?)\s*(?:-|_)?\s*(\d+)\.jpg$/';
        preg_match($pattern, $originalName, $matches);

        if (is_array($matches) && empty($matches)) {
            $pattern = '/^(.+?)(?:_(\d{11}))?\.jpg$/';
            preg_match($pattern, $originalName, $matches);
            $name = $matches[1];
        } else {
            $name = $matches[1];
        }

        return mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
    }

    public function uploadPhoto(Student $student, $name, $cpf, $image, $folderPath, $frontPathFolder, $schoolIdPath, $schoolName, $className)
    {
        $name = Str::slug(strtolower($name), '_');
        $imageName = "{$name}_{$cpf}" . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs($folderPath, $imageName);
        $frontPathFolder = $frontPathFolder . '/' . $imageName;

        $image = Image::updateOrCreate(
            [
                'module' => 'corsight_image',
                'module_id' => $student->id,
            ],
            [
                'status' => 'ACTIVE',
                'date_created' => now(),
                'date_modified' => now(),
                'order' => 1,
                'source' => '',
                'name_original' => $imageName,
                'path_original' => "images/{$schoolIdPath}_{$schoolName}/$className/$imageName",
                'extension' => $image->getClientOriginalExtension(),
            ]
        );

        $imageId = $image->id;
        $watchlistId = $student->school->watchlist_id;

        $data = [
            'pois' => [
                [
                    'display_name' => $student->name,
                    'display_img' => $imageId,
                    'poi_notes' => [
                        'free_notes' => 'CPF: ' . $student->cpf . ' | Observações: ' . $student->observations . ' | Notes: This person was created through InpexID integration at ' . now()->format('d/m/Y - H:i:s') . '.',
                    ],
                    'poi_watchlists' => [$watchlistId],
                    'poi_id' => $student->cpf,
                    'face' => [
                        'image_payload' => [
                            'img' => $imageId,
                            'detect' => true,
                            'use_detector_lms' => true,
                            'fail_on_multiple_faces' => true
                        ],
                        'force' => false,
                        'save_crop' => true,
                    ],
                ]
            ]
        ];

        $jsonData = json_encode($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON encoding error: ' . json_last_error_msg(), ['data' => $data]);
            throw new \Exception('JSON encoding error: ' . json_last_error_msg());
        }

        CorsightQueue::create([
            'status' => 'NOT_SEND',
            'module_id' => $student->id,
            'module' => 'corsight_person',
            'data' => $jsonData,
            'endpoint' => 'addPerson',
            'log' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return asset($frontPathFolder);
    }
}
