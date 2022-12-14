<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function area()
    {
        $user = auth()->user();

        $areas = ['I', 'II', 'III', 'KAM'];
        $array_target = [];
        $array_progress = [];
        $array_percentage = [];

        for ($i = 0; $i < count($areas); $i++) {
            $target = (float)number_format((Sales::user($user)->rkap()->area($areas[$i])->sum('value') / 1000000), 2);
            $progress = (float)number_format((Sales::user($user)->rkap()->area($areas[$i])->level(1)->clean()->sum('value') / 1000000), 2);

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = ($target == 0) ? 0 : (float)number_format((($progress / $target) * 100), 2);
        }

        $data = [
            'pie' => $array_target,
            'bar' => [
                'target' => $array_target,
                'progress' => $array_progress,
                'percentage' => $array_percentage,
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

        $groups = [0, 1];
        $array_target = [];
        $array_progress = [];
        $array_percentage = [];

        for ($i = 0; $i < count($groups); $i++) {
            $target = (float)number_format((Sales::user($user)->rkap()->groupType($groups[$i])->sum('value') / 1000000), 2);
            $progress = (float)number_format((Sales::user($user)->rkap()->groupType($groups[$i])->level(1)->clean()->sum('value') / 1000000), 2);

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = ($target == 0) ? 0 : (float)number_format((($progress / $target) * 100), 1);
        }

        $data = [
            'pie' => $array_target,
            'bar' => [
                'target' => $array_target,
                'progress' => $array_progress,
                'percentage' => $array_percentage,
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

        $products = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $array_target = [];
        $array_progress = [];
        $array_percentage = [];

        for ($i = 0; $i < count($products); $i++) {
            $target = (float)number_format((Sales::user($user)->rkap()->product($products[$i])->sum('value') / 1000000), 2);
            $progress = (float)number_format((Sales::user($user)->rkap()->product($products[$i])->level(1)->clean()->sum('value') / 1000000), 2);

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = ($target == 0) ? 0 : (float)number_format((($progress / $target) * 100), 1);
        }

        $data = [
            'pie' => $array_target,
            'bar' => [
                'target' => $array_target,
                'progress' => $array_progress,
                'percentage' => $array_percentage,
            ],
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoTotal()
    {
        $user = auth()->user();

        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $array_target = [];
        $array_progress = [];
        $array_percentage = [];
        $array_gap = [];

        $total_target = 0;
        $total_progress = 0;

        for ($i = 1; $i <= 12; $i++) {
            if ($i < $month) {
                $total_days = Carbon::create()->day(1)->month($i)->year($year)->endOfMonth()->format('d');

                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($total_days)->month($i)->year($year)->format('Y-m-d'),
                ];
            } else if ($i == $month) {
                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($day)->month($i)->year($year)->format('Y-m-d'),
                ];
            }

            if ($i > $month) {
                $target = 0;
                $progress = 0;
            } else {
                $target = (float)number_format((Sales::user($user)->rkap()->month($i)->clean()->sum('value') / 1000000), 2);
                $progress = (float)number_format((Sales::user($user)->rkap()->filter($date_range)->level(1)->clean()->sum('value') / 1000000), 2);
            }

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
            $array_gap[] = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);

            $total_target += $target;
            $total_progress += $progress;
        }

        $array_target[] = (float)number_format($total_target, 2);
        $array_progress[] = (float)number_format($total_progress, 2);
        $array_percentage[] = $total_target == 0 ? 0 : (float)number_format((($total_progress / $total_target) * 100), 2);
        $array_gap[] = $total_target == 0 ? 0 : (float)number_format(($total_target - $total_progress), 2);

        $data = [
            'target' => $array_target,
            'progress' => $array_progress,
            'percentage' => $array_percentage,
            'gap' => $array_gap,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoGaruda()
    {
        $user = auth()->user();

        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $array_target = [];
        $array_progress = [];
        $array_percentage = [];
        $array_gap = [];

        $total_target = 0;
        $total_progress = 0;

        for ($i = 1; $i <= 12; $i++) {
            if ($i < $month) {
                $total_days = Carbon::create()->day(1)->month($i)->year($year)->endOfMonth()->format('d');

                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($total_days)->month($i)->year($year)->format('Y-m-d'),
                ];
            } else if ($i == $month) {
                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($day)->month($i)->year($year)->format('Y-m-d'),
                ];
            }

            if ($i > $month) {
                $target = 0;
                $progress = 0;
            } else {
                $target = (float)number_format((Sales::user($user)->rkap()->customerName('Garuda Indonesia')->month($i)->clean()->sum('value') / 1000000), 2);
                $progress = (float)number_format((Sales::user($user)->rkap()->customerName('Garuda Indonesia')->filter($date_range)->level(1)->clean()->sum('value') / 1000000), 2);
            }

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
            $array_gap[] = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);

            $total_target += $target;
            $total_progress += $progress;
        }

        $array_target[] = (float)number_format($total_target, 2);
        $array_progress[] = (float)number_format($total_progress, 2);
        $array_percentage[] = $total_target == 0 ? 0 : (float)number_format((($total_progress / $total_target) * 100), 2);
        $array_gap[] = $total_target == 0 ? 0 : (float)number_format(($total_target - $total_progress), 2);

        $data = [
            'target' => $array_target,
            'progress' => $array_progress,
            'percentage' => $array_percentage,
            'gap' => $array_gap,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }

    public function rofoCitilink()
    {
        $user = auth()->user();

        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $array_target = [];
        $array_progress = [];
        $array_percentage = [];
        $array_gap = [];

        $total_target = 0;
        $total_progress = 0;

        for ($i = 1; $i <= 12; $i++) {
            if ($i < $month) {
                $total_days = Carbon::create()->day(1)->month($i)->year($year)->endOfMonth()->format('d');

                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($total_days)->month($i)->year($year)->format('Y-m-d'),
                ];
            } else if ($i == $month) {
                $date_range = [
                    'start_date' => Carbon::create()->day(1)->month($i)->year($year)->format('Y-m-d'),
                    'end_date' => Carbon::create()->day($day)->month($i)->year($year)->format('Y-m-d'),
                ];
            }

            if ($i > $month) {
                $target = 0;
                $progress = 0;
            } else {
                $target = (float)number_format((Sales::user($user)->rkap()->customerName('Citilink Indonesia')->month($i)->clean()->sum('value') / 1000000), 2);
                $progress = (float)number_format((Sales::user($user)->rkap()->customerName('Citilink Indonesia')->filter($date_range)->level(1)->clean()->sum('value') / 1000000), 2);
            }

            $array_target[] = $target;
            $array_progress[] = $progress;
            $array_percentage[] = $target == 0 ? 0 : (float)number_format((($progress / $target) * 100), 2);
            $array_gap[] = $target == 0 ? 0 : (float)number_format(($target - $progress), 2);

            $total_target += $target;
            $total_progress += $progress;
        }

        $array_target[] = (float)number_format($total_target, 2);
        $array_progress[] = (float)number_format($total_progress, 2);
        $array_percentage[] = $total_target == 0 ? 0 : (float)number_format((($total_progress / $total_target) * 100), 2);
        $array_gap[] = $total_target == 0 ? 0 : (float)number_format(($total_target - $total_progress), 2);

        $data = [
            'target' => $array_target,
            'progress' => $array_progress,
            'percentage' => $array_percentage,
            'gap' => $array_gap,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data succesfully',
            'data' => $data,
        ], 200);
    }
}
