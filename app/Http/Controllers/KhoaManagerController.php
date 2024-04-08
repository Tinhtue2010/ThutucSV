<?php

    namespace App\Http\Controllers;

    use App\Models\Khoa;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class KhoaManagerController extends Controller
    {
        function index()
        {
            return view('khoa_manager.index');
        }

        public function getData(Request $request)
        {
            $query = Khoa::query();

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
                $error = Khoa::findOrFail($id);

                return [
                    'id'         => $error->id,
                    'name'       => $error->name,
                    'created_at' => $error->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $error->updated_at->format('Y-m-d H:i:s'),
                ];
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

        function create(Request $request)
        {
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            $model = new Khoa();

            $model->name = $request->input('name');
            $model->timestamps = true;
            $model->save();

            try {
                $model->save();
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Thêm khoa mới thành công !',
                    'data'    => $model,
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => 'Thêm khoa mới thất bại',
                    'error'   => $e->getMessage(),
                ], 500);
            }
        }
    }
