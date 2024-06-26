<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use App\Http\Helpers\Helper;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\LessonResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Lesson::all();
        // $attendances = Attendance::all();
        // return AttendanceResource::collection($attendances);

        $lesson = Lesson::all();
        return LessonResource::collection($lesson);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LessonRequest $request)
    {
        //

        /** @var \App\Models\User */
        $user = Auth::user();
        
        if(!$user->hasRole('admin'))
        {


            if (!$user->staffs()->where('group_id', $request->group_id)->exists()) {
                return response()->json(['error' => 'You are not authorized to create a lesson for this group'], 403);
            }

        }







        // dd($request->all());
        $data = $request->validated();

        if (!Helper::isValidLessonTime($data['start_time'], $data['end_time'])) {
            return response()->json(['error' => 'Start time must be after 08:00:00, end time must be before 18:00:00, and end time must be greater than start time.'], 400);
        }

        try{
            $lesson = Lesson::create($data);
            return response()->json(['message' => 'Lesson  created successfully', 'group_id' => $lesson->group_id], 201, [], JSON_UNESCAPED_UNICODE);


    } catch (\Exception $e) {

        if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1062) {
            return response()->json(['error' => 'A lesson with the same day, action, and group already exists.'], 400);
        }

        return response()->json(['error' => $e->getMessage()], 400);
        // abort(400, $e->getMessage());
    }
    }


//      function isAfterEightAM(string $startTime): bool
// {
//   // Перетворення start_time в об'єкт DateTime
//   $startDateTime = Carbon::parse($startTime);

//   // Створення об'єкта DateTime для 08:00:00
//   $eightAM = Carbon::parse('08:00:00');

//   // Порівняння start_time з 08:00:00
//   return $startDateTime->isAfter($eightAM);
// }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // return Lesson::findOrFail($id);

        $lesson =Lesson::findOrFail($id);
    return new LessonResource($lesson);
    }



    public function indexByGroupAndDay( int $group_id, int $day_id)
    {
        $lessons = Lesson::where('group_id', $group_id)
            ->where('day_id', $day_id)
            ->get();

        // return response()->json(['lessons' => $lessons], 200);
        return LessonResource::collection($lessons);
    }


        public function indexByGroupForWeek(int $group_id)
    {
        $lessons = Lesson::where('group_id', $group_id)
            ->orderBy('day_id') // Спочатку сортуємо за днем тижня
            ->orderBy('start_time') // Потім сортуємо за часом початку
            ->get()
            ->groupBy('day_id'); // Групуємо уроки за днем тижня

        $schedule = [];

        for ($day_id = 1; $day_id <= 7; $day_id++) {
            $dayLessons = $lessons->get($day_id); // Отримуємо уроки для кожного дня тижня
            $schedule[$day_id] = $dayLessons ? LessonResource::collection($dayLessons) : [];
        }

        return $schedule;
    }







    /**
     * Update the specified resource in storage.
     */
    public function update(LessonRequest $request, Lesson $lesson)
    {
        //

        /** @var \App\Models\User */
        $user = Auth::user();
        
        if(!$user->hasRole('admin'))
        {


            if (!$user->staffs()->where('group_id', $request->group_id)->exists()) {
                return response()->json(['error' => 'You are not authorized to create a lesson for this group'], 403);
            }

        }





        $data = $request->validated();
        if (!Helper::isValidLessonTime($data['start_time'], $data['end_time'])) {
            return response()->json(['error' => 'Start time must be after 08:00:00, end time must be before 18:00:00, and end time must be greater than start time.'], 400);
        }

        try{
            $lesson->update($data);


        return new LessonResource($lesson);
    } catch (\Exception $e) {

        if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] === 1062) {
            return response()->json(['error' => 'A lesson with the same day, action, and group already exists.'], 400);
        }

        return response()->json(['error' => $e->getMessage()], 400);
    }

    }







    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        
         /** @var \App\Models\User */
         $user = Auth::user();
        
         if(!$user->hasRole('admin'))
         {
 
 
             if (!$user->staffs()->where('group_id', $lesson->group_id)->exists()) {
                 return response()->json(['error' => 'You are not authorized to create a lesson for this group'], 403);
             }
 
         }

        $lesson->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
