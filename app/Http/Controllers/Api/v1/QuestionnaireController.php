<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\QuestionnaireRequest;
use App\Http\Resources\QuestionnaireResource;
use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionnaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return QuestionnaireResource::collection(
            $request->user()->questionnaires()->paginate($request->get('per_page'))
        )->additional([
                          'status' => 200,
                          'success' => true,
                          'error' => null,
                      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestionnaireRequest $request
     * @return JsonResponse
     */
    public function store(QuestionnaireRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Question $question */
        $question = Question::find($request->get('question_id'));
        if ($question->answer_type === 1) {
            $user->answers()->updateOrCreate(
                ['question_id' => $request->get('question_id')],
                ['answer' => $request->get('answer')]
            );
        } elseif (is_array($request->get('answer_id'))) {
            $arr = [];
            foreach ($request->get('answer_id') as $item) {
                $arr[$item] = ['user_id' => $user->id];
            }
            $question->answered()->sync($arr);
        } else {
            $question->answered()->sync([$request->get('answer_id') => ['user_id' => $user->id]]);
        }

        return response()->json([
                                    'error' => null,
                                    'success' => true,
                                    'status' => 200,
                                    'result' => null,
                                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Survey $questionnaire
     * @return QuestionnaireResource
     */
    public function show(Survey $questionnaire)
    {
        return (new QuestionnaireResource($questionnaire->loadMissing(['questions.answers', 'questions.answered'])))
            ->additional([
                             'status' => 200,
                             'success' => true,
                             'error' => null,
                         ]);
    }
}
