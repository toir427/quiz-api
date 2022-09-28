<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|moderator');
    }
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $surveys = (auth()->user()->hasRole(User::ROLE_ADMIN) ?
            Survey::with('questions')->orderBy('created_at', 'DESC'):
            auth()->user()->surveys()
        );
        return SurveyResource::collection(
            $surveys->paginate($request->get('per_page'))
        )->additional([
                          'status' => 200,
                          'success' => true,
                          'error' => null,
                      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SurveyRequest $request
     * @return SurveyResource
     */
    public function store(SurveyRequest $request)
    {
        $survey = auth()->user()->surveys()->create($request->all());

        return (new SurveyResource($survey))
            ->additional([
                             'status' => 200,
                             'success' => true,
                             'error' => null,
                         ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Survey $survey
     * @return SurveyResource
     */
    public function show(Survey $survey)
    {
        return (new SurveyResource($survey->loadMissing('questions.answers')))
            ->additional([
                             'status' => 200,
                             'success' => true,
                             'error' => null,
                         ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SurveyRequest $request
     * @param Survey $survey
     * @return SurveyResource
     * @throws \Throwable
     */
    public function update(SurveyRequest $request, Survey $survey)
    {
        DB::beginTransaction();
        try {
            $survey->update($request->except(['questions']));
            if ($request->has('questions')) {
                foreach ($request->get('questions') as $questionData) {
                    $answers = Arr::pull($questionData, 'answers', []);
                    if (isset($questionData['id'])) {
                        $question = $survey->questions()->find($questionData['id']);
                        $question->update($questionData);
                    } else {
                        $question = $survey->questions()->save(new Question($questionData));
                    }
                    /** @var Question $question */
                    if (!empty($answers)) {
                        foreach ($answers as $pos => $answerData) {
                            $answerData['position'] = $pos + 1;
                            if (isset($answerData['id'])) {
                                $answer = $question->answers()->find($answerData['id']);
                                $answer->update($answerData);
                            } else {
                                $answer = $question->answers()->save(new Answer($answerData));
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        $survey->refresh();
        return (new SurveyResource($survey->loadMissing(['questions.answers'])))
            ->additional([
                             'status' => 200,
                             'success' => true,
                             'error' => null,
                         ]);
    }

    public function sort(Request $request, Survey $survey)
    {
        try {
            foreach ($request->all() as $pos => $question) {
                if (isset($question['id'])) {
                    $survey->questions()->find($question['id'])->update(['position' => $pos + 1]);
                }
            }
        } catch (\Throwable $exception) {
            return \response()->json([
                                         'error' => [$exception->getMessage()],
                                         'result' => null,
                                         'success' => false,
                                         'status' => 400,
                                     ], 400);
        }
        return \response()->json([
                                     'result' => [],
                                     'error' => null,
                                     'success' => true,
                                     'status' => 200,
                                 ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Survey $survey
     * @return JsonResponse
     */
    public function destroy(Survey $survey)
    {
        try {
//            $survey->questions()->answers()->delete();
            $survey->questions()->delete();
            $survey->delete();
        } catch (\Throwable $exception) {
            return \response()->json([
                                         'error' => [$exception->getMessage()],
                                         'success' => false,
                                         'result' => null,
                                         'status' => Response::HTTP_CONFLICT,
                                     ], Response::HTTP_CONFLICT);
        }
        return response()->json([
                                    'error' => null,
                                    'success' => true,
                                    'status' => 200,
                                    'result' => null,
                                ]);
    }
}
