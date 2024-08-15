<?php

namespace App\Http\Controllers;

use App\Models\CustomerDdq;
use Illuminate\Http\Request;

class CustomerDdqController extends Controller
{
    //
    public function showDDQ($customer_id, $site_id, $group_id, $subgroup_id)
    {
        $ddq = CustomerDdq::where('customer_id', $customer_id)
            ->where('customer_site_id', $site_id)
            ->where('group_id', $group_id)
            ->where('subgroup_id', $subgroup_id)
            ->get();

        return response()->json($ddq);
    }

    public function destroy($id)
    {
        $ddq = CustomerDdq::findOrFail($id);
        $ddq->delete();
        return response()->json(null, 204);
    }

    public function approve($id)
    {
        $ddq = CustomerDdq::findOrFail($id);
        $ddq->status = 'approved';
        $ddq->save();
        return response()->json($ddq);
    }

    public function decline($id)
    {
        $ddq = CustomerDdq::findOrFail($id);
        $ddq->status = 'declined';
        $ddq->save();
        return response()->json($ddq);
    }
}
