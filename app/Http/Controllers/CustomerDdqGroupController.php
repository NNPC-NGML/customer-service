<?php

namespace App\Http\Controllers;

use App\Models\CustomerDdqGroup;
use App\Services\DDQService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\CustomerDdqGroupResource;

/**
 * @OA\Schema(
 *     schema="CustomerDdqGroup",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Group Title"),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class CustomerDdqGroupController extends Controller
{
    /**
     * @var DDQService
     */
    protected DDQService $ddqService;

    /**
     * CustomerDdqGroupController constructor.
     *
     * @param DDQService $ddqService
     */
    public function __construct(DDQService $ddqService)
    {
        $this->ddqService = $ddqService;
    }

    /**
     * @OA\Get(
     *     path="/api/ddq-groups",
     *     tags={"Customer DDQ Groups"},
     *     summary="Get list of DDQ Groups",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CustomerDdqGroup")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $groups = $this->ddqService->getAllCustomerDdqGroups();
            return response()->json([
                'status' => 'success',
                'data' => CustomerDdqGroupResource::collection($groups)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode() ?: 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/ddq-groups",
     *     tags={"Customer DDQ Groups"},
     *     summary="Create a new DDQ Group",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "status"},
     *             @OA\Property(property="title", type="string", example="New Group"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="DDQ Group created",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/CustomerDdqGroup")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->ddqService->validateDDQGroup($request->all());
            $group = $this->ddqService->createACustomerDdqGroup($data);
            return response()->json([
                'status' => 'success',
                'data' => new CustomerDdqGroupResource($group)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode() ?: 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/ddq-groups/{id}",
     *     tags={"Customer DDQ Groups"},
     *     summary="Get a specific DDQ Group by ID",
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
     *             @OA\Property(ref="#/components/schemas/CustomerDdqGroup")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $group = $this->ddqService->findACustomerDdqGroupById($id);
            return response()->json([
                'status' => 'success',
                'data' => new CustomerDdqGroupResource($group)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode() ?: 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/ddq-groups/{id}",
     *     tags={"Customer DDQ Groups"},
     *     summary="Update a specific DDQ Group",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "status"},
     *             @OA\Property(property="title", type="string", example="Updated Group"),
     *             @OA\Property(property="status", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="DDQ Group updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/CustomerDdqGroup")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = $this->ddqService->validateDDQGroup($request->all());
            $group = $this->ddqService->findACustomerDdqGroupById($id);
            $group->update($data);
            return response()->json([
                'status' => 'success',
                'data' => new CustomerDdqGroupResource($group)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode() ?: 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/ddq-groups/{id}",
     *     tags={"Customer DDQ Groups"},
     *     summary="Delete a specific DDQ Group",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="DDQ Group deleted",
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
            $group = $this->ddqService->findACustomerDdqGroupById($id);
            $group->delete();
            return response()->json([
                'status' => 'success'
            ], 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], $th->getCode() ?: 404);
        }
    }
}
