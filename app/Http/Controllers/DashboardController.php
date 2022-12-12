<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;

class DashboardController extends Controller
{
    public function area()
    {
        $user = auth()->user();

        $total_area1 = (float)number_format((Sales::user($user)->rkap()->area('I')->sum('value') / 1000000), 1);
        $total_area2 = (float)number_format((Sales::user($user)->rkap()->area('II')->sum('value') / 1000000), 1);
        $total_area3 = (float)number_format((Sales::user($user)->rkap()->area('III')->sum('value') / 1000000), 1);
        $total_kam = (float)number_format((Sales::user($user)->rkap()->area('KAM')->sum('value') / 1000000), 1);

        $progress_area1 = (float)number_format((Sales::user($user)->rkap()->area('I')->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_area2 = (float)number_format((Sales::user($user)->rkap()->area('II')->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_area3 = (float)number_format((Sales::user($user)->rkap()->area('III')->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_kam = (float)number_format((Sales::user($user)->rkap()->area('KAM')->level(1)->clean()->sum('value') / 1000000), 1);

        $data = [
            'pie' => [
                $total_area1,
                $total_area2,
                $total_area3,
                $total_kam,
            ],
            'bar' => [
                'area1' => [
                    'target' => $total_area1,
                    'progress' => $progress_area1,
                    'percentage' => (float)number_format((($progress_area1 / $total_area1) * 100), 1),
                ],
                'area2' => [
                    'target' => $total_area2,
                    'progress' => $progress_area2,
                    'percentage' => (float)number_format((($progress_area2 / $total_area2) * 100), 1),
                ],
                'area3' => [
                    'target' => $total_area3,
                    'progress' => $progress_area3,
                    'percentage' => (float)number_format((($progress_area3 / $total_area3) * 100), 1),
                ],
                'kam' => [
                    'target' => $total_kam,
                    'progress' => $progress_kam,
                    'percentage' => (float)number_format((($progress_kam / $total_kam) * 100), 1),
                ],
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function group()
    {
        $user = auth()->user();

        $total_group1 = (float)number_format((Sales::user($user)->rkap()->groupType(0)->sum('value') / 1000000), 1);
        $total_group2 = (float)number_format((Sales::user($user)->rkap()->groupType(1)->sum('value') / 1000000), 1);

        $progress_group1 = (float)number_format((Sales::user($user)->rkap()->groupType(0)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_group2 = (float)number_format((Sales::user($user)->rkap()->groupType(1)->level(1)->clean()->sum('value')
        / 1000000), 1);

        $data = [
            'pie' => [
                $total_group1,
                $total_group2,
            ],
            'bar' => [
                'group1' => [
                    'target' => $total_group1,
                    'progress' => $progress_group1,
                    'percentage' => (float)number_format((($progress_group1 / $total_group1) * 100), 1),
                ],
                'group2' => [
                    'target' => $total_group2,
                    'progress' => $progress_group2,
                    'percentage' => (float)number_format((($progress_group2 / $total_group2) * 100), 1),
                ],
            ],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }
}
