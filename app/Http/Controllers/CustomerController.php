<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
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

        $this->customerService->createCustomer($data);

        return response()->json(['message' => 'Customer creation job dispatched.'], 201);
    }

    /**
 * @OA\Get(
 *     path="/api/customers",
 *     summary="Get a list of all customers",
 *     tags={"Customers"},
 *     @OA\Response(
 *         response=200,
 *         description="List of customers",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
    /**
     * Retrieve a list of all customers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $customers = $this->customerService->getAllCustomers();
        return response()->json($customers);
    }


    /**
 * @OA\Get(
 *     path="/api/customers/{id}",
 *     operationId="getCustomerById",
 *     tags={"Customers"},
 *     summary="Get a customer by ID",
 *     description="Returns a customer",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Customer ID",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/Customer")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Customer not found"
 *     ),
 * )
 */

    /**
     * Retrieve a specific customer by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $customer = $this->customerService->showCustomer($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        return response()->json($customer, 200);
    }
}

    