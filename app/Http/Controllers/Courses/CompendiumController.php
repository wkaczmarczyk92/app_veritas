<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

use App\Http\Requests\Compendium\StoreRequest;
use App\Models\Courses\Compendium;

use Illuminate\Support\Facades\DB;
use App\Services\Compendium\CompendiumStoreService;
use App\Services\Compendium\CompendiumDestroyService;
use App\Services\Compendium\CompendiumUpdateService;

use App\Services\Vademecum\VademecumStoreService;
// use App\Services\Compendium\VademecumDestroyService;
// use App\Services\Compendium\VademecumUpdateService;

use App\Models\Common\Visibility;
use App\Models\Common\CompanyBranch;
use Spatie\Permission\Models\Role;

class CompendiumController extends Controller
{
    public function create_compendium()
    {
        return Inertia::render('CourseModerator/Compendium/CreateForm', [
            'visibility' => Visibility::all(),
            'company_branches' => CompanyBranch::all(),
            'roles' => Role::where('show_course', true)->get()
        ]);
    }

    public function create_vademecum()
    {
        return Inertia::render('CourseModerator/Vademecum/CreateForm', [
            'visibility' => Visibility::all(),
            'company_branches' => CompanyBranch::all(),
            'roles' => Role::where('show_course', true)->get()
        ]);
    }

    public function edit(int $id)
    {
        $compendium = Compendium::with(['roles' => function ($query) {
            $query->select('roles.id');
        }, 'company_branches' => function ($query) {
            $query->select('company_branches.id');
        }])->find($id);

        $compendium->roles_id = $compendium->roles->pluck('id')->toArray();
        $compendium->company_branches_id = $compendium->company_branches->pluck('id')->toArray();

        return Inertia::render('CourseModerator/Compendium/EditForm', [
            'compendium' => $compendium,
            'visibility' => Visibility::all(),
            'company_branches' => CompanyBranch::all(),
            'roles' => Role::where('show_course', true)->get()
        ]);
    }

    public function edit_vademecum(int $id)
    {
        $vademecum = Compendium::with(['roles' => function ($query) {
            $query->select('roles.id');
        }, 'company_branches' => function ($query) {
            $query->select('company_branches.id');
        }])->find($id);

        $vademecum->roles_id = $vademecum->roles->pluck('id')->toArray();
        $vademecum->company_branches_id = $vademecum->company_branches->pluck('id')->toArray();

        return Inertia::render('CourseModerator/Vademecum/EditForm', [
            'vademecum' => $vademecum,
            'visibility' => Visibility::all(),
            'company_branches' => CompanyBranch::all(),
            'roles' => Role::where('show_course', true)->get()
        ]);
    }

    public function index()
    {
        return Inertia::render('CourseModerator/Compendium/List');
    }

    public function index_vademeca()
    {
        return Inertia::render('CourseModerator/Vademecum/List');
    }

    public function show(int $id)
    {
        $compendium = Compendium::with(['lessons', 'visibility', 'roles', 'company_branches'])->withCount('lessons')->find($id);

        if (!$compendium) {
            abort(404, 'Not found');
        }

        return Inertia::render('CourseModerator/Compendium/Show', [
            'compendium' => $compendium
        ]);
    }

    public function show_vademecum(int $id)
    {
        $vademecum = Compendium::with(['lessons', 'visibility', 'roles', 'company_branches'])->withCount('lessons')->find($id);

        if (!$vademecum) {
            abort(404, 'Not found');
        }

        return Inertia::render('CourseModerator/Vademecum/Show', [
            'vademecum' => $vademecum
        ]);
    }

    public function list(Request $request)
    {
        $type_id = $request->input('type_id') ?? 1;

        return response()->json([
            'compendiums' => Compendium::withCount('lessons')
                ->with(['visibility', 'roles', 'company_branches'])
                ->where('compendium_type_id', $type_id)
                ->paginate(50)
        ]);
    }

    public function store_compendium(StoreRequest $request)
    {
        $service = new CompendiumStoreService;
        return $service->store($request->all());
    }

    public function store_vademecum(StoreRequest $request)
    {
        $service = new VademecumStoreService;
        return $service->store($request->all());
    }

    public function update(StoreRequest $request, int $id)
    {
        return (new CompendiumUpdateService)->update($request->all(), $id);
    }

    public function destroy(int $id)
    {
        return (new CompendiumDestroyService)->destroy($id);
    }
}
