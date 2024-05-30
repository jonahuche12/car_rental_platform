<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\ScholarshipCategory;
use App\Models\Curriculum;
use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\Test;
use App\Mail\ScholarshipCategoryUpdatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ScholarshipController extends Controller
{
    //

    public function manageScholarship()
    {
        $scholarships = Scholarship::with(['academicSession', 'term'])->get();
        $uniqueClassLevels = Curriculum::getUniqueClassLevels();
        $academicSessions = AcademicSession::all();
        $terms = Term::all();
    
        return view('super_admin.scholarships', compact('scholarships', 'uniqueClassLevels', 'academicSessions', 'terms'));
    }

    public function storeScholarship(Request $request)
    {
        
        $latestAcademicSession = AcademicSession::latest()->first();
        $latestTerm = Term::latest()->first();

        if (!$latestAcademicSession || !$latestTerm) {
            return redirect()->back()->withErrors('Current academic session or term not found.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            
            'class_level' => 'required|string|max:255'
        ]);

        $scholarship = Scholarship::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'published' => false, // or set it to true if it should be published immediately
            'class_level' => $validatedData['class_level'],
            'academic_session_id' => $latestAcademicSession->id,
            'term_id' => $latestTerm->id
        ]);

        return redirect()->back()->with('success', 'Scholarship created successfully.');
    }

    public function updateScholarship(Request $request, Scholarship $scholarship)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // 'class_level' => 'required|string|max:255'
           
        ]);

        // Update the test with the new data
        $scholarship->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            // 'class_level' => $validatedData['class_level'],
            
        ]);

        // Return a JSON response indicating success
        return response()->json([
            'message' => 'Test updated successfully!',
            'scholarship' => $scholarship,
        ]);
    }
    
    public function destroyScholarships(Scholarship $scholarship)
    {
        // Delete related categories only
        $scholarship->categories->each(function($category) {
            $category->delete();
        });

        // Delete the scholarship
        $scholarship->delete();

        // Return a JSON response indicating success
        return response()->json([
            'message' => 'Scholarship deleted successfully!',
        ]);
    }

    public function storeCategory(Request $request)
    {
        $validatedData = $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'name' => 'required|string|max:255',
            'required_viewed_lessons' => 'required|integer',
            'reward_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'required_connects' => 'required|integer',
        ]);

        $category = ScholarshipCategory::create($validatedData);

        return response()->json(['message' => 'Scholarship Category created successfully.', 'category' => $category]);
    }

    public function showScholarship($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        return view('super_admin.show_scholarship', compact('scholarship'));
    }
   

    public function updateCategory(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'required_viewed_lessons' => 'required|integer',
            'reward_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'required_connects' => 'required|integer',
        ]);

        $category = ScholarshipCategory::findOrFail($id);
        $category->update($validatedData);

        return response()->json(['message' => 'Category updated successfully.']);
    }

    public function getLatestTests(Request $request)
    {
        $class_level = $request->get('class_level');
        $category_id = $request->get('category_id');

        // Get the latest academic session and term
        $latestAcademicSession = AcademicSession::latest()->first();
        $latestTerm = Term::latest()->first();

        if (!$latestAcademicSession || !$latestTerm) {
            return response()->json(['message' => 'No academic sessions or terms found'], 404);
        }

        // Get tests matching the latest academic session and term
        $tests = Test::where('academic_session_id', $latestAcademicSession->id)
            ->where('term_id', $latestTerm->id)
            ->where('class_level', $class_level)
            ->get();

        // Transform the tests to include term_name and academic_session_name
        $transformedTests = $tests->map(function ($test) {
            return [
                'id' => $test->id,
                'title' => $test->title,
                'class_level' => $test->class_level,
                'type' => $test->type,
                'term_name' => $test->term->name,  // Assuming the term relationship is correctly defined
                'academic_session_name' => $test->academicSession->name  // Assuming the academicSession relationship is correctly defined
            ];
        });

        // Get current tests for the category
        $category = ScholarshipCategory::find($category_id);
        $currentTestIds = $category->tests->pluck('id');

        return response()->json(['tests' => $transformedTests, 'currentTestIds' => $currentTestIds]);
    }


    // Method to toggle tests in a scholarship category
    public function toggleTestInCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:scholarship_categories,id',
            'test_id' => 'required|exists:tests,id',
        ]);

        $category = ScholarshipCategory::find($request->category_id);

        if ($category->tests()->where('test_id', $request->test_id)->exists()) {
            // If the test is already in the category, remove it
            $category->tests()->detach($request->test_id);
            $message = 'Test removed from the scholarship category.';
        } else {
            // If the test is not in the category, add it
            $category->tests()->attach($request->test_id);
            $message = 'Test added to the scholarship category.';
        }

        return response()->json(['message' => $message]);
    }
    public function destroyCategory($id)
    {
        $category = ScholarshipCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Scholarship Category deleted successfully.']);
    }
    public function showScholarshipProgram($class_level)
    {
        // Retrieve the latest academic session and term
        $latestAcademicSession = AcademicSession::latest()->first();
        $latestTerm = Term::latest()->first();
    
        // Get all the scholarships available for this class_level
        $scholarships = Scholarship::where('class_level', $class_level)
                                    ->where('academic_session_id', $latestAcademicSession->id)
                                    ->where('term_id', $latestTerm->id)
                                    ->get();
    
        // Return the view with the scholarships and additional data
        return view('scholarship.program', [
            'class_level' => $class_level,
            'scholarships' => $scholarships,
            'latestAcademicSession' => $latestAcademicSession,
            'latestTerm' => $latestTerm,
        ]);
    }
    public function enrollScholarship($category_id)
    {
        $category = ScholarshipCategory::findOrFail($category_id);
        $student = auth()->user();
    
        // Check if the student is already enrolled in this scholarship category
        if ($student->scholarshipCategories()->where('scholarship_category_id', $category_id)->exists()) {
            return response()->json(['error' => 'You are already enrolled in this scholarship category.'], 400);
        }
    
        $requiredViewedLessons = $category->required_viewed_lessons;
        $requiredConnects = $category->required_connects;
    
        $enrolledLessonsCount = $student->enrolledLessons()
                                        ->where('class_level', $category->scholarship->class_level)
                                        ->count();
    
        $schoolConnects = $student->profile->school_connects;
        $class_level = $category->scholarship->class_level;
    
        if ($enrolledLessonsCount < $requiredViewedLessons) {
            return response()->json(['error' => "You do not meet the required number of viewed lessons to enroll in this scholarship category. Please take more lessons for $class_level."], 400);
        }
    
        if ($schoolConnects < $requiredConnects) {
            return response()->json(['error' => 'You do not have enough school connects to enroll in this scholarship category.'], 400);
        }
    
        // Deduct the required school connects from the student's profile
        $student->profile->school_connects -= $requiredConnects;
        $student->profile->save();
    
        // Enroll the student in the scholarship category
        $student->scholarshipCategories()->attach($category->id);
    
        // Send congratulatory email to the student
        \Mail::to($student->email)->send(new \App\Mail\ScholarshipEnrollmentMail($student, $category));
    
        return response()->json(['success' => 'You have successfully enrolled in the scholarship category.']);
    }
    public function showEnrolledStudents(ScholarshipCategory $category)
    {
        // Paginate and sort students alphabetically by name
        $students = $category->students()->orderBy('first_name')->paginate(10);
        return view('super_admin.scholarship_students', compact('students', 'category'));
    }
    
    public function updateCategoryPublish(Request $request, ScholarshipCategory $category)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
    
        $category->update([
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
    
        // Queue the email for each enrolled student
        $students = $category->students;
        $link = route('scholarship_categories.show_page', $category->id);
        
        foreach ($students as $student) {
            Mail::to($student->email)->queue(new ScholarshipCategoryUpdatedMail($category, $student, $link));
        }
    
        return response()->json(['success' => 'Scholarship category updated successfully.']);
    }
   
    public function showScholarshipCategoryPage(ScholarshipCategory $category)
    {
        $user = Auth::user();
    
        // Check if the user is enrolled in the specified category
        if (!$user->scholarshipCategories->contains($category)) {
            return redirect()->route('home')->with('error', 'You are not registered for the scholarship category.');
        }
    
        return view('scholarship.show_test', compact('category'));
    }

    public function startTest(ScholarshipCategory $category)
    {
        $user = Auth::user();
    
        // Check if the user is enrolled in the specified category
        if (!$user->scholarshipCategories->contains($category)) {
            return redirect()->route('home')->with('error', 'You are not registered for the scholarship category.');
        }
    
        return view('scholarship.start_test', compact('category'));
    }
      
    public function showTestPage(Request $request, $testId)
    {
        $test = Test::findOrFail($testId);
        $user = Auth::user();
    
        // Check if the user is enrolled in any of the scholarship categories that this test belongs to
        $enrolled = $test->scholarshipCategories->pluck('id')->intersect($user->scholarshipCategories->pluck('id'))->isNotEmpty();
    
        if (!$enrolled) {
            return redirect()->route('home')->with('error', 'You are not enrolled in the scholarship category for this test.');
        }
    
        $questionIndex = (int) $request->input('question', 0);
        $action = $request->input('action', 'view'); // Default action to view
    
        if ($action === 'next') {
            $questionIndex++;
        } elseif ($action === 'previous') {
            $questionIndex--;
        } elseif ($action === 'finish') {
            // Handle the finish action, e.g., save responses, calculate score, etc.
        }
    
        // Ensure the index is within the valid range
        $questionIndex = max(0, min($questionIndex, $test->questions->count() - 1));
    
        $currentQuestion = $test->questions->get($questionIndex);
    
        if ($currentQuestion) {
            $answers = $currentQuestion->answers->shuffle();
            $currentQuestionIndex = $questionIndex;
    
            return view('scholarship.test_page', compact('test', 'currentQuestion', 'currentQuestionIndex', 'answers'));
        }
    
        return redirect()->route('home')->with('error', 'Question not found.');
    }
    
    public function fetchNextQuestion(Request $request, $testId)
    {
        $test = Test::findOrFail($testId);
        $user = Auth::user();
    
        // Check if the user is enrolled in any of the scholarship categories that this test belongs to
        $enrolled = $test->scholarshipCategories->pluck('id')->intersect($user->scholarshipCategories->pluck('id'))->isNotEmpty();
    
        if (!$enrolled) {
            return response()->json(['error' => 'You are not enrolled in the scholarship category for this test.'], 403);
        }
    
        $questionIndex = $request->input('question', 0);
        $currentQuestion = $test->questions->get($questionIndex);
    
        if ($currentQuestion) {
            $answers = $currentQuestion->answers->shuffle();
            $currentQuestionIndex = $questionIndex;
    
            return response()->json([
                'currentQuestion' => $currentQuestion,
                'currentQuestionIndex' => $currentQuestionIndex,
                'answers' => $answers,
                'totalQuestions' => $test->questions->count()
            ]);
        }
    
        return response()->json(['error' => 'Question not found.'. $questionIndex], 404);
    }
    
    public function submitTest(Request $request, $testId)
    {
        $test = Test::findOrFail($testId);
        $user = Auth::user();
    
        // Check if the user is enrolled
        $enrolled = $test->scholarshipCategories->pluck('id')->intersect($user->scholarshipCategories->pluck('id'))->isNotEmpty();
    
        if (!$enrolled) {
            return redirect()->route('home')->with('error', 'You are not enrolled in the scholarship category for this test.');
        }
    
        // Calculate the total grade based on the submitted answers
        $answers = $request->input('answers', []);
        $totalScore = 0;
    
        foreach ($answers as $questionId => $answerScores) {
            if (is_array($answerScores)) {
                $totalScore += array_sum($answerScores);
            } else {
                $totalScore += $answerScores;
            }
        }
    
        // Save the grade
        TestGrade::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'grade' => $totalScore,
        ]);
    
        return redirect()->route('home')->with('success', 'Test submitted successfully. Your grade: ' . $totalScore);
    }


    public function showResults(Request $request, $testId)
    {
        $test = Test::findOrFail($testId);
        $score = $request->input('score', 0);

        return view('scholarship.test_result', compact('test', 'score'));
    }


    
}
