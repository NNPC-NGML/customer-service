<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerDdqResource;
use App\Services\DDQService;

class CustomerDdqController extends Controller
{
    /**
     * @var DDQService
     */
    protected DDQService $ddqService;

    /**
     * CustomerDdqController constructor.
     *
     * @param DDQService $ddqService
     */
    public function __construct(DDQService $ddqService)
    {
        $this->ddqService = $ddqService;
    }

    // /**
    //  * @OA\Get(
    //  *     path="/api/ddqs/{customer_id}/{site_id}/{group_id}/{subgroup_id}",
    //  *     tags={"Customer DDQs"},
    //  *     summary="Get list of DDQs",
    //  *     @OA\Parameter(
    //  *         name="customer_id",
    //  *         in="path",
    //  *         required=true,
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="site_id",
    //  *         in="path",
    //  *         required=true,
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="group_id",
    //  *         in="path",
    //  *         required=true,
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Parameter(
    //  *         name="subgroup_id",
    //  *         in="path",
    //  *         required=true,
    //  *         @OA\Schema(type="integer")
    //  *     ),
    //  *     @OA\Response(
    //  *         response=200,
    //  *         description="Successful operation",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="status", type="string", example="success"),
    //  *             @OA\Property(
    //  *                 property="data",
    //  *                 type="array",
    //  *                 @OA\Items(ref="#/components/schemas/CustomerDdq")
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Response(response=401, description="Unauthorized"),
    //  *     @OA\Response(response=403, description="Forbidden"),
    //  *     @OA\Response(response=404, description="Not Found")
    //  * )
    //  */
    // public function showDDQ($customer_id, $site_id, $group_id, $subgroup_id)
    // {
    //     try {
    //         $ddq = $this->ddqService->getCustomerDdqByParams($customer_id, $site_id, $group_id, $subgroup_id);
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => CustomerDdqResource::collection($ddq)
    //         ]);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $th->getMessage(),
    //         ], 500);
    //     }
    // }


    /**
     * @OA\GET(
     *     path="/api/ddqs/view/{id}",
     *     tags={"Customer DDQs"},
     *     summary="Get a specific DDQ by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="DDQ found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function viewDDQ(int $id)
    {
        try {
            $ddq = $this->ddqService->getCustomerDdqById($id);
            return (new CustomerDdqResource($ddq))->additional([
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
     * @OA\Delete(
     *     path="/api/ddqs/{id}",
     *     tags={"Customer DDQs"},
     *     summary="Delete a specific DDQ by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="DDQ deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function destroy(int $id)
    {
        try {
            $ddq = $this->ddqService->getCustomerDdqById($id);
            $ddq->delete();
            return response()->json([
                'status' => 'success'
            ], 204);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/ddqs/approve/{id}",
     *     tags={"Customer DDQs"},
     *     summary="Approve a specific DDQ by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="DDQ approved",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function approve(int $id)
    {
        try {
            $ddq = $this->ddqService->getCustomerDdqById($id);
            // $subgroup = $this->ddqService->getCustomerDdqSubGroupById($ddq->subgroup_id);
            // $subgroup->status = true;
            $ddq->status = 'approved';
            $ddq->save();
            // $subgroup->save();
            return response()->json([
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
     * @OA\Post(
     *     path="/api/ddqs/decline/{id}",
     *     tags={"Customer DDQs"},
     *     summary="Decline a specific DDQ by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="DDQ declined",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function decline(int $id)
    {
        try {
            $ddq = $this->ddqService->getCustomerDdqById($id);
            // $subgroup = $this->ddqService->getCustomerDdqSubGroupById($ddq->subgroup_id);
            // $subgroup->status = false;
            $ddq->status = 'declined';
            $ddq->save();
            // $subgroup->save();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ],  404);
        }
    }
}
