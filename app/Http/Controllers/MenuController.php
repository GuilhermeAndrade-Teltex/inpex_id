<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidationService;
use App\Services\BreadcrumbService;
use App\Http\Middleware\MenuMiddleware;
use App\Models\Menus1;
use App\Models\Menus2;
use App\Models\Menus3;

class MenuController extends Controller
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
    public function index()
    {
        $menus1 = Menus1::with([
            'menus2' => function ($query) {
                $query->orderBy('name');
                $query->with([
                    'menus3' => function ($query) {
                        $query->orderBy('name');
                    }
                ]);
            }
        ])->orderBy('name')->get();

        $breadcrumbsItems = [
            'Home' => 'dashboard',
            'Menus' => 'menus.index',
        ];

        $breadcrumbs = $this->breadcrumbService->generateBreadcrumbs($breadcrumbsItems);
        $pageTitle = 'Lista de Menus';

        return view('pages.config.menus-list', compact('menus1', 'breadcrumbs', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.config.menus-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menus1.*.name' => 'required',
            'menus1.*.url' => 'nullable|url',
            'menus1.*.icon' => 'nullable',
            'menus1.*.position' => 'nullable|integer',

            // Validação condicional para menus2
            'menus1.*.menus2.*.name' => 'sometimes|required',
            'menus1.*.menus2.*.url' => 'sometimes|nullable|url',
            'menus1.*.menus2.*.icon' => 'sometimes|nullable',
            'menus1.*.menus2.*.position' => 'sometimes|nullable|integer',
            'menus1.*.menus2.*.iframe' => 'sometimes|nullable|integer',

            // Validação condicional para menus3
            'menus1.*.menus2.*.menus3.*.name' => 'sometimes|required',
            'menus1.*.menus2.*.menus3.*.url' => 'sometimes|nullable|url',
            'menus1.*.menus2.*.menus3.*.icon' => 'sometimes|nullable',
            'menus1.*.menus2.*.menus3.*.position' => 'sometimes|nullable|integer',
            'menus1.*.menus2.*.menus3.*.dashboard' => 'sometimes|nullable|integer',
            'menus1.*.menus2.*.menus3.*.method' => 'sometimes|nullable|integer',
        ]);

        // Salvar Menu1
        foreach ($request->menus1 as $menu1Data) {
            $menu1 = new Menus1($menu1Data);
            $menu1->created_by = Auth::id();
            $menu1->modified_by = Auth::id();
            $menu1->save();

            // Salvar Menus2 associados
            if (isset($menu1Data['menus2'])) {
                foreach ($menu1Data['menus2'] as $menu2Data) {
                    $menu2 = new Menus2($menu2Data);
                    $menu2->menus1_id = $menu1->id;
                    $menu2->created_by = Auth::id();
                    $menu2->modified_by = Auth::id();
                    $menu2->save();

                    // Salvar Menus3 associados
                    if (isset($menu2Data['menus3'])) {
                        foreach ($menu2Data['menus3'] as $menu3Data) {
                            $menu3 = new Menus3($menu3Data);
                            $menu3->menus2_id = $menu2->id;
                            $menu3->created_by = Auth::id();
                            $menu3->modified_by = Auth::id();
                            $menu3->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('menus.index')->with('success', 'Menus criados com sucesso!');
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
    public function edit(Menus1 $menu1)
    {
        $menu1->load('menus2.menus3'); // Eager loading para otimizar as consultas
        return view('pages.config.menus-edit', compact('menu1'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menus1 $menu1)
    {
        $request->validate([
            'menus1.*.name' => 'required',
            'menus1.*.url' => 'nullable|url',
            'menus1.*.icon' => 'nullable',
            'menus1.*.position' => 'nullable|integer',

            // Validação condicional para menus2
            'menus1.*.menus2.*.name' => 'sometimes|required',
            'menus1.*.menus2.*.url' => 'sometimes|nullable|url',
            'menus1.*.menus2.*.icon' => 'sometimes|nullable',
            'menus1.*.menus2.*.position' => 'sometimes|nullable|integer',
            'menus1.*.menus2.*.iframe' => 'sometimes|nullable|integer',

            // Validação condicional para menus3
            'menus1.*.menus2.*.menus3.*.name' => 'sometimes|required',
            'menus1.*.menus2.*.menus3.*.url' => 'sometimes|nullable|url',
            'menus1.*.menus2.*.menus3.*.icon' => 'sometimes|nullable',
            'menus1.*.menus2.*.menus3.*.position' => 'sometimes|nullable|integer',
            'menus1.*.menus2.*.menus3.*.dashboard' => 'sometimes|nullable|integer',
            'menus1.*.menus2.*.menus3.*.method' => 'sometimes|nullable|integer',
        ]);

        // Atualizar Menu1
        $menu1->update($request->input('menu1'));

        // Atualizar/Criar/Excluir Menus2 e Menus3
        $this->syncSubmenus($request->input('menu1.menus2', []), $menu1);

        return redirect()->route('menus.index')->with('success', 'Menus atualizados com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function syncSubmenus($menu2Data, $menu1)
    {
        $menu2Ids = [];

        foreach ($menu2Data as $menu2Item) {
            $menu2 = Menus2::findOrNew($menu2Item['id']);
            $menu2->fill($menu2Item);
            $menu2->menus1_id = $menu1->id;
            $menu2->created_by = Auth::id();
            $menu2->modified_by = Auth::id();
            $menu2->save();
            $menu2Ids[] = $menu2->id;

            $this->syncSubmenus3($menu2Item['menus3'] ?? [], $menu2);
        }

        Menus2::whereNotIn('id', $menu2Ids)->where('menus1_id', $menu1->id)->delete();
    }

    private function syncSubmenus3($menu3Data, $menu2)
    {
        $menu3Ids = [];

        foreach ($menu3Data as $menu3Item) {
            $menu3 = Menus3::findOrNew($menu3Item['id']);
            $menu3->fill($menu3Item);
            $menu3->menus2_id = $menu2->id;
            $menu3->created_by = Auth::id();
            $menu3->modified_by = Auth::id();
            $menu3->save();
            $menu3Ids[] = $menu3->id;
        }

        Menus3::whereNotIn('id', $menu3Ids)->where('menus2_id', $menu2->id)->delete();
    }

    public function getJsonData(Menus1 $menu1)
    {
        $menu1->load('menus2.menus3');
        return response()->json($menu1);
    }

    public function showSubmenu(Menus2 $menu2)
    {
        return view('pages.config.submenus-show', compact('menu2'));
    }

    public function editSubmenu(Menus2 $menu2)
    {
        return view('pages.config.submenus-edit', compact('menu2'));
    }

    public function updateSubmenu(Request $request, Menus2 $menu2)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'nullable|url',
            'icon' => 'nullable',
            'position' => 'nullable|integer',
        ]);

        $menu2->update($request->all());

        return redirect()->route('menus.index')->with('success', 'Submenu atualizado com sucesso!');
    }

    public function destroySubmenu(Menus2 $menu2)
    {
        $menu2->delete();

        return redirect()->route('menus.index')->with('success', 'Submenu excluído com sucesso!');
    }

    public function showSubsubmenu(Menus3 $menu3)
    {
        return view('pages.config.subsubmenus-show', compact('menu3'));
    }

    public function editSubsubmenu(Menus3 $menu3)
    {
        return view('pages.config.subsubmenus-edit', compact('menu3'));
    }

    public function updateSubsubmenu(Request $request, Menus3 $menu3)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'nullable|url',
            'icon' => 'nullable',
            'position' => 'nullable|integer',
        ]);

        $menu3->update($request->all());

        return redirect()->route('menus.index')->with('success', 'Sub-submenu atualizado com sucesso!');
    }

    public function destroySubsubmenu(Menus3 $menu3)
    {
        $menu3->delete();

        return redirect()->route('menus.index')->with('success', 'Sub-submenu excluído com sucesso!');
    }
}
