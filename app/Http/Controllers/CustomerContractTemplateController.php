<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\CustomerContractTemplate;
use App\Http\Requests\StoreTemplateRequest;
use App\Http\Requests\UpdateTemplateRequest;

/**
 * @OA\Tag(
 *     name="Contract Templates",
 *     description="API Endpoints of Contract Templates"
 * )
 */

class CustomerContractTemplateController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/contract-templates",
     *     summary="Get all contract templates",
     *     tags={"Contract Templates"},
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(): JsonResponse
    {
        $templates = CustomerContractTemplate::all();
        return response()->json($templates);
    }

    /**
     * @OA\Post(
     *     path="/api/contract-templates",
     *     summary="Create a new contract template",
     *     tags={"Contract Templates"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTemplateRequest")
     *     ),
     *     @OA\Response(response=201, description="Successful operation"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreTemplateRequest $request): JsonResponse
    {
        $template = CustomerContractTemplate::create($request->validated() + ['created_by_user_id' => auth()->id()]);
        return response()->json($template, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contract-templates/{id}",
     *     summary="Get a specific contract template",
     *     tags={"Contract Templates"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Template not found")
     * )
     */
    public function show(CustomerContractTemplate $template): JsonResponse
    {
        return response()->json($template);
    }

    /**
     * @OA\Put(
     *     path="/api/contract-templates/{id}",
     *     summary="Update a specific contract template",
     *     tags={"Contract Templates"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTemplateRequest")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Template not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateTemplateRequest $request, CustomerContractTemplate $template): JsonResponse
    {
        $template->update($request->validated());
        return response()->json($template);
    }

    /**
     * @OA\Delete(
     *     path="/api/contract-templates/{id}",
     *     summary="Delete a specific contract template",
     *     tags={"Contract Templates"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Successful operation"),
     *     @OA\Response(response=404, description="Template not found")
     * )
     */
    public function destroy(CustomerContractTemplate $template): JsonResponse
    {
        $template->delete();
        return response()->json(null, 204);
    }
}
