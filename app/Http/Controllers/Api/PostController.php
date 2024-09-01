<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
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
        //
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
        //
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
