<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Lenders\Visits;
use App\Models\Admincp\Lenders\VisitsSorting;
use App\Models\Admincp\Settings;
use App\Models\Visitors\Visitors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Return the count of sessions in the last 30 days or the dates specified.
     *
     * @return JsonResponse
     */
    public function sessions(): JsonResponse
    {
        $lenders = Lenders::get();
        $lenders_array = [];

        $date[0] = request()->date_from;
        $date[1] = request()->date_to;

        $i = 1;

        foreach ($lenders as $lender) {
            $sessions = 0;
            $lenders_array[$i]['name'] = $lender->name;

            if (request()->date_from && request()->date_to) {
                $total_sessions = Visits::where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($date[0])))->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($date[1])))->where('bot', 0)->count();

                if (request()->position) {
                    $sessions = VisitsSorting::where('lender_id', $lender->id)
                        ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($date[0])))
                        ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($date[1])))
                        ->where('position', request()->position)
                        ->where('bot', 0)
                        ->get();

                    $lenders_array[$i]['sessions'] = count($sessions);
                } else {
                    $sessions = VisitsSorting::where('lender_id', $lender->id)
                        ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($date[0])))
                        ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime($date[1])))
                        ->where('bot', 0)
                        ->get();

                    $lenders_array[$i]['sessions'] = count($sessions);
                }

                $lenders_array[$i]['total_sessions'] = $total_sessions;
            } else {
                $total_sessions = Visits::where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-30 days')))->where('created_at', '<=', date('Y-m-d 23:59:59'))->where('bot', 0)->count();

                if (request()->position) {
                    $sessions = VisitsSorting::where('lender_id', $lender->id)
                        ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-30 days')))
                        ->where('created_at', '<=', date('Y-m-d 23:59:59'))
                        ->where('position', request()->position)
                        ->where('bot', 0)
                        ->get();

                    $lenders_array[$i]['sessions'] = count($sessions);
                } else {
                    $sessions = VisitsSorting::where('lender_id', $lender->id)
                        ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-30 days')))
                        ->where('created_at', '<=', date('Y-m-d 23:59:59'))
                        ->where('bot', 0)
                        ->get();

                    $lenders_array[$i]['sessions'] = count($sessions);
                }

                $lenders_array[$i]['total_sessions'] = $total_sessions;
            }

            $i++;
        }

        return response()->json($lenders_array);
    }

    /**
     * Return the count of visitors in the last 5 minutes.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function visitors(Request $request): JsonResponse
    {
        $api_key = $request->header('apikey');
        $settings = Settings::where('name', 'api_key')->where('value', $api_key)->exists();

        if ($settings) {
            $visitor_count = Visitors::where('updated_at', '>=', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                ->where('user_agent', 'not like', '%bot%')
                ->where('user_agent', 'not like', '%crawler%')
                ->get()
                ->count();

            return response()->json(['message' => $visitor_count]);
        } else {
            return response()->json(['message' => 'Forbidden'], 403);
        }
    }
}
