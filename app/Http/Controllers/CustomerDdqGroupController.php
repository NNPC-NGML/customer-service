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
        $groups = CustomerDdqGroup::all();
        return response()->json([
            'status' => 'success',
            'data' => CustomerDdqGroupResource::collection($groups)
        ]);
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
        $data = $this->ddqService->validateDDQGroup($request->all());
        $group = CustomerDdqGroup::create($data);
        return response()->json([
            'status' => 'success',
            'data' => new CustomerDdqGroupResource($group)
        ], 201);
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
    public function show($id): JsonResponse
    {
        $group = CustomerDdqGroup::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => new CustomerDdqGroupResource($group)
        ]);
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
    public function update(Request $request, $id): JsonResponse
    {
        $data = $this->ddqService->validateDDQGroup($request->all());
        $group = CustomerDdqGroup::findOrFail($id);
        $group->update($data);
        return response()->json([
            'status' => 'success',
            'data' => new CustomerDdqGroupResource($group)
        ]);
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
    public function destroy($id): JsonResponse
    {
        $group = CustomerDdqGroup::findOrFail($id);
        $group->delete();
        return response()->json([
            'status' => 'success'
        ], 204);
    }
}
