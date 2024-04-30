<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Curriculum;
use App\Models\Lesson;

use Illuminate\Http\Request;

class CourseController extends Controller
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
    //
    public function showCurriculumforClass(Request $request, $courseId, $classId)
    {
        // Retrieve the course based on the given $courseId
        $course = Course::findOrFail($courseId);
        $class = SchoolClass::findOrFail($classId);
        $class_level = $class->class_level;

        // Retrieve curricula for the specified course and class section
        $curricula = $course->curricula()->where('class_level', $class_level);
        // dd($curricula);

        // Pass the retrieved curricula to a view or return as needed
        return view('curriculum.class_curricula', [
            'course' => $course,
            'curricula' => $curricula,
        ]);
    }

    public function getCurriculumDetails(Request $request, $curriculum)
    {
        // Retrieve curriculum details
        // You can modify this logic based on your application's needs
        $curriculum = Curriculum::findOrFail($curriculum);

        if ($curriculum) {
            return response()->json([
                'success' => true,
                'curriculum_id' => $curriculum->id,
                // Add more curriculum details if needed
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Curriculum details not found.',
        ]);
    }

   /**
     * Rank lessons based on keyword frequency and criteria.
     *
     * @param Collection $lessons
     * @param string $curriculumDescription
     * @param string $classLevel
     * @param int $schoolConnectsRequired
     * @return Collection
     */
    protected function rankLessons($lessons, $curriculumDescription, $classLevel)
    {
        $stopwords = $this->stopwords;
    
        // Retrieve curriculum keywords
        $curriculumKeywords = array_diff(explode(' ', strtolower($curriculumDescription)), $stopwords);
    
        // Initialize a collection to hold ranked lessons
        $rankedLessons = collect();
    
        // Iterate through each lesson in the provided collection
        foreach ($lessons as $lesson) {
            if (isset($lesson->description) && isset($lesson->class_level) && isset($lesson->school_connects_required)) {
                $lessonKeywords = array_diff(explode(' ', strtolower($lesson->description)), $stopwords);
                $keywordMatches = array_intersect($curriculumKeywords, $lessonKeywords);
                $rank = count($keywordMatches);
    
                // Calculate age of the lesson in days (you may adjust the time unit as needed)
                $createdDaysAgo = $lesson->created_at->diffInDays(now());
    
                // Retrieve like and favorite counts
                $likeCount = $lesson->likedUsers()->count();
                $favoriteCount = $lesson->favoritedByUsers()->count();
                
                // Retrieve school_connects_required
                $schoolConnectsRequired = $lesson->school_connects_required;
    
                // Normalize and standardize like and favorite counts
                $normalizedLikeCount = $this->normalizeCount($likeCount);
                $normalizedFavoriteCount = $this->normalizeCount($favoriteCount);
                
                // Normalize and standardize school_connects_required
                $normalizedSchoolConnects = $this->normalizeCount($schoolConnectsRequired);
                // $standardizedSchoolConnects = $this->standardizeCount($schoolConnectsRequired);
    
                // Standardize like and favorite counts
                // $standardizedLikeCount = $this->standardizeCount($likeCount);
                // $standardizedFavoriteCount = $this->standardizeCount($favoriteCount);
    
                // Additional criteria: Consider lesson age and standardized counts for ranking
                $ageWeight = 1 / ($createdDaysAgo + 1); // Use inverse of days ago to give higher weight to more recent lessons
    
                // Combine all factors into a composite rank score
                $rankScore = $rank + $ageWeight + $normalizedLikeCount + $normalizedFavoriteCount + $normalizedSchoolConnects;
    
                // Add the lesson with its composite rank score to the collection
                $rankedLessons->push([
                    'lesson' => $lesson,
                    'rankScore' => $rankScore,
                ]);
            }
        }
    
        // Filter lessons by class level
        $filteredLessons = $rankedLessons->filter(function ($item) use ($classLevel) {
            $lesson = $item['lesson'];
            return $lesson->class_level === $classLevel;
        });
    
        // Sort lessons by composite rank score (highest to lowest)
        $sortedLessons = $filteredLessons->sortByDesc('rankScore')->pluck('lesson');
    
        return $sortedLessons->values();
    }
    protected function normalizeCount($count, $maxCount = 100)
    {
        // Ensure count and maxCount are valid positive numbers
        if (!is_numeric($count) || !is_numeric($maxCount) || $maxCount <= 0) {
            return 0; // Return 0 if invalid parameters are provided
        }

        // Apply normalization to scale count to the range [0, 1]
        if ($count > 0 && $maxCount > 0) {
            return min($count / $maxCount, 1); // Ensure the result is within [0, 1]
        } else {
            return 0; // Return 0 if count or maxCount is zero or negative
        }
    }
    protected function standardizeCount($count, $mean = 0, $stdDev = 1)
    {
        // Ensure mean and standard deviation are valid numbers and stdDev is positive
        if (!is_numeric($count) || !is_numeric($mean) || !is_numeric($stdDev) || $stdDev <= 0) {
            return 0; // Return 0 if invalid parameters are provided
        }

        // Apply standardization to transform count to have mean 0 and std dev 1
        if ($stdDev > 0) {
            return ($count - $mean) / $stdDev;
        } else {
            return 0; // Return 0 if standard deviation is zero or negative
        }
    }


    

    /**
     * Get related lessons for a curriculum.
     *
     * @param Request $request
     * @param Curriculum $curriculum
     * @return \Illuminate\Contracts\View\View
     */
    public function getRelatedLessons(Request $request, Curriculum $curriculum)
    {
        // dd($curriculum->id);
        $curriculumId = $curriculum->id;
        $topicId = null;
        $lessons = Lesson::where('class_level', $curriculum->class_level)
            ->where('subject', $curriculum->subject)
            ->take(1)
            ->get(); // Execute the query to retrieve lessons

        // Rank and filter lessons based on curriculum criteria
        $relevantLessons = $this->rankLessons(
            $lessons,
            $curriculum->description,
            $curriculum->class_level
        );
        // dd($relevantLessons);

        return view('curriculum.lessons', compact('relevantLessons', 'curriculumId', 'topicId'));
    }
    public function loadMoreCurriculumLessons(Request $request)
    {
        try {
            $perPage = 2; // Number of lessons to load per request
            $displayedLessonIds = $request->get('displayedLessonIds', []); // Ensure default empty array if no IDs are provided
            $curriculumId = $request->get('curriculum_id');
            $topicId = $request->get('topic_id');
            
    
            // Start building the query for lessons with eager loaded teacher and profile
            $query = Lesson::with(['teacher.profile'])
                ->orderBy('id', 'desc');
    
            if (!empty($displayedLessonIds)) {
                // Exclude already displayed lesson IDs
                $query->whereNotIn('id', $displayedLessonIds);
            }

            if ($curriculumId) {
                $curriculum = Curriculum::findOrFail($curriculumId);
                if($curriculum){
                    $description = $curriculum->description;
                    $query->where('class_level', $curriculum->class_level);
                    $query->where('subject', $curriculum->subject);
                }
                if ($topicId) {
                    $topic = $curriculum->topics->where('pivot.id', $topicId)->first();
                    $description =  $topic->pivot->description;
                    // $query->where()
                }
                
             }
    
            // Retrieve lessons based on the constructed query
            $lessons = $query->take($perPage)->get();
    
            // Pass lessons collection to rankLessons method
            $filteredLessons = $this->rankLessons(
                $lessons,
                $description,

                $curriculum->class_level
                
                
            );
    
            // Transform lessons data as needed
            $transformedLessons = $filteredLessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'is_enrolled' => $lesson->enrolledUsers()->where('user_id', auth()->id())->exists(),
                    'thumbnail' => $lesson->thumbnail,
                    'class' => $lesson->class_level,
                    'title' => $lesson->title,
                    'subject' => $lesson->subject,
                    'description' => $lesson->description,
                    'teacher_name' => $lesson->teacher->profile->full_name, // Access teacher's full name 
                    'school_connects_required' => $lesson->school_connects_required,
                ];
            });
    
            // Get IDs of the filtered lessons
            $filteredLessonIds = $filteredLessons->pluck('id')->toArray();
    
            return response()->json([
                'lessons' => $transformedLessons,
                'filteredLessonIds' => $filteredLessonIds, // Pass back IDs of filtered lessons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    
    public function getTopicDetails(Request $request, $topicId, $curriculumId)
    {
        try {
            // Retrieve the curriculum
            $curriculum = Curriculum::findOrFail($curriculumId);

            // Retrieve the topic using the pivot table (curricula_topics)
            $topic = $curriculum->topics->where('pivot.id', $topicId)->first();

            if ($topic) {
                return response()->json([
                    'success' => true,
                    'topic' => $topic,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Topic not found for the specified curriculum and topic ID.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getRelatedTopicLessons(Request $request, $curriculumId, $topicId)
{
    try {
        // Retrieve the curriculum
        $curriculum = Curriculum::findOrFail($curriculumId);

        // Retrieve the specific topic from the pivot table using the provided topicId
        $topic = $curriculum->topics()->where('curricula_topics.id', $topicId)->first();

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found for the specified curriculum and topic ID.',
            ], 404);
        }

        // Retrieve lessons based on curriculum criteria
        $lessons = Lesson::where('class_level', $curriculum->class_level)
            ->where('subject', $curriculum->subject)
            ->get();

        // Rank and filter lessons based on topic description and curriculum criteria
        if ($topic->pivot && isset($topic->pivot->description)) {
            $relevantLessons = $this->rankLessons(
                $lessons,
                $topic->pivot->description,
                $curriculum->class_level
            );

            return view('curriculum.lessons', compact('relevantLessons', 'curriculumId', 'topicId'));
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Topic description not found for the specified topic.',
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}

}
