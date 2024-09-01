<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * Class constructor.
     */
    public function __construct(protected PostService $postService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $datas = $this->postService->get();
            return $this->successResponse($datas, 'Successfully to Get data', 200);
        } catch (\Exception $e) {

            return $this->errorResponse(null, "'Failed to Get data : {$e->getMessage()}", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        try {
            $this->postService->store($request);
            return $this->successResponse(null, 'Successfully to create data', 200);
        } catch (\Exception $e) {

            return $this->errorResponse(null, "'Failed to create data : {$e->getMessage()}", 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $datas = $this->postService->get($id);
            return $this->successResponse($datas, 'Successfully to Get data', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(null, "Data Not found", 404);
        } catch (\Exception $e) {

            return $this->errorResponse(null, "'Failed to Get data : {$e->getMessage()}", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
