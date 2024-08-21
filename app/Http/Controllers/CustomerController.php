<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Http\Resources\CustomerResource;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Customer API",
 *     description="API Documentation for Customer Management",
 * )
 */

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @OA\Get(
     *     path="/api/customers",
     *     operationId="getCustomersList",
     *     tags={"Customers"},
     *     summary="Get list of customers",
     *     description="Returns list of customers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Customer")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $customers = $this->customerService->findAll();
        return CustomerResource::collection($customers);
    }

    /**
     * @OA\Post(
     *     path="/api/customers",
     *     summary="Create a new customer",
     *     description="Creates a new customer and dispatches a job to handle the creation process.",
     *     operationId="storeCustomer",
     *     tags={"Customers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"company_name", "email", "phone_number", "password", "created_by_user_id", "status"},
     *             @OA\Property(property="company_name", type="string", example="Test Company"),
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="1234567890"),
     *             @OA\Property(property="password", type="string", example="secret"),
     *             @OA\Property(property="created_by_user_id", type="integer", example=1),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer creation job dispatched successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Customer creation job dispatched.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties=@OA\Property(type="string", example="The company_name field is required.")
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'created_by_user_id' => 'required|integer|exists:users,id',
            'status' => 'required|boolean',
        ]);

        $this->customerService->create($data);

        return response()->json(['message' => 'Customer creation job dispatched.'], 201);
    }
}
