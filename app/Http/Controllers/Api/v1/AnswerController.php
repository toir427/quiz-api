<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerRequest;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnswerController extends Controller
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
        return AnswerResource::collection(Answer::all())
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AnswerRequest $request
     * @param Question $question
     * @return AnswerResource
     */
    public function store(AnswerRequest $request, Question $question)
    {
        $answer = $question->answers()->create($request->all());

        return (new AnswerResource($answer))
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Answer $answer
     * @return AnswerResource
     */
    public function show(Answer $answer)
    {
        return (new AnswerResource($answer))
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Answer $answer
     * @return AnswerResource
     */
    public function update(Request $request, Answer $answer)
    {
        $answer->update($request->all());

        return (new AnswerResource($answer))
            ->additional([
                'status' => 200,
                'success' => true,
                'error' => null,
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Answer $answer
     * @return JsonResponse
     */
    public function destroy(Answer $answer)
    {
        $result = $answer->delete();

        return response()->json([
            'status' => 200,
            'result' => $result,
            'success' => true,
            'error' => null
        ]);
    }
}
