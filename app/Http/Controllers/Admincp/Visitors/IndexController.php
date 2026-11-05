<?php

namespace App\Http\Controllers\Admincp\Visitors;

use App\Http\Controllers\Controller;
use App\Models\Clicks;
use App\Models\Visitors\Visitors;
use Jenssegers\Agent\Agent;

class IndexController extends Controller
{
    /**
     * Show the lenders index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $visitorsWithOutCountyCode = collect($this->getAllVisitors()->items())->where('country', null);
        if ($visitorsWithOutCountyCode->count() > 0) {
            foreach ($visitorsWithOutCountyCode as $visitor) {
                $curl = curl_init();

                $optArray = [
                    CURLOPT_URL => 'https://geoip.goodday.group/?ip=' . $visitor->ip,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT => 5,
                ];

                curl_setopt_array($curl, $optArray);
                $result = curl_exec($curl);

                if (curl_errno($curl) == 28) {
                    break;
                }

                $result = json_decode($result);

                if ($result) {
                    $visitor->update(['country' => $result->countryCode]);
                }
            }
        }
        return view('admincp.visitors.index')->with([
            'visitors' => $this->getAllVisitors(),
            'agent' => new Agent(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function visitor($visitor_id)
    {
        return view('admincp.visitors.visitor')->with([
            'visitor' => $this->getVisitorById($visitor_id),
            'clicks' => $this->getClicksById($visitor_id),
            'agent' => new Agent(),
        ]);
    }

    /**
     * @return mixed
     */
    public function getAllVisitors()
    {
        if (request()->search) {
            $records = Visitors::where('id', request()->search)->orderBy('id', 'DESC')->paginate(50);
            if (count($records) == 0) {
                $records = Visitors::where('ip', request()->search)->orderBy('id', 'DESC')->paginate(50);
            }
        } else {
            $records = Visitors::orderBy('id', 'DESC')->paginate(20);
        }

        return $records;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getVisitorById($id)
    {
        return Visitors::where('id', $id)->first();
    }

    public function getClicksById($id)
    {
        return Clicks::where('visitor_id', $id)->orderBy('id', 'DESC')->paginate(50);
    }
}
