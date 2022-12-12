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

    public function product()
    {
        $user = auth()->user();

        $total_learning = (float)number_format((Sales::user($user)->rkap()->product(1)->sum('value') / 1000000), 1);
        $total_igte = (float)number_format((Sales::user($user)->rkap()->product(2)->sum('value') / 1000000), 1);
        $total_others = (float)number_format((Sales::user($user)->rkap()->product(3)->sum('value') / 1000000), 1);
        $total_engapu = (float)number_format((Sales::user($user)->rkap()->product(4)->sum('value') / 1000000), 1);
        $total_material = (float)number_format((Sales::user($user)->rkap()->product(5)->sum('value') / 1000000), 1);
        $total_line = (float)number_format((Sales::user($user)->rkap()->product(6)->sum('value') / 1000000), 1);
        $total_engineering = (float)number_format((Sales::user($user)->rkap()->product(7)->sum('value') / 1000000), 1);
        $total_component = (float)number_format((Sales::user($user)->rkap()->product(8)->sum('value') / 1000000), 1);
        $total_airframe = (float)number_format((Sales::user($user)->rkap()->product(9)->sum('value') / 1000000), 1);

        $progress_learning = (float)number_format((Sales::user($user)->rkap()->product(1)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_igte = (float)number_format((Sales::user($user)->rkap()->product(2)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_others = (float)number_format((Sales::user($user)->rkap()->product(3)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_engapu = (float)number_format((Sales::user($user)->rkap()->product(4)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_material = (float)number_format((Sales::user($user)->rkap()->product(5)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_line = (float)number_format((Sales::user($user)->rkap()->product(6)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_engineering = (float)number_format((Sales::user($user)->rkap()->product(7)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_component = (float)number_format((Sales::user($user)->rkap()->product(8)->level(1)->clean()->sum('value') / 1000000), 1);
        $progress_airframe = (float)number_format((Sales::user($user)->rkap()->product(9)->level(1)->clean()->sum('value') / 1000000), 1);

        $data = [
            'pie' => [
                $total_learning,
                $total_igte,
                $total_others,
                $total_engapu,
                $total_material,
                $total_line,
                $total_engineering,
                $total_component,
                $total_airframe,
            ],
            'bar' => [
                'learning' => [
                    'target'     => $total_learning,
                    'progress'   => $progress_learning,
                    'percentage' => (float)number_format((($progress_learning / $total_learning) * 100), 1),
                ],
                'igte' => [
                    'target'     => $total_igte,
                    'progress'   => $progress_igte,
                    'percentage' => (float)number_format((($progress_igte / $total_igte) * 100), 1),
                ],
                'others' => [
                    'target'     => $total_others,
                    'progress'   => $progress_others,
                    'percentage' => (float)number_format((($progress_others / $total_others) * 100), 1),
                ],
                'engapu' => [
                    'target'     => $total_engapu,
                    'progress'   => $progress_engapu,
                    'percentage' => (float)number_format((($progress_engapu / $total_engapu) * 100), 1),
                ],
                'material' => [
                    'target'     => $total_material,
                    'progress'   => $progress_material,
                    'percentage' => (float)number_format((($progress_material / $total_material) * 100), 1),
                ],
                'line' => [
                    'target'     => $total_line,
                    'progress'   => $progress_line,
                    'percentage' => (float)number_format((($progress_line / $total_line) * 100), 1),
                ],
                'engineering' => [
                    'target'     => $total_engineering,
                    'progress'   => $progress_engineering,
                    'percentage' => (float)number_format((($progress_engineering / $total_engineering) * 100), 1),
                ],
                'component' => [
                    'target'     => $total_component,
                    'progress'   => $progress_component,
                    'percentage' => (float)number_format((($progress_component / $total_component) * 100), 1),
                ],
                'airframe' => [
                    'target'     => $total_airframe,
                    'progress'   => $progress_airframe,
                    'percentage' => (float)number_format((($progress_airframe / $total_airframe) * 100), 1),
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
