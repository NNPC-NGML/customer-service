<?php

namespace App\Http\Controllers;

use App\Models\CustomerDdqGroup;
use App\Services\DDQService;
use Illuminate\Http\Request;

/**
 * Class CustomerDdqGroupController
 *
 * Handles the operations related to Customer Due Diligence (DDQ) groups.
 * Provides methods for listing, creating, viewing, updating, and deleting DDQ groups.
 *
 * @package App\Http\Controllers
 */
class CustomerDdqGroupController extends Controller
{
    /**
     * @var DDQService Service for validating DDQ groups.
     */
    protected DDQService $ddqService;

    /**
     * CustomerDdqGroupController constructor.
     *
     * @param DDQService $ddqService The DDQ service instance for validating DDQ groups.
     */
    public function __construct(DDQService $ddqService)
    {
        $this->ddqService = $ddqService;
    }

    /**
     * Display a listing of all DDQ groups.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing a list of all DDQ groups.
     */
    public function index()
    {
        $groups = CustomerDdqGroup::all();
        return response()->json($groups);
    }

    /**
     * Store a newly created DDQ group in the database.
     *
     * @param Request $request The HTTP request containing data for the new DDQ group.
     * @return \Illuminate\Http\JsonResponse The JSON response with the created DDQ group and HTTP status code 201 (Created).
     */
    public function store(Request $request)
    {
        $data = $this->ddqService->validateDDQGroup($request->all());
        $group = CustomerDdqGroup::create($data);
        return response()->json($group, 201);
    }

    /**
     * Display a specific DDQ group by ID.
     *
     * @param int $id The ID of the DDQ group to retrieve.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the requested DDQ group.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ group with the given ID is not found.
     */
    public function show($id)
    {
        $group = CustomerDdqGroup::findOrFail($id);
        return response()->json($group);
    }

    /**
     * Update the specified DDQ group in the database.
     *
     * @param Request $request The HTTP request containing data for updating the DDQ group.
     * @param int $id The ID of the DDQ group to update.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated DDQ group.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ group with the given ID is not found.
     */
    public function update(Request $request, $id)
    {
        $data = $this->ddqService->validateDDQGroup($request->all());
        $group = CustomerDdqGroup::findOrFail($id);
        $group->update($data);
        return response()->json($group);
    }

    /**
     * Remove the specified DDQ group from the database.
     *
     * @param int $id The ID of the DDQ group to delete.
     * @return \Illuminate\Http\JsonResponse The JSON response with HTTP status code 204 (No Content).
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ group with the given ID is not found.
     */
    public function destroy($id)
    {
        $group = CustomerDdqGroup::findOrFail($id);
        $group->delete();
        return response()->json(null, 204);
    }
}
