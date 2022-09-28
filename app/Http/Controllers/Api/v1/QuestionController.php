<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return QuestionResource::collection(Question::all())
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestionRequest $request
     * @param Survey $survey
     * @return QuestionResource
     */
    public function store(QuestionRequest $request, Survey $survey)
    {
        $question = $survey->questions()->create($request->all());

        return (new QuestionResource($question))
            ->additional([
                'error' => null,
                'success' => true,
                'status' => 200,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return QuestionResource
     */
    public function show(Question $question)
    {
        return (new QuestionResource($question))
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestionRequest $request
     * @param Question $question
     * @return QuestionResource
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->update($request->all());

        return (new QuestionResource($question))
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return JsonResponse
     */
    public function destroy(Question $question)
    {
        $result = $question->delete();

        return response()->json([
            'status' => 200,
            'success' => true,
            'error' => null,
            'result' => $result
        ]);
    }
}
