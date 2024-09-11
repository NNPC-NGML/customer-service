<?php

namespace App\Http\Controllers;

use App\Models\CustomerDdqSubGroup;
use App\Services\DDQService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CustomerDdqSubGroupResource;

class CustomerDdqSubGroupController extends Controller
{
    /**
     * @var DDQService
     */
    protected DDQService $ddqService;

    /**
     * CustomerDdqSubGroupController constructor.
     *
     * @param DDQService $ddqService
     */
    public function __construct(DDQService $ddqService)
    {
        $this->ddqService = $ddqService;
    }

    /**
     * @OA\Get(
     *     path="/api/ddq-subgroups",
     *     tags={"Customer DDQ SubGroups"},
     *     summary="Get list of DDQ SubGroups",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CustomerDdqSubGroup")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        try {
            $subgroups = $this->ddqService->getAllCustomerDdqSubGroups();
            return CustomerDdqSubGroupResource::collection($subgroups)->additional([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/ddq-subgroups",
     *     tags={"Customer DDQ SubGroups"},
     *     summary="Create a new DDQ SubGroup",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_ddq_group_id", "title", "status"},
     *             @OA\Property(property="customer_ddq_group_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="New SubGroup"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="DDQ SubGroup created",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/CustomerDdqSubGroup")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        try {
            $data = $this->ddqService->validateDDQSubGroup($request->all());
            $subgroup = $this->ddqService->createACustomerDdqSubGroup($data);
            return (new CustomerDdqSubGroupResource($subgroup))->additional([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],  400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/ddq-subgroups/{id}",
     *     tags={"Customer DDQ SubGroups"},
     *     summary="Get a specific DDQ SubGroup by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/CustomerDdqSubGroup")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show(int $id)
    {
        try {
            $subgroup = $this->ddqService->getCustomerDdqSubGroupById($id);
            return (new CustomerDdqSubGroupResource($subgroup))->additional([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],  404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/ddq-subgroups/{id}",
     *     tags={"Customer DDQ SubGroups"},
     *     summary="Update a specific DDQ SubGroup",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated SubGroup"),
     *             @OA\Property(property="status", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="DDQ SubGroup updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/CustomerDdqSubGroup")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, int $id)
    {
        try {
            $data = $this->ddqService->validateDDQSubGroupUpdate($request->all());
            $subgroup = $this->ddqService->getCustomerDdqSubGroupById($id);
            $subgroup->update($data);
            return (new CustomerDdqSubGroupResource($subgroup))->additional([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],  400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/ddq-subgroups/{id}",
     *     tags={"Customer DDQ SubGroups"},
     *     summary="Delete a specific DDQ SubGroup",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="DDQ SubGroup deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $subgroup = $this->ddqService->getCustomerDdqSubGroupById($id);
            $subgroup->delete();
            return response()->json([
                'status' => 'success'
            ], 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],  404);
        }
    }
}
