<?php

namespace App\Http\Controllers;

use App\Models\CustomerDdq;
use Illuminate\Http\Request;

/**
 * Class CustomerDdqController
 *
 * Handles the operations related to Customer Due Diligence Questionnaires (DDQ).
 * Provides methods for viewing, deleting, approving, and declining DDQs.
 *
 * @package App\Http\Controllers
 */
class CustomerDdqController extends Controller
{
    // /**
    //  * Show a list of DDQs based on customer, site, group, and subgroup IDs.
    //  *
    //  * @param int $customer_id The ID of the customer.
    //  * @param int $site_id The ID of the customer site.
    //  * @param int $group_id The ID of the DDQ group.
    //  * @param int $subgroup_id The ID of the DDQ subgroup.
    //  * @return \Illuminate\Http\JsonResponse The JSON response containing the list of DDQs.
    //  */
    // public function showDDQ($customer_id, $site_id, $group_id, $subgroup_id)
    // {
    //     $ddq = CustomerDdq::where('customer_id', $customer_id)
    //         ->where('customer_site_id', $site_id)
    //         ->where('group_id', $group_id)
    //         ->where('subgroup_id', $subgroup_id)
    //         ->get();

    //     return response()->json($ddq);
    // }

    // /**
    //  * Delete a specific DDQ by ID.
    //  *
    //  * @param int $id The ID of the DDQ to delete.
    //  * @return \Illuminate\Http\JsonResponse The JSON response with HTTP status code 204 (No Content).
    //  * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ with the given ID is not found.
    //  */
    // public function destroy($id)
    // {
    //     $ddq = CustomerDdq::findOrFail($id);
    //     $ddq->delete();
    //     return response()->json(null, 204);
    // }

    // /**
    //  * Approve a specific DDQ by ID.
    //  *
    //  * @param int $id The ID of the DDQ to approve.
    //  * @return \Illuminate\Http\JsonResponse The JSON response containing the updated DDQ with status 'approved'.
    //  * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ with the given ID is not found.
    //  */
    // public function approve($id)
    // {
    //     $ddq = CustomerDdq::findOrFail($id);
    //     $ddq->status = 'approved';
    //     $ddq->save();
    //     return response()->json($ddq);
    // }

    // /**
    //  * Decline a specific DDQ by ID.
    //  *
    //  * @param int $id The ID of the DDQ to decline.
    //  * @return \Illuminate\Http\JsonResponse The JSON response containing the updated DDQ with status 'declined'.
    //  * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the DDQ with the given ID is not found.
    //  */
    // public function decline($id)
    // {
    //     $ddq = CustomerDdq::findOrFail($id);
    //     $ddq->status = 'declined';
    //     $ddq->save();
    //     return response()->json($ddq);
    // }
}
