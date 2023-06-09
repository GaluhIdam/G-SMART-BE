<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Sales;
use App\Models\SalesRequirement;
use App\Models\SalesLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Http\Requests\FileRequest;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $requirement = $request->requirement;

        $files = File::when($requirement, function ($query) use ($requirement) {
                            $query->where('sales_requirement_id', $requirement);
                        })->get();

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $files,
        ], 200);
    }

    public function store(FileRequest $request)
    {
        try {
            DB::beginTransaction();

            $sales = Sales::find($request->sales_id);
            $requirement_id = $request->requirement_id;
            $files = $request->file('files');
            $temp_paths = [];
            $temp_files = [];
            
            $requirement = $sales->setRequirement($requirement_id);

            if ($requirement_id == 9 ) {
                $sales_level = $sales->salesLevel;
                $sales_level->status = 2;
                $sales_level->save();
            }

            foreach ($files as $file) {
                $file_name = Carbon::now()->format('dmyHis').'_'.$file->getClientOriginalName();
                $file_path = Storage::disk('public')->putFileAs('attachment', $file, $file_name);
                $temp_paths[] = $file_path;

                $new_file = new File;
                $new_file->sales_requirement_id = $requirement->id;
                $new_file->path = $file_path;
                $new_file->save();

                $temp_files[] = $new_file;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => $temp_files,
            ], 200);
        } catch (QueryException $e) {
            DB::rollback();

            for ($i = 0; $i < count($temp_paths); $i++) {
                Storage::disk('public')->delete($temp_paths[$i]);
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $file = File::find($id);

        if (!$file || !$file->path) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $headers = [
            'Content-Type' => $file->content_type,            
            'Content-Disposition' => 'attachment; filename="'.$file->file_name.'"',
        ];

        return \Response::make(Storage::disk('public')->get($file->path), 200, $headers);
    }

    public function history($sales_id, Request $request)
    {
        $sales = Sales::find($sales_id);
        $filter = $request->filter;

        if (!$sales) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $requirements = $sales->salesRequirements->whereNotIn('requirement_id', [1, 4, 10]);
        $requirement_ids = [];
        
        foreach ($requirements as $item) {
            $requirement_ids[] = $item->id;
        }

        $files = File::whereIn('sales_requirement_id', $requirement_ids)
                        ->when($filter, function ($query) use ($filter) {
                            $query->whereMonth("updated_at", $filter);
                        })
                        ->get()
                        ->groupBy(function ($item) {
                            return $item->updated_at->format('d F Y');
                        })
                        ->take(10);

        $file_histories = [];
        foreach ($files as $key => $file) {
            $date = $key;
            $file_histories[] = [
                'uploadedAt' => $date,
                'totalFiles' => $file->count(),
                'files' => $file,
            ];
        }

        $month = Carbon::create()->day(1)->month($filter)->year(Carbon::now()->format('Y'));

        $data = collect([
            'month' => $month->format('F Y'),
            'history' => collect($file_histories)->sortByDesc('uploadedAt')->values(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Retrieve data successfully',
            'data' => $data,
        ], 200);
    }

    public function destroy($id)
    {
        $file = File::find($id);
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 404);
        }

        $requirement = $file->salesRequirement;
        
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        $files = $requirement->files;
        $requirement->status = $files->isNotEmpty() ?? 0;
        $requirement->push();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully',
        ], 200);
    }
}
