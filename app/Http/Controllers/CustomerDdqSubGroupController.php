<?php

namespace App\Http\Controllers;

use App\Models\CustomerDdqSubGroup;
use App\Services\DDQService;
use Illuminate\Http\Request;

/**
 * Class CustomerDdqSubGroupController
 *
 * Handles the operations related to Customer Due Diligence (DDQ) subgroups.
 * Provides methods for creating, listing, viewing, updating, and deleting DDQ subgroups.
 *
 * @package App\Http\Controllers
 */
class CustomerDdqSubGroupController extends Controller
{
    // /**
    //  * @var DDQService Service for validating DDQ subgroups.
    //  */
    // protected DDQService $ddqService;

    // /**
    //  * CustomerDdqSubGroupController constructor.
    //  *
    //  * @param DDQService $ddqService The DDQ service instance for validating DDQ subgroups.
    //  */
    // public function __construct(DDQService $ddqService)
    // {
    //     $this->ddqService = $ddqService;
    // }

    // /**
    //  * Store a newly created DDQ subgroup in the database.
    //  *
    //  * @param Request $request The HTTP request containing data for the new DDQ subgroup.
    //  * @return \Illuminate\Http\JsonResponse The JSON response with the created DDQ subgroup and HTTP status code 201 (Created).
    //  */
    // public function store(Request $request)
    // {
    //     $data = $this->ddqService->validateDDQSubGroup($request->all());
    //     $subgroup = CustomerDdqSubGroup::create($data);
    //     return response()->json($subgroup, 201);
    // }

    // /**
    //  * Display a listing of all DDQ subgroups.
    //  *
    //  * @return \Illuminate\Http\JsonResponse The JSON response containing a list of all DDQ subgroups.
    //  */
    // public function index()
    // {
    //     $subgroups = CustomerDdqSubGroup::all();
    //     return response()->json($subgroups);
    // }

    // /**
    //  * Display a specific DDQ subgroup by ID.
    //  *
    //  * @param int $id The ID of the DDQ subgroup to retrieve.
    //  * @return \Illuminate\Http\JsonResponse The JSON response containing the requested DDQ subgroup.
    //  * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ subgroup with the given ID is not found.
    //  */
    // public function show($id)
    // {
    //     $subgroup = CustomerDdqSubGroup::findOrFail($id);
    //     return response()->json($subgroup);
    // }

    // /**
    //  * Update the specified DDQ subgroup in the database.
    //  *
    //  * @param Request $request The HTTP request containing data for updating the DDQ subgroup.
    //  * @param int $id The ID of the DDQ subgroup to update.
    //  * @return \Illuminate\Http\JsonResponse The JSON response containing the updated DDQ subgroup.
    //  * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ subgroup with the given ID is not found.
    //  */
    // public function update(Request $request, $id)
    // {
    //     $data = $this->ddqService->validateDDQSubGroup($request->all());
    //     $subgroup = CustomerDdqSubGroup::findOrFail($id);
    //     $subgroup->update($data);
    //     return response()->json($subgroup);
    // }

    // /**
    //  * Remove the specified DDQ subgroup from the database.
    //  *
    //  * @param int $id The ID of the DDQ subgroup to delete.
    //  * @return \Illuminate\Http\JsonResponse The JSON response with HTTP status code 204 (No Content).
    //  * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ subgroup with the given ID is not found.
    //  */
    // public function destroy($id)
    // {
    //     $subgroup = CustomerDdqSubGroup::findOrFail($id);
    //     $subgroup->delete();
    //     return response()->json(null, 204);
    // }
}
