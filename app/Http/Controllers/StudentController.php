<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use App\Models\CorsightQueue;
use App\Models\Student;
use App\Models\School;
use App\Models\Image;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Middleware\MenuMiddleware;

class StudentController extends Controller
{
    protected $validationService;
    protected $breadcrumbService;

    public function __construct(ValidationService $validationService, BreadcrumbService $breadcrumbService)
    {
        $this->validationService = $validationService;
        $this->breadcrumbService = $breadcrumbService;
        $this->middleware(MenuMiddleware::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $columns = ['id', 'created_at', 'name', 'cpf'];

        if ($request->ajax()) {
            $length = $request->input('length', 10);
            $start = $request->input('start', 0);
            $orderIndex = $request->input('order.0.column');
            $order = $columns[$orderIndex] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'asc';

            $query = Student::select('id', 'created_at', 'name', 'cpf');

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%");
                });
            }

            $totalFiltered = $query->count();
            $data = $query->orderBy($order, $dir)
                ->offset($start)
                ->limit($length)
                ->get();
            $totalData = Student::count();

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ]);
        }

        return view('pages.student.student-list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Alunos' => 'student.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Novo Aluno';

        return view('pages.student.student-create', compact('schools', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        // Cria o aluno
        $student = Student::create([
            'school_id' => $request->input('school_id'),
            'name' => $request->input('name'),
            'cpf' => $request->input('cpf'),
            'date_of_birth' => $request->input('date_of_birth'),
            'enrollment' => $request->input('enrollment'),
            'grade' => $request->input('grade'),
            'class' => $request->input('class'),
            'cpf_responsible' => $request->input('cpf_responsible'),
            'responsible_name' => $request->input('responsible_name'),
            'responsible_phone' => $request->input('responsible_phone'),
            'responsible_email' => $request->input('responsible_email'),
            'cep' => $request->input('cep'),
            'address' => $request->input('address'),
            'number' => $request->input('number'),
            'complement' => $request->input('complement'),
            'district' => $request->input('district'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'observations' => $request->input('observations'),
        ]);

        // Upload da foto, se fornecida
        if ($request->hasFile('photo')) {
            $this->uploadPhoto($request, $student);
        }

        return redirect()->route('student.index')->with('success', 'Aluno criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::findOrFail($id);
        $studentImage = Image::where('module_id', $student->id)->where('module', 'corsight_image')->first();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Alunos' => 'student.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Detalhes do Aluno';

        return view('pages.student.student-show', compact('student', 'breadcrumbs', 'pageTitle', 'studentImage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schools = School::all();
        $student = Student::findOrFail($id);

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Alunos' => 'student.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Editar Aluno';

        return view('pages.student.student-edit', compact('student', 'schools', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        $student = Student::findOrFail($id);

        $validatedData = $request->validated();
        $student->update($validatedData);

        if ($request->hasFile('photo')) {
            $this->uploadPhoto($request, $student);
        }

        return redirect()->route('student.index')->with('success', 'Aluno atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            return response()->json(['success', 'Aluno excluído com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao remover escola: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Validate realtime request
     */
    public function validateStudentRequest(StoreStudentRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules(), $request->messages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toArray()], 422);
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * Upload image
     */
    public function uploadPhoto(Request $request, Student $student)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $schoolName = Str::slug($student->school->name, '_');
        $schoolId = str_pad($student->school->id, 6, '0', STR_PAD_LEFT);
        $schoolName = $schoolId . '_' . $schoolName;
        $className = Str::slug($student->class, '_');
        $studentName = Str::slug($student->name, '_');
        $studentCpf = $student->cpf;

        $folderPath = "public/images/$schoolName/$className/";
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        $imageName = "{$studentName}_{$studentCpf}." . $request->photo->extension();
        $path = $request->photo->storeAs($folderPath, $imageName);

        $image = Image::updateOrCreate(
            [
                'module' => 'corsight_image',
                'module_id' => $student->id,
            ],
            [
                'status' => 1,
                'date_created' => now(),
                'date_modified' => now(),
                'order' => 1,
                'source' => '',
                'name_original' => $imageName,
                'path_original' => "images/$schoolName/$className/$imageName",
                'extension' => $request->photo->extension(),
            ]
        );

        $imageId = $image->id;
        $watchlistId = $student->school->watchlist_id;

        $imageContent = Storage::get($path);
        $base64Image = base64_encode($imageContent);


        $callingMethod = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];

        if ($callingMethod === 'store') {
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
        } elseif ($callingMethod === 'update') {
            $data['id'] = $student->cpf;
            $data['faces'] = array(
                array(
                    'image_payload' => array(
                        "img" => $imageId,
                        "detect" => true,
                        "use_detector_lms" => true,
                        "fail_on_multiple_faces" => true
                    ),
                    "force" => false,
                    "save_crop" => true,
                ),
            );

            $jsonData = json_encode($data);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON encoding error: ' . json_last_error_msg(), ['data' => $data]);
                throw new \Exception('JSON encoding error: ' . json_last_error_msg());
            }

            CorsightQueue::create([
                'status' => 'NOT_SEND',
                'module_id' => $student->id,
                'module' => 'corsight_person_face',
                'data' => $jsonData,
                'endpoint' => 'addFaces',
                'log' => '',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}