<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerContract;
use Illuminate\Http\JsonResponse;
use App\Services\Contract\ContractService;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;

/**
 * @OA\Tag(
 *     name="Contracts",
 *     description="API Endpoints of Customer Contracts"
 * )
 */

class CustomerContractController extends Controller
{

    protected $service;

    public function __construct(ContractService $contractService)
    {
        $this->service = $contractService;
    }
    /**
     * @OA\Get(
     *     path="/api/contracts",
     *     summary="Get all contracts",
     *     tags={"Contracts"},
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(): JsonResponse
    {
        $contracts = CustomerContract::with(['createdByUser', 'contractType'])->get();
        return response()->json($contracts);
    }

    /**
     * @OA\Post(
     *     path="/api/contracts",
     *     summary="Create a new contract",
     *     tags={"Contracts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreContractRequest")
     *     ),
     *     @OA\Response(response=201, description="Successful operation"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreContractRequest $request): JsonResponse
    {



        // $data = $request->validated();
        // $contract = CustomerContract::create($request->validated() + ['created_by_user_id' => auth()->id()]);
        // return response()->json($contract, 201);


        $data = $request->validated() + ['created_by_user_id' => auth()->id()];
        $contract =  $this->service->create($data);
        return response()->json($contract, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contracts/{id}",
     *     summary="Get a specific contract",
     *     tags={"Contracts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Contract not found")
     * )
     */
    public function show(CustomerContract $contract): JsonResponse
    {
        return response()->json($contract->load(['contractType']));
    }

    /**
     * @OA\Put(
     *     path="/api/contracts/{id}",
     *     summary="Update a specific contract",
     *     tags={"Contracts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateContractRequest")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Contract not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    public function update(UpdateContractRequest $request, CustomerContract $contract): JsonResponse
    {

        // $contract->update($request->toArray());
        // return response()->json($contract);
        $data = $this->service->update($contract->id, $request->toArray());
        return response()->json($data);
    }


    /**
     * @OA\Delete(
     *     path="/api/contracts/{id}",
     *     summary="Delete a specific contract",
     *     tags={"Contracts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Successful operation"),
     *     @OA\Response(response=404, description="Contract not found")
     * )
     */
    public function destroy(CustomerContract $contract): JsonResponse
    {
        $contract->delete();
        return response()->json(null, 204);
    }
}
