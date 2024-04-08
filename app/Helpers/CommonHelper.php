<?php

namespace App\Helpers;


trait CommonHelper{
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
                        $query->orWhere($field, 'like', '%'.$search.'%');
                    }
                });
            }
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

            return $data;
        } catch (QueryException $e) {
            abort(404);
        }
    }
}