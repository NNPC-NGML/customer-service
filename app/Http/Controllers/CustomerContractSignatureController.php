<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\CustomerContractSignature;
use App\Http\Requests\SignContractRequest;

/**
 * @OA\Tag(
 *     name="Contract Signatures",
 *     description="API Endpoints of Contract Signatures"
 * )
 */
class CustomerContractSignatureController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/contracts/sign",
     *     summary="Sign a contract",
     *     tags={"Contract Signatures"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SignContractRequest")
     *     ),
     *     @OA\Response(response=201, description="Successful operation"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function sign(SignContractRequest $request): JsonResponse
    {
        $signature = CustomerContractSignature::create($request->validated() + [
            'created_by_user_id' => auth()->id(),
            'status' => true,
        ]);

        return response()->json($signature, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contracts/signatures/{id}",
     *     summary="Get a specific contract signature",
     *     tags={"Contract Signatures"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Signature not found")
     * )
     */
    public function show(CustomerContractSignature $signature): JsonResponse
    {
        return response()->json($signature);
    }
}
