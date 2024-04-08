<?php

    namespace App\Http\Controllers;

    use App\Models\Lop;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class ClassManagerController extends Controller
    {
        function index()
        {
            return view('class_manager.index');
        }

        public function getData(Request $request)
        {
            $query = Lop::query()
                ->leftJoin("khoas", "lops.khoa_id", "=", "khoas.id")
                ->select("lops.*", "khoas.name as khoa_name");

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
                $error = Lop::findOrFail($id);

                return $error;
            } catch (QueryException $e) {
                abort(404);
            }
        }

        function detele($id)
        {
            try {
                return Lop::findOrFail($id)->delete();
            } catch (QueryException $e) {
                abort(404);
            }
        }

        function create(Request $request)
        {
            try {
                Lop::create($request->only([
                    'name',
                    'nganh',
                    'khoa_id'
                ]));

                return true;
            } catch (QueryException $e) {
                abort(404);
            }
        }

        public function update(Request $request, $id)
        {
            $lop = Lop::find($id);

            if (!$lop) {
                return response()->json([
                    'message' => 'Not found',
                ], 404);
            }

            return $lop->update($request->only([
                'name',
                'nganh',
                'khoa_id'
            ]));
        }
    }
