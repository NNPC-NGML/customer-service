<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerDdqExistingResource;
use App\Services\CustomerDdqExistingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerDdqExistingController extends Controller
{
    /**
     * @var CustomerDdqExistingService
     */
    protected CustomerDdqExistingService $customerDdqExistingService;

    /**
     * CustomerDdqExistingController constructor.
     *
     * @param CustomerDdqExistingService $customerDdqExistingService
     */
    public function __construct(CustomerDdqExistingService $customerDdqExistingService)
    {
        $this->customerDdqExistingService = $customerDdqExistingService;
    }

    /**
     * @OA\Get(
     *     path="/api/customer-ddq-existings",
     *     tags={"Customer DDQ Existings"},
     *     summary="Get a list of Existing DDQ Customers",
     *     description="Fetches a list of all available DDQ groups.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CustomerDdqExisting")
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
            $ddqs = $this->customerDdqExistingService->getAll();
            return CustomerDdqExistingResource::collection($ddqs)->additional([
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
     *     path="/api/customer-ddq-existings",
     *     tags={"Customer DDQ Existings"},
     *     summary="Create a new Existing DDQ Customer",
     *     description="Creates a new Existing DDQ Customer with their provided information.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "file_path", "customer_id", "customer_site_id", "status"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="customer_site_id", type="integer", example=1),
     *             @OA\Property(property="file_path", type="string", example="document.pdf"),
     *             @OA\Property(property="status", type="string", example="pending"),
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
            // Validate the input data using the service
            $data = $this->customerDdqExistingService->validate($request->all());

            // Create the DDQ
            $ddq = $this->customerDdqExistingService->create($data);

            return (new CustomerDdqExistingResource($ddq))->additional([
                'status' => 'success'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/customer-ddq-existings/{id}",
     *     tags={"Customer DDQ Existings"},
     *     summary="Get details of a specific Existing DDQ Customer",
     *     description="Fetches details of a specific DDQ group by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the DDQ Group",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(ref="#/components/schemas/CustomerDdqExisting")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show($id)
    {
        try {
            // Retrieve DDQ by ID
            $ddq = $this->customerDdqExistingService->getById($id);

            return (new CustomerDdqExistingResource($ddq))->additional([
                'status' => 'success'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'DDQ not found',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/customer-ddq-existings/{id}",
     *     tags={"Customer DDQ Existings"},
     *     summary="Update a specific Existing DDQ Customer",
     *     description="Updates an Existing DDQ Customer with the provided information.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the DDQ Group",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="customer_id", type="integer", example=1),
     *             @OA\Property(property="customer_site_id", type="integer", example=1),
     *             @OA\Property(property="file_path", type="string", example="updated_document.pdf"),
     *             @OA\Property(property="status", type="string", example="approved"),
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
     *     @OA\Response(response=400, description="Bad Request")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the input data using the service
            $data = $this->customerDdqExistingService->validate($request->all());

            // Find the DDQ and update it
            $ddq = $this->customerDdqExistingService->getById($id);
            $ddq->update($data);

            return (new CustomerDdqExistingResource($ddq))->additional([
                'status' => 'success'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'DDQ not found',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/customer-ddq-existings/{id}",
     *     tags={"Customer DDQ Existings"},
     *     summary="Delete a specific Existing DDQ Customer",
     *     description="Deletes a specific DDQ group by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the DDQ Group",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="DDQ Group deleted successfully"
     *     ),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=400, description="Bad Request")
     * )
     */
    public function destroy($id)
    {
        try {
            // Find and delete the DDQ
            $ddq = $this->customerDdqExistingService->getById($id);
            $ddq->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'DDQ deleted successfully'
            ], 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'DDQ not found',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
