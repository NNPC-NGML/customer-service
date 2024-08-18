<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\CustomerContractAddendum;
use App\Http\Requests\StoreAddendumRequest;
use App\Http\Requests\UpdateAddendumRequest;
use App\Services\Contract\CustomerContractAddendumService;

/**
 * @OA\Tag(
 *     name="Contract Addendums",
 *     description="API Endpoints of Contract Addendums"
 * )
 */

class CustomerContractAddendumController extends Controller

{
    protected $service;

    public function __construct(CustomerContractAddendumService $service)
    {
        $this->service = $service;
    }
    /**
     * @OA\Get(
     *     path="/api/contract-addendums",
     *     summary="Get all contract addendums",
     *     tags={"Contract Addendums"},
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(): JsonResponse
    {
        $addendums = $this->service->getAllAddendums();
        return response()->json(['success' => true, 'data' => $addendums]);
    }

    /**
     * @OA\Post(
     *     path="/api/contract-addendums",
     *     summary="Create a new contract addendum",
     *     tags={"Contract Addendums"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreAddendumRequest")
     *     ),
     *     @OA\Response(response=201, description="Successful operation"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    public function store(StoreAddendumRequest $request): JsonResponse
    {
        $addendum = $this->service->attachAddendum($request->validated() + ['created_by_user_id' => auth()->id()]);
        return response()->json(['success' => true, 'message' => 'Addendum created successfully.', 'data' => $addendum], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contract-addendums/{id}",
     *     summary="Get a specific contract addendum",
     *     tags={"Contract Addendums"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Addendum not found")
     * )
     */
    public function show(CustomerContractAddendum $addendum): JsonResponse
    {
        return response()->json($addendum->load(['parentContract', 'childContract']));
    }

    /**
     * @OA\Put(
     *     path="/api/contract-addendums/{id}",
     *     summary="Update a specific contract addendum",
     *     tags={"Contract Addendums"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateAddendumRequest")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Addendum not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateAddendumRequest $request, CustomerContractAddendum $addendum): JsonResponse
    {
        $addendum->update($request->validated());
        return response()->json($addendum);
    }

    /**
     * @OA\Delete(
     *     path="/api/contract-addendums/{id}",
     *     summary="Delete a specific contract addendum",
     *     tags={"Contract Addendums"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Successful operation"),
     *     @OA\Response(response=404, description="Addendum not found")
     * )
     */
    public function destroy(CustomerContractAddendum $addendum): JsonResponse
    {
        $addendum->delete();
        return response()->json(null, 204);
    }
}
