<?php

namespace App\Http\Controllers;

use App\Services\DDQService;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerDdqGroupResource;

/**
 * @OA\Schema(
 *     schema="CustomerDdqGroup",
 *     type="object",
 *     title="Customer DDQ Group",
 *     @OA\Property(property="id", type="integer", example=1, description="ID of the DDQ Group"),
 *     @OA\Property(property="title", type="string", example="Group Title", description="Title of the DDQ Group"),
 *     @OA\Property(property="status", type="boolean", example=true, description="Status of the DDQ Group"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation date of the DDQ Group"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date of the DDQ Group")
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
     *     summary="Get a list of DDQ Groups",
     *     description="Fetches a list of all available DDQ groups.",
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
    public function index()
    {
        try {
            $groups = $this->ddqService->getAllCustomerDdqGroups();
            return CustomerDdqGroupResource::collection($groups)->additional([
                'status' => 'success'
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
     *     description="Creates a new DDQ group with the provided title and status.",
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
     *         description="DDQ Group created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/CustomerDdqGroup")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        try {
            $data = $this->ddqService->validateDDQGroup($request->all());
            $group = $this->ddqService->createACustomerDdqGroup($data);
            return (new CustomerDdqGroupResource($group))->additional([
                'status' => 'success'
            ]);
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
     *     description="Fetches a DDQ group by its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the DDQ Group",
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
    public function show(int $id)
    {
        try {
            $group = $this->ddqService->getCustomerDdqGroupById($id);
            return (new CustomerDdqGroupResource($group))->additional([
                'status' => 'success'
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
     *     description="Updates a DDQ group with the specified ID using the provided data.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the DDQ Group",
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
     *         description="DDQ Group updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/CustomerDdqGroup")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(Request $request, int $id)
    {
        try {
            $data = $this->ddqService->validateDDQGroup($request->all());
            $group = $this->ddqService->getCustomerDdqGroupById($id);
            $group->update($data);
            return (new CustomerDdqGroupResource($group))->additional([
                'status' => 'success'
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
     *     description="Deletes the DDQ group with the specified ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the DDQ Group",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="DDQ Group deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(int $id)
    {
        try {
            $group = $this->ddqService->getCustomerDdqGroupById($id);
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
