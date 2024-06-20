<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lesson;
use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Support\Collection; // Import Collection class
use App\Models\Event;

class SearchController extends Controller
{
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
    public function search(Request $request)
    {
        $term = $request->input('term');

        // Perform validation on search term
        if (strlen($term) < 3) {
            return response()->json(['error' => 'Search term must be at least 3 characters.'], 422);
        }

        

        return response()->json(['term' => $term]);
    }

    public function showResults(Request $request)
    {
        try {
            $term = $request->input('term');
    
            // Validate and sanitize search term
            if (Str::length($term) < 3) {
                throw new \Exception('Search term must be at least 3 characters long.');
            }
    
            // Define stopwords to filter out
            $stopwords = $this->stopwords;
    
            // Split search term into individual keywords
            $keywords = array_filter(array_map('trim', explode(' ', $term)));
    
            // Filter out stopwords and normalize keywords
            $keywords = array_diff($keywords, $stopwords);
            $keywords = array_map(function ($keyword) {
                return Str::lower($keyword); // Convert keywords to lowercase for case-insensitive search
            }, $keywords);
    
            // Prepare to store results by type (users, lessons, events, schools)
            $results = [
                'users' => new Collection(),
                'lessons' => new Collection(),
                'events' => new Collection(),
                'schools' => new Collection(),
            ];
    
            // Perform weighted search based on keyword relevance
            foreach ($keywords as $keyword) {
                // Search users
                $users = User::where('first_name', 'like', "%$keyword%")
                            ->orWhere('middle_name', 'like', "%$keyword%")
                            ->orWhere('last_name', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%")
                            ->take(6)
                            ->get();
                $results['users'] = $results['users']->merge($users);
    
                // Search lessons
                $lessons = Lesson::where('title', 'like', "%$keyword%")
                                ->orWhere('description', 'like', "%$keyword%")
                                ->take(6)
                                ->get();
                $results['lessons'] = $results['lessons']->merge($lessons);
    
                // Search events
                $events = Event::where('title', 'like', "%$keyword%")
                                ->orWhere('description', 'like', "%$keyword%")
                                ->take(6)
                                ->get();
                $results['events'] = $results['events']->merge($events);
    
                // Search schools
                $schools = School::where('name', 'like', "%$keyword%")
                                ->orWhere('description', 'like', "%$keyword%")
                                ->orWhere('city', 'like', "%$keyword%")
                                ->orWhere('state', 'like', "%$keyword%")
                                ->orWhere('country', 'like', "%$keyword%")
                                ->take(6)
                                ->get();
                $results['schools'] = $results['schools']->merge($schools);
            }
    
            // Pass the $results variable to the view
            return view('search_results', compact('results', 'term'));
    
        } catch (\Exception $e) {
            // Catch any validation or other exceptions and handle them
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function searchLessons(Request $request)
    {
        $query = $request->input('query');
        $userId = $request->input('userId');
        $displayedSearchLessonIds = $request->input('displayedSearchLessonIds', []);
    
        try {
            // Validate and sanitize search query
            if (Str::length($query) < 3) {
                // Fetch the latest 6 lessons, optionally filtered by user ID if provided
                $lessonsQuery = Lesson::whereNotIn('id', $displayedSearchLessonIds)
                    ->orderBy('created_at', 'desc')
                    ->take(6);
    
                if ($userId) {
                    $lessonsQuery->where('user_id', $userId);
                }
    
                $lessons = $lessonsQuery->get()->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'thumbnail' => $lesson->thumbnail,
                        'title' => $lesson->title,
                        'created_at' => $lesson->created_at->diffForHumans(),
                        'description' => $lesson->description,
                        'teacher_name' => $lesson->teacher->profile->full_name,
                        'school_connects_required' => $lesson->school_connects_required,
                        'enrolledUsers_count' => $lesson->enrolledUsers()->count(),
                        'is_enrolled' => $lesson->enrolledUsers()->where('user_id', auth()->id())->exists(),
                    ];
                });
    
                return response()->json($lessons);
            } else {
                // Define stopwords to filter out
                $stopwords = $this->stopwords;
    
                // Split query into individual keywords
                $keywords = array_filter(array_map('trim', explode(' ', $query)));
    
                // Filter out stopwords and normalize keywords
                $keywords = array_diff($keywords, $stopwords);
                $keywords = array_map(function ($keyword) {
                    return Str::lower($keyword); // Convert keywords to lowercase for case-insensitive search
                }, $keywords);
    
                // Prepare to store the search results
                $search_lessons = collect();
    
                // Perform search for each keyword
                foreach ($keywords as $keyword) {
                    $searchQuery = Lesson::where(function ($q) use ($keyword) {
                            $q->where('title', 'like', '%' . $keyword . '%')
                              ->orWhere('description', 'like', '%' . $keyword . '%');
                        })
                        ->whereNotIn('id', $displayedSearchLessonIds)
                        ->take(6);
    
                    if ($userId) {
                        $searchQuery->where('user_id', $userId);
                    }
    
                    $lessons = $searchQuery->get();
                    $search_lessons = $search_lessons->merge($lessons);
                }
    
                // Calculate rank for each lesson and filter them based on rank
                $rankedLessons = $search_lessons->filter(function ($lesson) {
                    $rank = $this->calculateLessonRank($lesson, $lesson->school, auth()->user()->profile->role, auth()->user());
                    return $rank; // Filter lessons with rank >= 4
                });
    
                // Sort lessons by rank in descending order
                $rankedLessons = $rankedLessons->sortByDesc('rank');
    
                // Map the ranked lessons to the desired format
                $lessons = $rankedLessons->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'thumbnail' => $lesson->thumbnail,
                        'title' => $lesson->title,
                        'created_at' => $lesson->created_at->diffForHumans(),
                        'description' => $lesson->description,
                        'teacher_name' => $lesson->teacher->profile->full_name,
                        'school_connects_required' => $lesson->school_connects_required,
                        'enrolledUsers_count' => $lesson->enrolledUsers()->count(),
                        'is_enrolled' => $lesson->enrolledUsers()->where('user_id', auth()->id())->exists(),
                        'rank' => $lesson->rank // Include the rank in the response if needed
                    ];
                });
    
                return response()->json($lessons);
            }
        } catch (\Exception $e) {
            // Catch any validation or other exceptions and handle them
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    

    private function calculateLessonRank($lesson, $school = null, $userRole, $user)
    {
        $rank = 0;
    
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
    
        return max($rank, 0); // Ensure rank is not negative
    }
   
}
