<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Sales;
use App\Models\SalesRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpeg,jpg,png,pdf,eml|max:5120',
            'sales_id' => 'required|integer|exists:sales,id',
            'requirement_id' => 'required|integer|exists:requirements,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        try {
            DB::beginTransaction();

            $sales = Sales::find($request->sales_id);
            $requirement = $sales->salesRequirements->where('requirement_id', $request->requirement_id);
            $files = $request->file('files'); 
            $temp_paths = [];
            $temp_files = [];
            
            if ($requirement->isEmpty()) {
                $requirement = new SalesRequirement;
                $requirement->sales_id = $sales->id;
                $requirement->requirement_id = $request->requirement_id;
                $requirement->status = 1;
                $requirement->save();
            } else {
                if ($requirement->count() > 1) {
                    foreach ($requirement as $item) {
                        if ($requirement->count() > 1) {
                            $item->delete();
                        }
                    }
                }
                $requirement = $requirement->first();
                $requirement->status = 1;
                $requirement->push();
            }
            
            foreach ($files as $file) {
                $file_path = Storage::disk('public')->putFile('attachment', $file);
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
            ], 400);
        }
    }

    public function show($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 400);
        }

        // TODO perlu konfirmasi -> format penamaan file yg akan didownload user
        $filename = Str::remove('attachment/', $file->path);

        $headers = [
            'Content-Type'        => 'image/png',            
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return \Response::make(Storage::disk('public')->get($file->path), 200, $headers);
    }

    public function destroy($id)
    {
        $file = File::find($id);
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found',
            ], 400);
        }

        $requirement = $file->salesRequirement;
        $files = $requirement->files;

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        $file->delete();
        
        $requirement->status = $files->isNotEmpty() ?? 0;
        $requirement->push();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully',
        ], 200);
    }
}
