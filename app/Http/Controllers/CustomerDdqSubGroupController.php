<?php

namespace App\Http\Controllers;

use App\Models\CustomerDdqSubGroup;
use App\Services\DDQService;
use Illuminate\Http\Request;

class CustomerDdqSubGroupController extends Controller
{
    //
    protected DDQService $ddqService;

    public function __construct(DDQService $ddqService)
    {
        $this->ddqService = $ddqService;
    }

    public function store(Request $request)
    {
        $data = $this->ddqService->validateDDQSubGroup($request->all());
        $subgroup = CustomerDdqSubGroup::create($data);
        return response()->json($subgroup, 201);

    }

    public function index()
    {
        $subgroups = CustomerDdqSubGroup::all();
        return response()->json($subgroups);
    }

    public function show($id)
    {
        $subgroup = CustomerDdqSubGroup::findOrFail($id);
        return response()->json($subgroup);
    }

    public function update(Request $request, $id)
    {
        $data = $this->ddqService->validateDDQSubGroup($request->all());
        $subgroup = CustomerDdqSubGroup::findOrFail($id);
        $subgroup->update($data);
        return response()->json($subgroup);
    }

    public function destroy($id)
    {
        $subgroup = CustomerDdqSubGroup::findOrFail($id);
        $subgroup->delete();
        return response()->json(null, 204);
    }
}
