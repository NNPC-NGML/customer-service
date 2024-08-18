<?php

namespace App\Http\Controllers;

use App\Services\CustomerSiteSurveyFindingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


/**
 * @OA\Tag(
 *     name="Survey Findings",
 *     description="API Endpoints of Survey Findings"
 * )
 */
class CustomerSiteSurveyFindingController extends Controller
{
    protected $surveyFindingService;

    public function __construct(CustomerSiteSurveyFindingService $surveyFindingService)
    {
        $this->surveyFindingService = $surveyFindingService;
    }


    /**
     * @OA\Get(
     *     path="/api/survey-findings/{id}",
     *     tags={"Survey Findings"},
     *     summary="Get a survey finding by ID",
     *     description="Returns a single survey finding",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the survey finding to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SurveyFinding")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Survey finding not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $surveyFinding = $this->surveyFindingService->getSurveyFindingById($id);
        return response()->json($surveyFinding);
    }
}
