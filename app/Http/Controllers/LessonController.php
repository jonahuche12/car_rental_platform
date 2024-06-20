<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use App\Models\LessonTransaction;
use App\Models\Wallet;
use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    //
    public function upload(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'file' => 'required|file',
                'title' => 'required|string|max:255',
                'resumableChunkNumber' => 'sometimes|required|integer',
                'resumableTotalChunks' => 'sometimes|required|integer',
                'resumableIdentifier' => 'sometimes|required|string',
                'resumableFilename' => 'sometimes|required|string',
            ]);
    
            $videoFile = $request->file('file');
            $title = $request->input('title');
            $chunkNumber = $request->input('resumableChunkNumber');
            $totalChunks = $request->input('resumableTotalChunks');
            $identifier = $request->input('resumableIdentifier');
            $filename = $request->input('resumableFilename');
    
            // Generate a unique filename based on the current timestamp
            $newFilename = time() . '_' . $filename;
    
            // Create a directory to store the chunks if it doesn't exist
            $chunkPath = storage_path('app/uploads/chunks');
            if (!file_exists($chunkPath)) {
                mkdir($chunkPath, 0777, true);
            }
    
            // Move the chunk to the temporary directory
            $videoFile->move($chunkPath, "{$identifier}.part{$chunkNumber}");
    
            // Check if all chunks have been uploaded
            if ($chunkNumber == $totalChunks) {
                // All chunks have been uploaded, now combine them
                $combinedFilePath = storage_path("app/public/uploads/{$newFilename}");
    
                $combinedFile = fopen($combinedFilePath, 'ab'); // Open in append binary mode
    
                for ($i = 1; $i <= $totalChunks; $i++) {
                    $chunkFilePath = "{$chunkPath}/{$identifier}.part{$i}";
    
                    // Read the chunk content and append to the combined file
                    $chunkContent = file_get_contents($chunkFilePath);
                    fwrite($combinedFile, $chunkContent);
    
                    // Delete the chunk file after combining
                    unlink($chunkFilePath);
                }
    
                fclose($combinedFile);
    
                // Save lesson details to the database
                $lesson = new Lesson();
                $lesson->title = $title;
                $lesson->user_id = auth()->id();
                $lesson->school_id = auth()->user()->school->id;
                $lesson->video_url = "uploads/{$newFilename}";
                $lesson->save();
    
                return response()->json([
                    'message' => 'Video uploaded successfully',
                    'lesson_id' => $lesson->id,
                ], 200);
            }
    
            return response()->json([
                'message' => 'Chunk uploaded successfully',
            ], 200);
    
        } catch (\Exception $e) {
            // Log and return error
            Log::error('Upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Upload error: ' . $e->getMessage()], 500);
        }
    }
    

    public function updateDetails(Request $request, $lessonId)
    {
        try {
            // Retrieve the lesson by ID
            $lesson = Lesson::findOrFail($lessonId);
    
            // Update lesson details based on request data
            $lesson->subject = $request->input('subject');
            $lesson->class_level = $request->input('class_level');
            $lesson->description = $request->input('description');
    
            // Handle uploaded thumbnail if present
            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailPath = $thumbnail->store('thumbnails', 'public'); // Store thumbnail in storage
                $lesson->thumbnail = '/storage/' . $thumbnailPath; // Save thumbnail URL
    
                // Resize the uploaded thumbnail to a standard dimension (e.g., 300x300)
                $thumbnailImage = Image::make(public_path($lesson->thumbnail));
                $thumbnailImage->fit(540, 360); // Resize and crop the image to 300x300
                $thumbnailImage->save(); // Save the resized image
    
                // Update the lesson with the resized thumbnail URL
                $lesson->thumbnail = '/storage/' . $thumbnailPath;
            }
    
            // Save the updated lesson
            $lesson->save();
    
            // Return a success response
            return response()->json(['message' => 'Lesson details updated successfully'], 200);
        } catch (\Exception $e) {
            // Log and return error response
            \Log::error('Error updating lesson details: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating lesson details: ' . $e->getMessage()], 500);
        }
    }


    public function show(Lesson $lesson)
    {
        // Get the lesson details
        $lesson->load('teacher.profile');

        // Retrieve related lessons
        $relatedLessons = $this->rankRelatedLessons($lesson);
        // dd($relatedLessons, $lesson);

        return view('lessons.show_lesson', compact('lesson', 'relatedLessons'));
    }
    private function rankRelatedLessons(Lesson $lesson)
    {
        // Define common stopwords
        $stopwords = ['a', 'an', 'the', 'and', 'or', 'is', 'it', 'in', 'on', 'for', 'with', 'to', 'of', 'from', 'at'];
    
        // Extract keywords from the lesson's description and remove stopwords
        $keywords = array_diff(explode(' ', $lesson->description), $stopwords);
    
        // Calculate keyword frequencies
        $keywordFrequencies = array_count_values($keywords);
    
        // Retrieve related lessons and calculate relevance scores
        $relatedLessons = Lesson::where('id', '!=', $lesson->id) // Exclude the current lesson
            ->where('class_level', '=', $lesson->class_level) // Match class level
            ->get();
    
        // Calculate relevance scores for each related lesson
        $rankedLessons = $relatedLessons->map(function ($relatedLesson) use ($stopwords, $keywordFrequencies, $lesson) {
            $score = 0;
    
            // Extract keywords from the related lesson's description
            $relatedKeywords = array_diff(explode(' ', $relatedLesson->description), $stopwords);
    
            // Calculate matching keyword scores
            foreach ($relatedKeywords as $keyword) {
                if (isset($keywordFrequencies[$keyword])) {
                    $score += $keywordFrequencies[$keyword];
                }
            }
    
            // Adjust score based on other attributes (class level, subject, same teacher)
            if ($relatedLesson->class_level === $lesson->class_level) {
                $score += 5; // High score for matching class level
            }
            if ($relatedLesson->subject === $lesson->subject) {
                $score += 3; // Medium score for matching subject
            }
            if ($relatedLesson->user_id === $lesson->teacher->id) {
                $score += 10; // Highest score for same teacher
            }
    
            return [
                'lesson' => $relatedLesson,
                'score' => $score,
            ];
        });
    
        // Sort lessons by relevance score in descending order
        $rankedLessons = $rankedLessons->sortByDesc('score')->take(5);
    
        // Extract the sorted lessons
        return $rankedLessons->pluck('lesson')->values();
    }
    
    

    public function removeLesson(Request $request, $lessonId)
    {
        try {
            // Retrieve the lesson by ID
            $lesson = Lesson::findOrFail($lessonId);

            // Delete the associated video file
            if (Storage::disk('public')->exists($lesson->video_url)) {
                Storage::disk('public')->delete($lesson->video_url);
            }

            // Delete the associated thumbnail file
            if ($lesson->thumbnail && Storage::disk('public')->exists($lesson->thumbnail)) {
                Storage::disk('public')->delete($lesson->thumbnail);
            }

            // Delete the lesson from the database
            $lesson->delete();

            return response()->json(['message' => 'Lesson removed successfully'], 200);
        } catch (\Exception $e) {
            // Log and return error response
            Log::error('Error removing lesson: ' . $e->getMessage());
            return response()->json(['message' => 'Error removing lesson: ' . $e->getMessage()], 500);
        }
    }

    public function getLessonById($lessonId)
    {
        try {
            $lesson = Lesson::findOrFail($lessonId);

            return response()->json(['lesson' => $lesson], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching lesson details: ' . $e->getMessage());
            return response()->json(['error' => 'Lesson not found'], 404);
        }
    }
    public function updateLesson(Request $request, $lessonId)
    {
        // Validate the incoming request data
        $request->validate([
            'edit_title' => 'required|string|max:255',
            'edit_subject' => 'required|string|max:255',
            'edit_class_level' => 'required|string|max:255',
            'edit_description' => 'required|string',
            'edit_thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate thumbnail upload
        ]);

        try {
            // Find the lesson by ID
            $lesson = Lesson::findOrFail($lessonId);

            // Update lesson details based on request data
            $lesson->title = $request->input('edit_title');
            $lesson->subject = $request->input('edit_subject');
            $lesson->class_level = $request->input('edit_class_level');
            $lesson->description = $request->input('edit_description');

            // Handle uploaded thumbnail if present
            if ($request->hasFile('edit_thumbnail')) {
                // Get the uploaded thumbnail file
                $thumbnailFile = $request->file('edit_thumbnail');

                // Delete the old thumbnail if it exists
                if ($lesson->thumbnail) {
                    Storage::disk('public')->delete($lesson->thumbnail);
                }

                // Resize and store the new thumbnail
                $thumbnailPath = $this->resizeAndStoreThumbnail($thumbnailFile);

                // Update lesson's thumbnail URL
                $lesson->thumbnail = '/storage/' . $thumbnailPath;
            }

            // Save the updated lesson
            $lesson->save();

            // Return a success response
            return response()->json(['message' => 'Lesson updated successfully'], 200);

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error updating lesson: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'Failed to update lesson'], 500);
        }
    }

    /**
     * Resize and store the thumbnail image.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function resizeAndStoreThumbnail($file)
    {
        // Generate a unique filename for the thumbnail
        $filename = uniqid('thumbnail_') . '.' . $file->getClientOriginalExtension();

        // Resize the image to 540x360 using Intervention Image
        $resizedImage = Image::make($file)->fit(540, 360);

        // Store the resized image in public storage (thumbnails directory)
        $thumbnailPath = 'thumbnails/' . $filename;
        Storage::disk('public')->put($thumbnailPath, (string) $resizedImage->encode());

        // Return the relative path to the stored thumbnail
        return $thumbnailPath;
    }
    public function checkEnrollment(Request $request)
    {
        // Retrieve lesson ID from the request
        $lessonId = $request->input('lesson_id');
    
        // Get the currently authenticated user
        $user = auth()->user();
    
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        // Check if the user is enrolled in the lesson
        $isEnrolled = $user->enrolledLessons()->where('lessons.id', $lessonId)->exists();
    
        // Alternatively, check if the lesson's user_id matches the authenticated user's ID
        if (!$isEnrolled) {
            $lesson = Lesson::find($lessonId);
            $isEnrolled = $lesson && $lesson->user_id == $user->id;
        }
    
        return response()->json(['is_enrolled' => $isEnrolled]);
    }
    
    public function checkSchoolConnects(Request $request)
    {
        // Retrieve lesson ID and required connects from the request
        $lessonId = $request->input('lesson_id');
        $requiredConnects = $request->input('required_connects');
        // Retrieve the lesson by ID
        $lesson = Lesson::findOrFail($lessonId);

        // Get the currently authenticated user
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Assuming 'school_connects' is a property on the user's profile or model
        $userProfile = $user->profile;

        if (!$userProfile) {
            return response()->json(['error' => 'User profile not found'], 404);
        }

        $userConnects = $userProfile->school_connects;

        // Check if the user has enough school connects
        if ($userConnects >= $requiredConnects) {
            // Calculate lesson earnings based on connects purchased
            $lessonEarnings = $requiredConnects * 6; // Each school_connect is worth 6 units

            // Calculate school's earnings (20% of lesson earnings)
            $schoolEarnings = $lessonEarnings * 0.20 / 3;

            // Calculate teacher's earnings (40% of lesson earnings)
            $teacherEarnings = $lessonEarnings * 0.40 / 3;

            // Update teacher's wallet if exists
            if ($lesson->teacher && $lesson->teacher->wallet) {
                $lesson->teacher->wallet->increment('balance', ceil($teacherEarnings));

                // Record teacher's earnings Lessontransaction
                $lessonTransaction = new LessonTransaction();
                $lessonTransaction->lesson_id = $lessonId;
                $lessonTransaction->user_id = $lesson->user_id;
                $lessonTransaction->type = 'teacher_earnings';
                $lessonTransaction->amount = ceil($teacherEarnings);
                $lessonTransaction->save();
            }

            // Update school's wallet if exists
            if ($lesson->school && $lesson->school->wallet) {
                $lesson->school->wallet->increment('balance', ceil($schoolEarnings));

                // Record school's earnings Lessontransaction
                $lessonTransaction = new LessonTransaction();
                $lessonTransaction->lesson_id = $lessonId;
                $lessonTransaction->school_id = $lesson->school_id;
                $lessonTransaction->type = 'school_earnings';
                $lessonTransaction->amount = ceil($schoolEarnings);
                $lessonTransaction->save();
            }

            // Subtract the required connects from the user's profile
            $userProfile->school_connects -= $requiredConnects;
            $userProfile->save();

            // Update the lesson_user pivot table
            DB::table('lesson_user')->updateOrInsert(
                ['lesson_id' => $lessonId, 'user_id' => $user->id],
                ['role' => $userProfile->role]
            );

            // Update lesson's school_connects_required attribute
            $baseSchoolConnectsRequired = $lesson->school_connects_required / 3;
            $enrolledUsersCount = $lesson->enrolledUsers()->count(); // Get the count of enrolled users

            $newSchoolConnectsRequired = ceil($baseSchoolConnectsRequired * log($enrolledUsersCount + 1, 2)); // Round up to nearest whole number
            $incrementedRequiredConnects = ceil($newSchoolConnectsRequired / 3);

            $lesson->increment('school_connects_required', $incrementedRequiredConnects);

            return response()->json([
                'has_enough_connects' => true,
                'new_school_connects_required' => $incrementedRequiredConnects,
                'lesson_earnings' => $lessonEarnings,
                'school_earnings' => $schoolEarnings,
                'teacher_earnings' => $teacherEarnings,
            ]);
        } else {
            return response()->json(['has_enough_connects' => false]);
        }
    }

     /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lessonId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $lessonId)
    {
        // Validate request data
        $request->validate([
            'comment_content' => 'required|string|max:255',
        ]);
    
        // Find the lesson
        $lesson = Lesson::findOrFail($lessonId);
    
        // Create a new comment
        $comment = new Comment();
        $comment->lesson_id = $lesson->id;
        $comment->user_id = Auth::id(); // Assuming you are using authentication
        $comment->content = $request->input('comment_content');
        $comment->save();
        
        // Update the comment count
        $commentCount = $lesson->comments()->count();
    
        // Return a JSON response indicating success
        return response()->json(['message' => 'Comment posted successfully', 'comment' => $comment, 'comment_count' => $commentCount]);
    }
    
    /**
     * Store a newly created reply to a comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $commentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function reply(Request $request, $commentId)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'reply_content' => 'required|string|max:255',
            'parent_reply_id' => 'nullable|exists:replies,id', // Validate existence of parent reply if provided
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
    
        // try {
            // Find the parent comment
            $parentComment = Comment::findOrFail($commentId);
    
            // Create a new reply
            $reply = new Reply();
            $reply->comment_id = $parentComment->id;
            $reply->user_id = Auth::id(); // Assuming you are using authentication
            $reply->content = $request->input('reply_content');
    
            // Check if a parent reply ID is provided
            $parentReplyId = $request->input('parent_reply_id');
            if ($parentReplyId) {
                // Validate and find the parent reply
                $parentReply = Reply::findOrFail($parentReplyId);
                $reply->parent_reply_id = $parentReply->id;
            }
    
            $reply->save();
    
            // Return a JSON response with the newly created reply
            return response()->json(['message' => 'Reply posted successfully', 'reply' => $reply], 200);
        // } catch (\Exception $e) {
        //     // Handle any unexpected errors
        //     return response()->json(['error' => 'Failed to post reply. Please try again.'], 500);
        // }
    }

    public function toggleFavorite(Request $request, Lesson $lesson)
    {
        $user = $request->user();
    
        if ($user->favoriteLessons()->where('lesson_id', $lesson->id)->exists()) {
            $user->favoriteLessons()->detach($lesson->id);
            $message = 'Lesson removed from favorites';
        } else {
            $user->favoriteLessons()->attach($lesson->id);
            $message = 'Lesson added to favorites';
        }
    
        $favoriteCount = $lesson->favoritedByUsers()->count();
    
        return response()->json([
            'message' => $message,
            'favorited' => !$user->favoriteLessons()->where('lesson_id', $lesson->id)->exists(), // Check if lesson is favorited after toggle
            'favorite_count' => $favoriteCount
        ], 200);
    }

    public function toggleLike(Request $request, Lesson $lesson)
    {
        $user = $request->user();

        if ($user->likedLessons()->where('lesson_id', $lesson->id)->exists()) {
            $user->likedLessons()->detach($lesson->id);
            $liked = false;
        } else {
            $user->likedLessons()->attach($lesson->id);
            $liked = true;
        }

        $likeCount = $lesson->likedUsers()->count();

        return response()->json([
            'liked' => $liked,
            'like_count' => $likeCount
        ], 200);
    }

    public function fetchLessonComments(Lesson $lesson, Request $request)
    {
        $lastDisplayedCommentId = $request->input('last_displayed_comment_id');
    
        // Retrieve the lesson's comments with eager loading of user and replies
        $query = $lesson->comments()->with('user.profile', 'replies.user.profile')->latest();
    
        if ($lastDisplayedCommentId) {
            $query->where('id', '>', $lastDisplayedCommentId);
        }
    
        $comments = $query->get();
        
        // Count all comments for the lesson
        $commentsCount = $lesson->comments()->count();
    
        // Calculate total comment count including replies
        $totalCommentCount = $commentsCount + $comments->sum(function ($comment) {
            return $comment->replies->count();
        });
    
        // Return the comments and total comment count as a JSON response
        return response()->json([
            'comments' => $comments,
            'comment_count' => $totalCommentCount,
        ]);
    }
    


}
