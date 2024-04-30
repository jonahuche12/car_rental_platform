<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lesson;
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

            // Prepare to store results by type (users, lessons, events)
            $results = [
                'users' => new Collection(),
                'lessons' => new Collection(),
                'events' => new Collection(),
            ];

            // Perform weighted search based on keyword relevance
            foreach ($keywords as $keyword) {
                // Search users
                $users = User::where('first_name', 'like', "%$keyword%")
                            ->orWhere('middle_name', 'like', "%$keyword%")
                            ->orWhere('last_name', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%")
                            ->take(1)
                            ->get();
                $results['users'] = $results['users']->merge($users);

                // Search lessons
                $lessons = Lesson::where('title', 'like', "%$keyword%")
                                ->orWhere('description', 'like', "%$keyword%")
                                ->take(1)
                                ->get();
                $results['lessons'] = $results['lessons']->merge($lessons);

                // Search events
                $events = Event::where('title', 'like', "%$keyword%")
                                ->orWhere('description', 'like', "%$keyword%")
                                ->take(2)
                                ->get();
                $results['events'] = $results['events']->merge($events);
            }

            // Pass the $results variable to the view
            return view('search_results', compact('results', 'term'));

        } catch (\Exception $e) {
            // Catch any validation or other exceptions and handle them
            return back()->with('error', $e->getMessage());
        }
    }

    

}
