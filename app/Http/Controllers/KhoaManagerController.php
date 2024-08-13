<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Lop;
use App\Models\Nganhs;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KhoaManagerController extends Controller
{
    function index()
    {
        return view('khoa_manager.index');
    }

    public function getData(Request $request)
    {
        $query = Khoa::with('nganhs');

        $query->when(
            $request->has('status_error')
                && $request->status_error !== 'all',
            function ($query) use ($request) {
                $query->where(
                    $request->status_error === 0 ? 'return_type' : null,
                    $request->status_error === 0 ? null
                        : $request->status_error
                );
            }
        );

        $data = $this->queryPagination($request, $query, []);

        return $data;
    }

    public function getDataChild($id)
    {
        try {
            $khoa = Khoa::with('nganhs')->findOrFail($id);

            return $khoa;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    function detele($id)
    {
        try {
            return Khoa::findOrFail($id)->delete();
        } catch (QueryException $e) {
            abort(404);
        }
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction(); 
        
            $khoa = Khoa::create($request->only([
                'name',
            ]));
            
            Nganhs::whereNull('khoa_id')
                ->whereIn('manganh', $request->nganh)
                ->update(['khoa_id' => $khoa->id]);
        
            DB::commit(); 
        
            return true;
        } catch (QueryException $e) {
            DB::rollBack(); 
            return abort(404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction(); 
        
            $khoa = Khoa::find($id);

            if (!$khoa) {
                return response()->json([
                    'message' => 'Not found',
                ], 404);
            }
    
            $khoa->update($request->only([
                'name',
            ]));
            
            Nganhs::where('khoa_id', $khoa->id)->update(['khoa_id' => null]);

            Nganhs::whereNull('khoa_id')
                ->whereIn('manganh', $request->nganh ?? [])
                ->update(['khoa_id' => $khoa->id]);
        
            DB::commit(); 
        
            return true;
        } catch (QueryException $e) {
            DB::rollBack(); 
            return abort(404);
        }
    }
    function importFile(Request $request)
    {
        if ($request->hasFile('csv_file')) {
            $data = $this->importCSV($request->file('csv_file'));
            DB::beginTransaction();
            try {
                foreach ($data['data'] as $index => $item) {
                    $khoa = new Khoa();
                    $khoa->name = $item[0];
                    $khoa->save();
                }
            } catch (\Throwable $th) {
                DB::rollback();
                abort(404);
            }
            DB::commit();
            return true;
        }
        abort(404);
        return true;
    }

    function nganh() {
        $nganh = Nganhs::whereNull('khoa_id')->get();
        return $nganh;
    }
    function nganhKhoa($id) {
        if(!isset($id))
        {
            return abort(404);
        }
        $nganh = Nganhs::where('khoa_id',$id)->get();
        return $nganh;
    }

    function lop($id) {
        if(!isset($id))
        {
            return abort(404);
        }
        $lop = Lop::where('lops.khoa_id',$id)
        ->leftJoin("nganhs", "lops.nganh_id", "=", "nganhs.manganh")
        ->select("lops.*","nganhs.tennganh as tennganh","nganhs.hedaotao as hedaotao")->get();
        return $lop;
    }
}
