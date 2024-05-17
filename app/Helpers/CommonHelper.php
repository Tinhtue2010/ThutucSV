<?php

namespace App\Helpers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait CommonHelper
{
    public function importCSV($file)
    {
        $path = $file->getRealPath();

        $file = fopen($path, 'r');
        $row = 1;
        $header = [];
        $datas = [];
        while (($data = fgetcsv($file, 9000000, ',')) !== false) {
            if ($data !== null) {
                $i = 0;
                $tmpData = [];
                while (isset($data[$i])) {
                    if ($row == 1) {
                        $header[] = $data[$i];
                    } else {
                        try {
                            $tmpData[] = $data[$i];
                        } catch (Exception $e) {
                            break;
                        }
                    }
                    $i++;
                }
                if ($row > 1) {
                    $datas[] = $tmpData;
                }
                $row++;
            } else {
                break;
            }
        }
        fclose($file);
        $dataFile['header'] = $header;
        $dataFile['data'] = $datas;

        return $dataFile;
    }
    public function queryPagination($request, $query, $searchName = [])
    {
        $per_page = $request->per_page ?? 10;
        $page = $request->page ?? 1;
        $offset = ($page - 1) * $per_page;
        $nameOrder = $request->order_name ?? null;
        $order_by = $request->order_by ?? null;
        $search = $request->search ?? '';

        try {
            if (isset($nameOrder) && isset($order_by)) {
                if ($order_by == 'ASC' || $order_by == 'DESC' || $order_by == 'asc' || $order_by == 'desc') {
                    $query = $query->orderBy($nameOrder, $order_by);
                }
            }
            if ($searchName !== [] && $search != '') {
                $query = $query->where(function ($query) use ($searchName, $search) {
                    foreach ($searchName as $field) {
                        $query->orWhere($field, 'like', '%' . $search . '%');
                    }
                });
            }
            if($per_page == 'all')
            {
                $query = $query->get()->toArray();
    
                $data['max_page'] = 1;
                $data['data'] = $query;
                $data['page'] = 1;
            }
            else
            {
                $max_page = clone $query;
                $max_page = ceil($max_page->count() / $per_page);
                if ($page > $max_page) {
                    $page = 1;
                    $offset = 0;
                }
                $query = $query->skip($offset)->take($per_page)->get()->toArray();
    
                $data['max_page'] = $max_page;
                $data['data'] = $query;
                $data['page'] = $page;
            }

            return $data;
        } catch (QueryException $e) {
            abort(404);
        }
    }

    public function notification($notification, $phieu = null, $type = null, $user_id = null)
    {
        $query = new Notification();
        $query->notification = $notification;
        $query->phieu_id = $phieu;
        $query->type = $type;
        $query->user_id = $user_id ?? Auth::user()->id;
        $query->save();
    }


    public function uploadListFile($request, $name, $folder)
    {
        $fileNames = [];

        if ($request->hasFile($name)) {
            foreach ($request->file($name) as $file) {
                $extension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $newFileName = $folder . '/' . date('Y-m-d') . '-' . uniqid() .'.'. $extension;
                Storage::putFileAs('public', $file, $newFileName);
                $fileNames[] = [$originalName,$newFileName];
            }
            return $fileNames;
        } else {
            return [];
        }
    }

    function deleteFiles($fileNames)
    {
        foreach ($fileNames as $fileName) {
            Storage::delete('public/' . $fileName[1]);
        }
        return true;
    }
}
