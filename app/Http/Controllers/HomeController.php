<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolPackage;
use App\Models\School;
use App\Models\User;
use App\Models\Course;
use App\Models\Event;
use App\Models\SchoolClass;
use App\Models\StudentResult;
use App\Models\Attendance;
use App\Models\StudentResultComment;
use App\Models\Lesson;
use App\Models\LessonTransaction;
use App\Models\Curriculum;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\UserPackage;
use App\Models\Qualification;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Mail\GuardianConfirmation;
use App\Mail\GuardianAddedYouAsWard;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    private  $stopwords = [
        'a', 'an', 'and', 'the', 'is', 'was', 'were', 'will', 'would', 'should',
        'to', 'from', 'in', 'on', 'at', 'by', 'with', 'for', 'of', 'about', 'as',
        'at', 'before', 'after', 'between', 'through', 'over', 'under', 'above', 'below',
        'such', 'some', 'any', 'no', 'not', 'only', 'just', 'even', 'more', 'most', 'less',
        'very', 'too', 'up', 'down', 'out', 'off', 'into', 'onto', 'up', 'down', 'throughout',
        'there', 'here', 'where', 'when', 'how', 'why', 'which', 'what', 'who', 'whom', 'whose',
        'that', 'these', 'those', 'this', 'those', 'thus', 'than', 'while', 'because', 'unless',
        'until', 'since', 'so', 'such', 'though', 'although', 'while', 'whether', 'either', 'neither',
        'nor', 'either', 'both', 'some', 'any', 'all', 'most', 'several', 'many', 'few', 'fewer', 'every',
        'each', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
        'first', 'second', 'third', 'fourth', 'fifth', 'last', 'next', 'previous', 'new', 'old',
        'good', 'bad', 'better', 'best', 'worse', 'worst', 'high', 'low', 'highly', 'lowly',
        'large', 'small', 'larger', 'smaller', 'largest', 'smallest', 'little', 'few', 'lot', 'many',
        'much', 'less', 'more', 'most', 'least', 'part', 'whole', 'along', 'amid', 'among', 'away',
        'back', 'forth', 'front', 'back', 'half', 'most', 'least', 'both', 'either', 'neither', 'over',
        'under', 'through', 'against', 'behind', 'beyond', 'below', 'above', 'within', 'without', 'upon',
        'along', 'around', 'among', 'apart', 'from', 'around', 'because', 'since', 'so', 'thus', 'therefore',
        'hence', 'however', 'also', 'then', 'thereupon', 'otherwise', 'instead', 'beside', 'furthermore',
        'meanwhile', 'subsequently', 'finally', 'earlier', 'later', 'next', 'above', 'below', 'before',
        'after', 'near', 'far', 'by', 'past', 'into', 'across', 'through', 'along', 'around', 'onto',
        'toward', 'with', 'without', 'within', 'among', 'against', 'upon', 'of', 'out', 'down', 'under',
        'up', 'over', 'for', 'against', 'along', 'together', 'apart', 'aside', 'between', 'during', 'since',
        'yet', 'still', 'already', 'till', 'until', 'when', 'while', 'as', 'although', 'because', 'since',
        'though', 'if', 'whether', 'unless', 'even', 'just', 'only', 'both', 'either', 'neither', 'one',
        'other', 'another', 'each', 'every', 'some', 'any', 'all', 'none', 'more', 'most', 'few', 'fewer',
        'little', 'less', 'much', 'many', 'such', 'so', 'there', 'here', 'where', 'when', 'how', 'why',
        'what', 'which', 'who', 'whom', 'whose', 'those', 'this', 'that', 'these', 'them', 'him', 'her',
        'us', 'you', 'me', 'my', 'your', 'his', 'her', 'our', 'its', 'their', 'whoever', 'whatever', 'whichever',
        'who', 'what', 'whom', 'whose', 'which', 'where', 'when', 'why', 'how', 'one', 'two', 'three', 'four',
        'five', 'six', 'seven', 'eight', 'nine', 'ten', 'first', 'second', 'third', 'fourth', 'fifth', 'last',
        'own', 'same', 'other', 'another', 'such', 'certain', 'sure', 'less', 'least', 'most', 'more', 'every',
        'each', 'few', 'many', 'some', 'most', 'no', 'any', 'all', 'few', 'some', 'much', 'more', 'several',
        'only', 'just', 'well', 'also', 'even', 'still', 'back', 'below', 'above', 'through', 'behind',
        'around', 'along', 'across', 'past', 'after', 'before', 'next', 'near', 'far', 'into', 'onto',
        'over', 'under', 'upon', 'within', 'without', 'between', 'among', 'against', 'during', 'since',
        'because', 'before', 'after', 'when', 'while', 'so', 'though', 'although', 'if', 'unless', 'until',
        'so', 'thus', 'therefore', 'hence', 'thereupon', 'then', 'yet', 'already', 'now', 'just', 'back',
        'forth', 'toward', 'with', 'within', 'while', 'whereas', 'where', 'here', 'there', 'both', 'either',
        'neither', 'only', 'also', 'once', 'twice', 'three', 'four', 'five', 'six', 'seven', 'eight',
        'nine', 'ten', 'hundred', 'thousand', 'million', 'billion', 'first', 'second', 'third', 'fourth',
        'fifth', 'last', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
    'first', 'second', 'third', 'fourth', 'fifth', 'last', 'next', 'previous', 'new', 'old',];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(auth()->user()->schoolClass()->scholarships());
        $showRoleSelectionModal = session('showRoleSelectionModal', false);
        $totalPackagesCount = SchoolPackage::all()->count();
        $totalSchoolsCount = School::all()->count();
        $totalCurriculumCount = Curriculum::all()->count();
        $totalAcademicSessionCount = AcademicSession::all()->count();
        $students = null;
        $totalUserPackagesCount = UserPackage::all()->count();
        $school = null;
        $sorted_events = [];
        $sorted_lessons = [];
        $uniqueSubjectNames = Course::getAllUniqueSubjects();

        // Retrieve the authenticated user's school
        if (auth()->user()->hasProfile() && auth()->user()->profile->role != 'super_admin') {
            $profile = auth()->user()->profile;
            $userRole = $profile->role;
            $school = auth()->user()->school;

            // Retrieve all events and lessons
            $events = Event::all();
            $lessons = Lesson::all();
            // dd($lessons->count());

            // Sort events by rank
            $sorted_events = $events->sortByDesc(function ($event) use ($school, $userRole) {
                return $this->calculateEventRank($event, $school, $userRole);
            });

            // Sort lessons by rank and include rank score with each lesson
            $sorted_lessons = $lessons->map(function ($lesson) use ($school, $userRole) {
                $rank = $this->calculateLessonRank($lesson, $school, $userRole, auth()->user());
                $lesson->rank = $rank; // Include the rank score with the lesson object
                return $lesson;
            })->sortByDesc('rank')->values(); // Ensure a re-indexed array

            // Retrieve the top 6 lessons and events based on rank
            $top_events = $sorted_events->take(2);
            $top_lessons = $sorted_lessons->take(6);

            // Pass the sorted events, lessons, and other variables to the view
            return view('home', compact(
                'showRoleSelectionModal',
                'totalPackagesCount',
                'totalSchoolsCount',
                'totalUserPackagesCount',
                'school',
                'top_events',
                'top_lessons',
                'students',
                'totalCurriculumCount',
                'totalAcademicSessionCount',
                'uniqueSubjectNames'
            ));
        }

        // Pass the default variables to the view if conditions are not met
        return view('home', compact(
            'showRoleSelectionModal',
            'totalPackagesCount',
            'totalSchoolsCount',
            'totalUserPackagesCount',
            'school',
            'sorted_events',
            'sorted_lessons',
            'students',
            'totalCurriculumCount',
            'totalAcademicSessionCount',
            'uniqueSubjectNames'
        ));
    }
    public function dashboard()
    {
        $user = auth()->user();
    
        if (!$user) {
            return redirect('login');
        }
    
        $profile = $user->profile;
    
        if (!$profile) {
            return redirect('home');
        }
    
        // Initialize common data
        $viewed_lessons = $user->enrolledLessons()->latest()->take(6)->get();
        $fav_lessons = $user->favoriteLessons()->latest()->take(2)->get();
        $school = $user->school;
        $uniqueSubjectNames = Course::getAllUniqueSubjects();
    
        $data = [
            'user'=>$user,
            'school' => $school,
            'viewed_lessons' => $viewed_lessons,
            'fav_lessons' => $fav_lessons,
            'uniqueSubjectNames' => $uniqueSubjectNames,
            'wallet_balance' => $user->wallet ? $user->wallet->balance : 0, // Add wallet balance to data
            'has_wallet' => $user->wallet ? true : false, // Add has_wallet flag
        ];
    
        // Check if the user has a class and get the previous class level
        $classAndPrevClassLevelDict = [
            'jss_two' => 'jss_one',
            'jss_three' => 'jss_two',
            'sss_one' => 'jss_three',
            'sss_two' => 'sss_one',
            'sss_three' => 'sss_two',
        ];
    
        if ($user->schoolClass()) {
            $currentClassLevel = $user->schoolClass()->class_level;
            $previousClassLevel = $classAndPrevClassLevelDict[$currentClassLevel] ?? null;
            $data['previous_class_level'] = $previousClassLevel;
        }
    
        $role = $profile->role;
        $dashboardpage = "dashboard.$role" . "_dashboard";
    
        if ($role === 'teacher' || $role === 'admin') {
            $lessons = $user->lessons()->withCount('enrolledUsers')->get();
    
            $lessonAnalyticsData = $lessons->map(function ($lesson) use ($user) {
                return $this->calculateLessonAnalytics($lesson, $user);
            });
    
            $data['lessonAnalyticsData'] = $lessonAnalyticsData;
    
        } elseif ($role === 'school_owner') {
            $schools = $user->ownedSchools()->get();
            $latest_academic_session = AcademicSession::latest()->first();
            $latest_term = Term::latest()->first();
    
            $data['schools'] = $schools;
            $data['latest_academic_session'] = $latest_academic_session;
            $data['latest_term'] = $latest_term;
        }
    
        return view($dashboardpage, $data);
    }
    
    

    private function calculateLessonAnalytics($lesson, $user)
    {
        // Fetch the total earnings from LessonTransaction for the lesson
        $totalEarnings = LessonTransaction::where('lesson_id', $lesson->id)
            ->sum('amount');

        // Fetch the teacher's earnings from LessonTransaction for the lesson
        $teacherEarnings = LessonTransaction::where('lesson_id', $lesson->id)
            ->where('user_id', $lesson->user_id)
            ->where('type', 'teacher_earnings')
            ->sum('amount');

        // Fetch the school's earnings from LessonTransaction for the lesson
        $schoolEarnings = LessonTransaction::where('lesson_id', $lesson->id)
            ->where('school_id', $lesson->school_id)
            ->where('type', 'school_earnings')
            ->sum('amount');

        return [
            'title' => $lesson->title,
            'views' => $lesson->enrolled_users_count,
            'lesson_earnings' => $totalEarnings,
            'teacher_earnings' => $teacherEarnings,
            'school_earnings' => $schoolEarnings,
        ];
    }


    private function calculateLessonRank($lesson, $school, $userRole, $user)
    {
        $rank = 0;
        if($userRole !==  'super_admin' && $userRole !== 'school_owner'){

        // Consider relevance to the user based on school, class level, and subjects
       if ($school) {
            if ($lesson->school_id == $school->id) {
                // Lesson is related to the user's school, give it a higher rank
                $rank += 20;
            }
       }

        // Check if user is a student and the lesson's class level matches user's school class level
        if ($userRole === 'student' && $user->schoolClass() && $lesson->class_level == $user->schoolClass()->class_level) {
            // Lesson's class level matches the user's school class level, give it a higher rank
            $rank += 30;
        }

        // Check if user is a student and the lesson's subject matches user's enrolled subjects
        if ($userRole === 'student' && $user->student_courses->contains('subject', $lesson->subject)) {
            // Lesson's subject matches user's enrolled subjects, give it a higher rank
            $rank += 40;
        }

        // Adjust rank based on the inverse of school_connects_required of the lesson
        $rank += $lesson->school_connects_required;

        // Check if user is a student and the lesson is already enrolled
        if ($userRole === 'student' && $user->enrolledLessons->contains('id', $lesson->id)) {
            // Lesson is already enrolled by the user, further reduce its rank
            $rank -= 20;
        }

        // Check if user is a teacher and the lesson's subject matches teacher's assigned subjects
        if ($userRole === 'teacher' && $user->teacher_courses->contains('subject', $lesson->subject)) {
            // Lesson's subject matches teacher's assigned subjects, give it a higher rank
            $rank += 40;
        }
        }

        return max($rank, 0); // Ensure rank is not negative
    }

    
    
    private function calculateEventRank($event, $school, $userRole)
    {
        // dd($userRole);
        $rank = 0;
        if($userRole !==  'super_admin' && $userRole !== 'school_owner'){
    
        // Consider the event dates for ranking
        $current_date = now();
        $start_date = $event->start_date;
        $end_date = $event->end_date;
    
        if ($current_date >= $start_date && $current_date <= $end_date) {
            // Event is ongoing, give it a higher rank
            $rank += 50;
        } elseif ($current_date < $start_date) {
            // Event is in the future, give it a lower rank
            $rank += 30;
        } else {
            // Event has passed, give it a very low rank
            $rank += 5;
        }
        if ($school) {
            // Consider relevance to the user based on school
            if ($event->school_id == $school->id) {
                // Event is related to the user's school, give it a higher rank
                $rank += 20;
            }
        
            // Check if the event's academic session matches the school's academic session
            if ($event->academicSession && $school->academicSession && $event->academicSession->name == $school->academicSession->name) {
                // Event's academic session matches the school's academic session, give it a very high rank
                $rank += 50;
            }
        
            // Check if the event's term matches the school's term
            if ($event->term->name == $school->term->name) {
                // Event's term matches the school's term, give it a higher rank
                $rank += 100;
            }
        }
    
        
        }
    
        return $rank;
    }

    public function loadMoreLessons(Request $request)
    {
        $page = $request->get('page');
        $class_level = $request->get('class_level');
        $term = $request->get('term');
        $course = $request->get('course');
        $perPage = 2; // Number of lessons to load per request
    
        // Retrieve IDs of already displayed lessons
        $displayedLessonIds = collect($request->get('displayedLessonIds'));
    
        // Start building the query for lessons with eager loaded teacher and profile
        $query = Lesson::with(['teacher.profile'])
                        ->orderBy('id', 'desc')
                        ->whereNotIn('id', $displayedLessonIds->toArray());
    
        // Apply class_level filter if provided
        if (!empty($class_level)) {
            $query->where('class_level', $class_level);
        }
    
        // Apply course filter if provided
        if (!empty($course)) {
            $query->where('subject', $course);
        }
    
        // Apply term search if provided
        if (!empty($term)) {
            // Split the term into keywords
            $keywords = explode(' ', strtolower($term));
    
            // Retrieve lessons matching any of the keywords in title or description
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    // Apply search to lesson title and description
                    $query->orWhere('title', 'like', '%' . $keyword . '%')
                          ->orWhere('description', 'like', '%' . $keyword . '%');
                }
            });
        }
    
        // Retrieve lessons based on the constructed query
        $lessons = $query->take($perPage)->get();
    
        // Filter and transform lessons based on calculated rank
        $filteredLessons = $lessons->filter(function ($lesson) {
            $rank = $this->calculateLessonRank($lesson, $lesson->school, auth()->user()->profile->role, auth()->user());
            return $rank >= 4; // Filter lessons with rank >= 4
        });
    
        // Transform lessons data to include teacher's name
        $transformedLessons = $filteredLessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'is_enrolled' => $lesson->enrolledUsers()->where('user_id', auth()->id())->exists(),
                'thumbnail' => $lesson->thumbnail,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'teacher_name' => $lesson->teacher->profile->full_name, // Access teacher's full name 
                'school_connects_required' => $lesson->school_connects_required,
                // Include other necessary lesson attributes
            ];
        });
    
        // Get IDs of the filtered lessons
        $filteredLessonIds = $filteredLessons->pluck('id')->toArray();
    
        return response()->json([
            'lessons' => $transformedLessons,
            'filteredLessonIds' => $filteredLessonIds, // Pass back IDs of filtered lessons
        ]);
    }
    public function loadMoreViewedLessons(Request $request)
    {
        $user = auth()->user();
        $page = $request->get('page', 0);
        $perPage = 2;

        $displayedViewedLessonIds = $request->get('displayedLessonIds', []);

        $lessons = $user->enrolledLessons()
            ->whereNotIn('lessons.id', $displayedViewedLessonIds)
            // ->skip($page * $perPage)
            ->take($perPage)
            ->get();

        $transformedLessons = $lessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'thumbnail' => $lesson->thumbnail,
                'title' => $lesson->title,
                'created_at' => $lesson->created_at->diffForHumans(),
                'description' => $lesson->description,
                'teacher_name' => $lesson->teacher->profile->full_name,
                'school_connects_required' => $lesson->school_connects_required,
            ];
        });

        return response()->json([
            'lessons' => $transformedLessons,
        ]);
    }

    public function loadMoreFavLessons(Request $request)
    {
        $user = auth()->user();
        $page = $request->get('page', 0);
        $perPage = 2;

        $displayedFavLessonIds = $request->get('displayedLessonIds', []);

        $lessons = $user->favoriteLessons()
            ->whereNotIn('lessons.id', $displayedFavLessonIds)
            // ->skip($page * $perPage)
            ->take($perPage)
            ->get();

        $transformedLessons = $lessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'thumbnail' => $lesson->thumbnail,
                'title' => $lesson->title,
                'created_at' => $lesson->created_at->diffForHumans(),
                'description' => $lesson->description,
                'teacher_name' => $lesson->teacher->profile->full_name,
                'school_connects_required' => $lesson->school_connects_required,
            ];
        });

        return response()->json([
            'lessons' => $transformedLessons,
        ]);
    }


    
    public function loadMoreEvents(Request $request)
    {
        $page = $request->get('page');
        $perPage = 6; // Number of events to load per request
        $term = $request->get('term');

        // Retrieve IDs of already displayed events
        $displayedEventIds = collect($request->get('displayedEventIds'))->toArray();

        // Start building the query to retrieve events with eager loaded school relationship
        $eventsQuery = Event::with('school')
            ->orderBy('id', 'desc');

        // Exclude displayed event IDs
        if (!empty($displayedEventIds)) {
            $eventsQuery->whereNotIn('id', $displayedEventIds);
        }

        // Apply term search if provided
        if (!empty($term)) {
            // Split the term into keywords
            $keywords = explode(' ', strtolower($term));

            // Retrieve events matching any of the keywords in title or description
            $eventsQuery->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    // Apply search to event title and description
                    $query->orWhere('title', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%');
                }
            });
        }

        // Retrieve events based on the constructed query
        $events = $eventsQuery->take($perPage)->get();

        // Filter and transform events based on calculated rank
        $filteredEvents = $events->filter(function ($event) {
            $rank = $this->calculateEventRank($event, $event->school, auth()->user()->profile->role);
            return $rank >= 4; // Filter events with rank >= 4
        });

        // Transform events data to include necessary attributes
        $transformedEvents = $filteredEvents->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start_date' => $event->start_date->format('d M Y'),
                'start_time' => $event->start_date->format('H:i'),
                'school_name' => $event->school->name,
                'banner_picture' => $event->banner_picture ? asset('storage/' . $event->banner_picture) : null,
                'academic_session_name' => $event->academicSession->name,
                // Include other necessary event attributes
            ];
        });

        // Get IDs of the filtered events
        $filteredEventIds = $filteredEvents->pluck('id')->toArray();

        return response()->json([
            'events' => $transformedEvents,
            'filteredEventIds' => $filteredEventIds, // Pass back IDs of filtered events
        ]);
    }
    
    public function loadMorePeople(Request $request)
    {
        $page = $request->get('page');
        $class_id = $request->get('class_id'); // Assuming this is used for filtering by class_id
        $course = $request->get('course');
        $perPage = 2; // Number of users to load per request
        $term = $request->get('term');
    
        // Retrieve IDs of already displayed people
        $displayedPeopleIds = collect($request->get('displayedPeopleIds'));
    
        // Start building the query to retrieve users with eager loaded profile and class section
        $query = User::with(['profile', 'userClassSection'])
            ->orderBy('id', 'desc')
            ->whereNotIn('id', $displayedPeopleIds->toArray());
    
        // Apply class_id filter if provided
        if (!empty($class_id)) {
            $query->whereHas('userClassSection', function ($subquery) use ($class_id) {
                $subquery->where('class_id', $class_id); // Filter by the provided class_id
            });
        }
    
        // Apply course filter if provided (assuming 'subject' is part of the user model)
        if (!empty($course)) {
            $query->where('subject', $course);
        }
    
        // Apply term search if provided
        if (!empty($term)) {
            // Split the term into keywords
            $keywords = explode(' ', strtolower($term));
    
            // Retrieve users matching any of the keywords in profile attributes
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    // Apply search to direct user fields
                    $query->orWhere('email', 'like', '%' . $keyword . '%')
                          ->orWhere('first_name', 'like', '%' . $keyword . '%')
                          ->orWhere('last_name', 'like', '%' . $keyword . '%')
                          ->orWhere('middle_name', 'like', '%' . $keyword . '%');
    
                    // Apply nested search within profile relationship
                    // $query->orWhereHas('profile', function ($profileQuery) use ($keyword) {
                    //     $profileQuery->where('bio', 'like', '%' . $keyword . '%');
                    //     // Add more profile field searches here if needed
                    // });
                }
            });
        }
    
        // Retrieve users based on the constructed query
        $people = $query->take($perPage)->get();
    
        // Transform users data to include necessary attributes
        $transformedPeople = $people->map(function ($user) {
            $profile = $user->profile; // Get profile relationship
            $userClassSection = $user->userClassSection; // Get userClassSection relationship
    
            return [
                'id' => $user->id,
                'full_name' => $user->profile->full_name, // Use optional helper to avoid accessing null object
                'profile_picture' => $profile->profile_picture,
                'email' => $profile->email,
                'role' => $profile->role,
                'class_code' => $user->userClassSection ? $user->userClassSection->code : null,

                'phone_number' => $user->profile->phone_number,
                'gender' => $user->profile->gender,
                'date_of_birth' => $user->profile->date_of_birth,
                // Include other necessary user attributes
            ];
        });
    
        // Get IDs of the filtered users
        $filteredPeopleIds = $people->pluck('id')->toArray();
    
        return response()->json([
            'people' => $transformedPeople,
            'filteredPeopleIds' => $filteredPeopleIds, // Pass back IDs of filtered people
        ]);
    }
    


    public function viewCurriculum($classId)
    {
        try {
            // Find the class by ID
            $class = SchoolClass::findOrFail($classId);
            $curriculum =$class->curriculum();
            // dd($curriculum);


            // Check if curriculum is found
            if ($curriculum) {
                // You can pass the $curriculum variable to the view or perform other actions
                return view('curriculum.view_curriculum', ['curricula' => $curriculum]);
            } else {
                // Handle the case when curriculum is not found
                return redirect()->back()->with('error','No Goverment Approved Curriculum Found');
            }
        } catch (\Exception $e) {
            \Log::error($e);

            // Handle exceptions or return an error response
            return redirect()->back()->with('error', 'Failed to retrieve curriculum. ');
        }
    }



    public function storeQualification(Request $request)
    {
        try {
            $user = auth()->user(); // Assuming the user is authenticated
    
            $validatedData = $request->validate([
                'certificate' => 'required|string',
                'school_attended' => 'required|string',
                'starting_year' => 'required|numeric',
                'completion_year' => 'required|numeric',
            ]);
    
            // Add user_id to the validated data
            $validatedData['user_id'] = $user->id;
    
            // Create the qualification
            $qualification = Qualification::create($validatedData);
    
            return response()->json([
                'success' => 'Qualification saved successfully',
                'data' => $qualification,
            ]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to save qualification. Please try again.'], 500);
        }
    }
    public function showStudent($studentId)
    {
        $user = auth()->user();

        // Check if the user is authenticated
        if (!$user) {
            return redirect()->route('home')->with('error', 'Unauthorized for this action');
        }

        // Find the student
        $student = User::find($studentId);

        // Check if the student exists and is a student
        if ($student && $student->profile->role == 'student') {
            $school = $student->school;
            // dd($school->getConfirmedAdmins()->pluck('id'), $user->id);

            // Check if the user is authorized based on their role
            if ($user->profile->role == 'admin' && $user->school_id == $student->school_id) {
                return view('school.student_page', compact('student'));
            } elseif ($user->profile->role == 'school_owner' && $user->ownedSchools->contains($school->id)) {
                return view('school.student_page', compact('student'));
            } elseif ($school->getConfirmedAdmins()->pluck('id')->contains($user->id)) {
                return view('school.student_page', compact('student'));
            }elseif ($user->profile->role == 'teacher') {
                // Check if the user is a form teacher of any class sections that the student belongs to
                foreach ($student->userClassSection->formTeachers as $formTeacher) {
                    if ($formTeacher->id == $user->id) {
                        return view('school.student_page', compact('student'));
                    }
                }
            } elseif ($user->profile->role == "student" && $student->id == $user->id) {
                return view('school.student_page', compact('student'));
            } elseif ($school->getConfirmedAdmins()->pluck('id')->contains($user->id)) {
                return view('school.student_page', compact('student'));
            }
        }

        // If the user is not authorized or the student doesn't exist, return unauthorized error
        return redirect()->route('home')->with('error', 'Unauthorized for this action');
    }

    public function submitCourse(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Sync the courses with the user
            $user->student_courses()->sync($request->courses);
    
            // Get the user's class section ID
            $classSectionId = $user->userClassSection->id;
    
            // Update the pivot table records with the class_section_id
            $user->student_courses()->updateExistingPivot($request->courses, ['class_section_id' => $classSectionId]);
    
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Failed to enroll in course. ' . $e->getMessage()], 500);
        }
    }
    
    public function offerCourse(Request $request)
    {
        try {
            // Retrieve the course and student IDs from the request
            $courseId = $request->input('course_id');
            $studentId = $request->input('student_id');
    
            // Find the course and student
            $course = Course::findOrFail($courseId);
            $student = User::findOrFail($studentId);
    
            // Get the student's class section
            $studentClassSection = $student->userClassSection;
    
            // Get the class sections offered for the course
            $courseClassSections = $course->class_sections;
    
            // Check if the student's class section is among the class sections offered for the course
            $studentInSection = $courseClassSections->contains('id', $studentClassSection->id);
    
            // If the student's class section is among the class sections offered for the course, attach the student to the course
            if ($studentInSection) {
                $course->students()->attach($student, ['class_section_id' => $studentClassSection->id]);
    
                // Return a success response
                return response()->json(['message' => 'Student assigned to course successfully', 'course' => $course], 200);
            } else {
                // Return an error response if the student's class section is not among the class sections offered for the course
                return response()->json(['error' => 'Student class section is not offered for this course'], 400);
            }
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['error' => 'Failed to assign student to course'], 500);
        }
    }
    
    public function creditSchoolConnects(Request $request)
    {
        $user = Auth::user();
    
        // Check if the user is authenticated and has a profile
        if ($user && $user->hasProfile()) {
            $profile = $user->profile;
    
            // Check if the user's role is 'student'
            if ($profile->role === 'student') {
                // Check if the user's profile has been credited today
                if ($profile->last_credited_at === now()->toDateString()) {
                    // User has already been credited today
                    return response()->json(['error_message' => 'You have already been credited today.', 'link' => 'buy_connects'], 200);
                }
    
                // Determine the school_connects based on the user's package
                $schoolConnects = 0;
    
                // Check if the user has a package
                if ($user->userPackage) {
                    switch ($user->userPackage->name) {
                        case 'Basic Package':
                            $schoolConnects = 90;
                            break;
                        case 'Standard Package':
                            $schoolConnects = 270;
                            break;
                        case 'Premium Package':
                            $schoolConnects = 1080;
                            break;
                        default:
                            $schoolConnects = 50;
                            break;
                    }
                } else {
                    // User does not have a package set up
                    return response()->json(['error_message' => 'You do not have a package set up. Please set up your package on your profile page.', 'link' => 'profile'], 200);
                }
    
                // Add the determined school_connects to the user's profile
                $profile->school_connects += $schoolConnects;
    
                // Update the last_credited_at field with today's date
                $profile->last_credited_at = now()->toDateString();
    
                // Save the updated profile
                $profile->save();
    
                // Return success response
                return response()->json(['message' => 'School connects credited successfully'], 200);
            } else {
                // User is not a student
                return response()->json(['error_message' => 'You are not eligible for free school connects at this time', 'link' => 'buy_connects'], 200);
            }
        }
    
        // User is not authenticated or has no profile
        return response()->json(['error' => 'You are not eligible for school connects credit at this time', 'link' => 'buy_connects'], 400);
    }
    

        
    public function buyConnects(Request $request)
    {
        $selectedAmount = $request->input('amount');

        // Perform any necessary operations with the selected amount
        // For example, log the selected amount
        \Log::info('Selected Amount: ' . $selectedAmount);

        // Redirect to another route after successful processing
        return response()->json([
            'redirect_url' => route('buy-connects_page', ['amount' => $selectedAmount]),
            'amount' => $selectedAmount
        ], 200);
    }

         
    public function buyConnectsForStudent(Request $request, $id)
    {
        $selectedAmount = $request->input('amount');
        $studentId = $id;

        // Perform any necessary operations with the selected amount
        // For example, log the selected amount
        \Log::info('Selected Amount: ' . $selectedAmount);

        // Redirect to another route after successful processing
        return response()->json([
            'redirect_url' => route('buy-connects-for-student-page', ['id'=> $id, 'amount' => $selectedAmount]),
            'amount' => $selectedAmount,
            'id'=>$id,
        ], 200);
    }
    public function buyConnectsPage(Request $request, $amount)
    {
        // $request->session()->forget('payment_session');
        //     $request->session()->forget('payment_confirmation');
        //     $request->session()->forget('payment_session_expires_at');
        // Here you can pass the amount to the view or perform any other logic
        return view('payment.buy_connects', ['amount' => $amount]);
    }
    public function buyConnectsForStudentPage($id, $amount)
    {
        // dd($id, $amount);
        // $request->session()->forget('payment_session');
        //     $request->session()->forget('payment_confirmation');
        //     $request->session()->forget('payment_session_expires_at');
        // Here you can pass the amount to the view or perform any other logic
        return view('payment.buy_connects_for_student', ['id'=>$id,'amount' => $amount]);
    }

    public function showClass(SchoolClass $class)
    {
        $user = auth()->user();
        $students = $class->students()->take(2);

        // Check if user's student status is confirmed
        if (!$user->profile || !$user->profile->student_confirmed) {
            return redirect()->route('home')->with('error', 'Please contact your school administrator to confirm your student status.');
        }

        // Load additional data related to the class if needed
        $school = $class->school;
        $userRole = $user->profile->role;

        $events = Event::all();
        $lessons = $class->getLessonsByClassLevel();

        // Sort events by rank
        $sorted_events = $events->sortByDesc(function ($event) use ($school, $userRole) {
            return $this->calculateEventRank($event, $school, $userRole);
        });

        // Sort lessons by rank and include rank score with each lesson
        $sorted_lessons = $lessons->map(function ($lesson) use ($school, $userRole, $user) {
            $rank = $this->calculateLessonRank($lesson, $school, $userRole, $user);
            $lesson->rank = $rank; // Include the rank score with the lesson object
            return $lesson;
        })->sortByDesc('rank')->values(); // Ensure a re-indexed array

        // Retrieve the top 2 events and top 6 lessons based on rank
        $top_events = $sorted_events->take(2);
        $top_lessons = $sorted_lessons->take(6);

        return view('school.class_page', compact('class', 'school', 'top_events', 'top_lessons', 'students'));
    }
    public function viewStudentResult(Request $request, $student_id, $academic_session_id, $term_id)
    {
        // Retrieve the student result based on the provided parameters
        $studentResults = $this->getStudentResults($student_id, $academic_session_id, $term_id);

        if ($studentResults->isEmpty()) {
            return redirect()->back()->with('error', 'Student results not found.');
        }

        $student = User::findOrFail($student_id);

        $school = $student->school;

        // Extract course names from student results
        $courseNames = $studentResults->pluck('course_name')->unique()->toArray();

        // Calculate average scores and course grades
        $averageScores = $this->calculateAverageScores($studentResults);
        $courseGrades = $this->calculateCourseGrades($studentResults);

        // Retrieve class position and class section position
        $classPosition = $this->getClassPosition($student_id, $academic_session_id, $term_id);
        $classSectionPosition = $this->getClassSectionPosition($student_id, $academic_session_id, $term_id);
        // dd($classSectionPosition);
        // Return the view with the result data
        return view('school.student_result', compact('studentResults', 'courseNames', 'school', 'averageScores', 'courseGrades', 'classPosition', 'classSectionPosition'));
    }

    
   // Retrieve student results based on parameters, filtering out courses with zero exam score
    private function getStudentResults($student_id, $academic_session_id, $term_id)
    {
        return StudentResult::where([
            'student_id' => $student_id,
            'academic_session_id' => $academic_session_id,
            'term_id' => $term_id,
        ])->where('exam_score', '>', 0)->get();
    }

    private function getStudentResultComment($student_id, $academic_session_id, $term_id)
    {
        // dd($student_id, $academic_session_id, $term_id);
        return StudentResultComment::where([
            'student_id' => $student_id,
            'academic_session_id' => $academic_session_id,
            'term_id' => $term_id,
        ]);
    }

    
    // Calculate average scores for each course
    private function calculateAverageScores($studentResults)
    {
        $averageScores = [];
    
        foreach ($studentResults as $result) {
            $courseName = $result->course_name;
            $totalScores = $studentResults->where('course_name', $courseName)->pluck('total_score')->toArray();
            $averageScore = count($totalScores) > 0 ? array_sum($totalScores) / count($totalScores) : 0;
            $averageScores[$courseName] = $averageScore;
        }
    
        return $averageScores;
    }
    
    // Calculate grades for each course
    private function calculateCourseGrades($studentResults)
    {
        $courseGrades = [];
    
        foreach ($studentResults as $result) {
            $courseName = $result->course_name;
            $grades = $studentResults->where('course_name', $courseName)->pluck('grade')->toArray();
            $courseGrades[$courseName] = $grades;
        }
    
        return $courseGrades;
    }
    
    // Get the position of the student based on total average score in the class
    public function getClassPosition($student_id, $academic_session_id, $term_id)
    {
        // Find the student
        $student = User::findOrFail($student_id);

        // Retrieve the student's class ID and class section ID
        $class_id = $student->profile->class_id;
        $class_section_id = $student->profile->class_section_id;

        // Get the position of the student within the class based on total average score
        $students = StudentResultComment::where('academic_session_id', $academic_session_id)
            ->where('term_id', $term_id)
            ->where('class_id', $class_id)
            ->orderByDesc('total_average_score')
            ->pluck('student_id')
            ->toArray();

        $position = array_search($student_id, $students) + 1;

        // Get the total number of students in the class
        $totalStudentsInClass = count($students);

        // Retrieve the comments for the student
        $comments = StudentResultComment::where('academic_session_id', $academic_session_id)
            ->where('term_id', $term_id)
            ->where('class_id', $class_id)
            ->where('student_id', $student_id)
            ->pluck('comment')
            ->toArray();
            // dd($comments);

        return [
            'position' => $position,
            'total_students' => $totalStudentsInClass,
            'comments' => $comments
        ];
    }

        

    // Get the position of the student based on total average score in the class section
    public function getClassSectionPosition($student_id, $academic_session_id, $term_id)
    {
        $student = User::findOrFail($student_id);

        $students = StudentResultComment::where('academic_session_id', $academic_session_id)
            ->where('term_id', $term_id)
            ->where('class_section_id', $student->userClassSection->id)
            ->orderByDesc('total_average_score')
            ->pluck('student_id')
            ->toArray();

        $position = array_search($student_id, $students) + 1;

        // Get the total number of students in the class section
        $totalStudentsInClassSection = count($students);

        return ['position' => $position, 'total_students' => $totalStudentsInClassSection];
    }

    public function findWards(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query');

        // Get the authenticated guardian user
        $guardian = auth()->user();

        // Perform the search query with eager loading of relationships
        $wards = User::whereHas('profile', function ($queryBuilder) use ($query) {
                $queryBuilder->where('full_name', 'like', '%' . $query . '%');
            })
            ->whereHas('profile', function ($queryBuilder) {
                $queryBuilder->where('role', 'student');
            })
            // Filter out students who are already wards of the guardian
            ->whereDoesntHave('guardians', function ($queryBuilder) use ($guardian) {
                $queryBuilder->where('guardian_id', $guardian->id);
            })
            ->get();

        // Structure the wards data
        $wardsData = [];
        foreach ($wards as $ward) {
            // dd($ward->profile->profile_picture);
            $wardData = [
                'id' => $ward->id,
                'full_name' => $ward->profile->full_name,
                'profile_picture' => $ward->profile->profile_picture,
                'school_name' => $ward->school->name,
                'class_name' => $ward->schoolClass()->name,
            ];
            $wardsData[] = $wardData;
        }

        // Return the data as a JSON response
        return response()->json($wardsData);
    }

    public function addWard(Request $request)
    {
        // Get the authenticated guardian user
        $guardian = auth()->user();

        // Get the student ID from the request data
        $studentId = $request->input('student_id');

        // Find the student by ID
        $student = User::find($studentId);

        if (!$student) {
            // If student not found, return error response
            return response()->json(['error' => 'Student not found.'], 404);
        }

        // Check if the student is already a ward of the guardian
        if ($guardian->wards->contains($studentId)) {
            return response()->json(['error' => 'Student is already a ward.'], 422);
        }

        // Generate a confirmation token
        $confirmationToken = Str::random(32);

        // Send email to the student
        Mail::to($student->email)->send(new GuardianAddedYouAsWard($guardian, $student, $confirmationToken));

        // Add the student as a ward of the guardian
        $ward = $guardian->wards()->attach($studentId, ['confirmation_token' => $confirmationToken]);
        

        // Return success response
        return response()->json(['message' => 'Student added as ward successfully.']);
    }


    public function removeWard($wardId)
    {
        try {
            // Get the authenticated guardian user
            $guardian = auth()->user();

            // Detach the ward from the guardian's list of wards
            $guardian->wards()->detach($wardId);

            return response()->json(['message' => 'Ward removed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove ward'], 500);
        }
    }
    public function confirmGuardian($token)
    {
        try {
            // Find the authenticated user (assuming the user is logged in)
            $student = auth()->user();
    
            // Find the unconfirmed ward record by confirmation token
            $guardian = $student->unconfirmedGuardians()
            ->where('confirmation_token', $token)
            ->where('ward_id', $student->id)
            
            ->first();
            // dd($guardian);
    
            // Check if the ward exists and the confirmation token matches
            if ($guardian) {
                // Update the confirmation status to true
                $student->guardians()->updateExistingPivot($guardian->id, ['confirmed' => true, 'confirmation_token' => null]);
                
                    // Send email to the student
                    Mail::to($guardian->email)->send(new GuardianConfirmation($guardian, $student));
    
                // Redirect to a success page with a success message
                return redirect()->route('home')->with('success', 'Guardian confirmed successfully.');
            }
            // dd($guardian);
    
            // If the confirmation fails, redirect with an error message
            return redirect()->route('home')->with('error', 'Failed to confirm guardian.');
        } catch (\Exception $e) {
            // Dump the exact error for debugging
            dd($e->getMessage());
        }
    }
    public function viewStudentProgress($studentId)
    {

        $student = User::findOrFail($studentId);
        // dd($student);
        // dd($student->studentResults);
         // Check if the student is enrolled in a school
        $school = $student->school;
        if (!$school) {
            return redirect()->route('home')->with('error', 'This student is not enrolled in any school yet.');
        }

        $academic_session_id = $school->academicSession->id;
        $term_id= $school->term->id;
        $studentResults = $this->getStudentResults($studentId, $academic_session_id, $term_id);

        // if ($studentResults->isEmpty()) {
        //     return redirect()->back()->with('error', 'Student results not found.');
        // }


        // Extract course names from student results
        $courseNames = $studentResults->pluck('course_name')->unique()->toArray();

        // Calculate average scores and course grades
        $averageScores = $this->calculateAverageScores($studentResults);
        $courseGrades = $this->calculateCourseGrades($studentResults);

        // Retrieve class position and class section position
        $classPosition = $this->getClassPosition($studentId, $academic_session_id, $term_id);
        $classSectionPosition = $this->getClassSectionPosition($studentId, $academic_session_id, $term_id);
        // Retrieve the student from the database
        $student = User::findOrFail($studentId);
        $school = $student->school;

        // Add logic to retrieve academic progress data for the student
        // For example, you can retrieve grades, attendance, etc.

        // Return the view with the academic progress data
        return view('student.student_progress', compact('student', 'studentResults', 'courseNames', 'school', 'averageScores', 'courseGrades', 'classPosition', 'classSectionPosition'));
    }

    public function userPackage()
    {
        $userPackages = UserPackage::all();
        return view('user_package', compact('userPackages'));
    }
   

}
    
