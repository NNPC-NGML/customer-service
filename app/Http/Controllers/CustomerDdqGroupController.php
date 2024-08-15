<?php

namespace App\Http\Controllers;

use App\Models\CustomerDdqGroup;
use App\Services\DDQService;
use Illuminate\Http\Request;

class CustomerDdqGroupController extends Controller
{
    protected DDQService $ddqService;

    public function __construct(DDQService $ddqService)
    {
        $this->ddqService = $ddqService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $groups = CustomerDdqGroup::all();
        return response()->json($groups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $this->ddqService->validateDDQGroup($request->all());
        $group = CustomerDdqGroup::create($data);
        return response()->json($group, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $group = CustomerDdqGroup::findOrFail($id);
        return response()->json($group);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $data = $this->ddqService->validateDDQGroup($request->all());
        $group = CustomerDdqGroup::findOrFail($id);
        $group->update($data);
        return response()->json($group);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $group = CustomerDdqGroup::findOrFail($id);
        $group->delete();
        return response()->json(null, 204);
    }
}
