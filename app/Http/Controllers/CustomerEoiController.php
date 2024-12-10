<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerEoiResource;
use App\Services\CustomerEoiService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerEoiController extends Controller
{
    /**
     * @var CustomerEoiService
     */
    protected CustomerEoiService $customerEoiService;

    /**
     * CustomerEoiController constructor.
     *
     * @param CustomerEoiService $customerEoiService
     */
    public function __construct(CustomerEoiService $customerEoiService)
    {
        $this->customerEoiService = $customerEoiService;
    }

    /**
     * @OA\Get(
     *     path="/api/customer-eois",
     *     tags={"Customer EOIs"},
     *     summary="Get a list of Customer EOIs",
     *     description="Fetches a list of all Customer EOIs.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Eoi")
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
            $customerEois = $this->customerEoiService->getAll();
            return CustomerEoiResource::collection($customerEois)->additional([
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
     * @OA\Get(
     *     path="/api/customer-eois/{id}",
     *     tags={"Customer EOIs"},
     *     summary="Get details of a specific Customer EOI",
     *     description="Fetches details of a specific Customer EOI by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Customer EOI",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/Eoi")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show($id)
    {
        try {
            $customerEoi = $this->customerEoiService->getById($id);

            return (new CustomerEoiResource($customerEoi))->additional([
                'status' => 'success'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer EOI not found',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/customer-eois/{id}",
     *     tags={"Customer EOIs"},
     *     summary="Delete a specific Customer EOI",
     *     description="Deletes a specific Customer EOI by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Customer EOI",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Customer EOI deleted successfully"
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=400, description="Bad Request")
     * )
     */
    public function destroy($id)
    {
        try {
            $customerEoi = $this->customerEoiService->getById($id);
            $customerEoi->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer EOI deleted successfully'
            ], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer EOI not found',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
