<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CourseController;


Auth::routes(['verify' => true]);


Route::get('/', function () {
    return view('welcome');
})->name('/');


// Registration page route
Route::get('/register', [AuthController::class, 'showRegistrationForm']);
// Login page route
Route::get('/login',  [AuthController::class, 'showLoginForm']);

// Confirm email page route (if needed)
Route::get('/confirm-email/{token}', [AuthController::class, 'showConfirmEmailForm'] );



Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('verified');

Route::get('login/google', 'App\Http\Controllers\Auth\LoginController@redirectToGoogle')->name('login.google');
Route::get('login/google/callback', 'App\Http\Controllers\Auth\LoginController@handleGoogleCallback');

Route::get('login/facebook', 'App\Http\Controllers\Auth\LoginController@redirectToFacebook')->name('login.facebook');
Route::get('login/facebook/callback', 'App\Http\Controllers\Auth\LoginController@handleFacebookCallback');

Route::get('login/github', 'App\Http\Controllers\Auth\LoginController@redirectToGithub')->name('login.github');
Route::get('login/github/callback', 'App\Http\Controllers\Auth\LoginController@handleGithubCallback');

Route::get('login/apple', 'App\Http\Controllers\Auth\LoginController@redirectToApple')->name('login.apple');
Route::get('login/apple/callback', 'App\Http\Controllers\Auth\LoginController@handleAppleCallback');

Route::post('/confirm-transfer',  [PaymentController::class, 'confirmTransfer'])->name('confirm_transfer');
   
// ... (other routes)
Route::post('/set-transfer-session',  [PaymentController::class, 'setTransferSession'])->name('set_transfer_session');
Route::get('/confirm-payment/{payment_session_id}',   [PaymentController::class, 'confirmPayment'])->name('confirm_payment');
Route::any('/remove-transfer-session',  [PaymentController::class, 'removeTransferSession'])->name('remove-transfer-session');

Route::post('/confirm-user-transfer/{paymentSessionId}', [PaymentController::class, 'confirmTransfer'])->name('confirm_user_transfer');

Route::get('/check-payment-confirmed/{payment_session_id}', [PaymentController::class, 'checkPaymentConfirmed'])->name('check-payment-confirmed');



Route::post('/confirm-transfer',  [PaymentController::class, 'confirmTransfer'])->name('confirm_transfer');



Route::middleware('auth')->group(function () {
    // ... Other routes

    Route::post('/profile/create', [ProfileController::class, 'create'])->name('profile.create');

    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::get('/check-profile', [ProfileController::class, 'checkProfile']);
    
    Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
    Route::post('/update-profile-picture', [ProfileController::class, 'updateProfilePicture']);
    Route::post('/qualifications', [HomeController::class, 'storeQualification']);
   

    Route::get('/search-schools', [ProfileController::class,'searchSchools'])->name('search-schools');
    Route::get('/payment/activate/{package_id}', [PaymentController::class, 'userActivationPage'])
    ->name('payment.activate');
    Route::get('/view_curriculum/{classId}', [HomeController::class, 'viewCurriculum'])->name('view_curriculum');
    Route::get('/student/{studentId}', [HomeController::class, 'showStudent'])->name('student');
    Route::get('/view_section/{sectionId}', [AdminController::class, 'viewSection'])->name('view_section');

    Route::post('/submit-course',  [HomeController::class, 'submitCourse'])->name('submit.course');
    Route::post('/offer-course',  [HomeController::class, 'offerCourse'])->name('offer.course');
    Route::post('/create-lesson', [HomeController::class, 'createLesson'])->name('create.lesson');


    Route::post('/upload-chunk', [HomeController::class, 'uploadChunk']);
    Route::post('/merge-chunks', [HomeController::class, 'mergeChunks']);
    Route::post('/create-lesson', [HomeController::class, 'createLesson']);
    Route::post('/upload', [LessonController::class, 'upload'])->name('upload');
    Route::post('/lessons/{lessonId}/update-details', [LessonController::class, 'updateDetails']);

    // routes/web.php

    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');

    Route::post('/remove-lesson/{lessonId}', [LessonController::class, 'removeLesson'])->name('remove.lesson');
    Route::get('/lessons-edit/{lessonId}', [LessonController::class, 'getLessonById'])->name('lessons-edit.get');
    Route::post('/lessons-update/{lessonId}', [LessonController::class, 'updateLesson'])->name('lessons-update.update');

    Route::post('/credit-school-connects', [HomeController::class, 'creditSchoolConnects'])->name('credit.school_connects');
    Route::post('/buy-connects',  [HomeController::class, 'buyConnects'])->name('buy_connects');
    Route::post('/buy-connects-for-student/{id}',  [HomeController::class, 'buyConnectsForStudent'])->name('buy_connects_for_student');
    Route::get('/buy-connects-for-student-page/{id}/{amount}',  [HomeController::class, 'buyConnectsForStudentPage'])->name('buy-connects-for-student-page');
    Route::get('/buy-connects_page/{amount}',  [HomeController::class, 'buyConnectsPage'])->name('buy-connects_page');

    // Named route for buying package
    Route::get('/buy-package', [HomeController::class, 'buyPackage'])->name('buy_package');

    // Named route for contacting support
    Route::get('/contact-support', [HomeController::class, 'contactSupport'])->name('contact_support');
    Route::post('/check-school-connects', [LessonController::class, 'checkSchoolConnects'])->name('check_school_connects');
    Route::post('/check-enrollment', [LessonController::class, 'checkEnrollment'])->name('lessons.checkEnrollment');

    Route::post('/lesson/{lesson}/comment', [LessonController::class, 'store'])->name('comment.store');
    Route::post('/comment/{comment}/reply', [LessonController::class, 'reply'])->name('comment.reply');
    Route::post('/lessons/{lesson}/favorite', [LessonController::class, 'toggleFavorite'])->name('lessons.favorite');
    Route::post('/lessons/{lesson}/like', [LessonController::class, 'toggleLike'])->name('lessons.like');
    Route::get('/lessons/{lesson}/comments',  [LessonController::class, 'fetchLessonComments'])->name('lesson.comments');

    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/search-results',[SearchController::class, 'showResults'])->name('search.results');
    Route::get('/load-more-lessons', [HomeController::class, 'loadMoreLessons'])->name('load.more.lessons');
    Route::get('/load-more-viewedlessons', [HomeController::class, 'loadMoreViewedLessons'])->name('load.more.viewedlessons');
    Route::get('/load-more-favlessons', [HomeController::class, 'loadMoreFavLessons'])->name('load.more.favlessons');
    Route::get('/load-more-events', [HomeController::class, 'loadMoreEvents'])->name('load.more.events');
    Route::get('/load-more-people', [HomeController::class, 'loadMorePeople'])->name('load.more.people');

    Route::get('/load-more-curriculum-lessons', [CourseController::class, 'loadMoreCurriculumLessons'])->name('load.more.curriculum.lessons');

    Route::get('/classes/{class}', [HomeController::class, 'showClass'])->name('class.show');


    Route::get('/curriculum/{course}/class/{class_id}', [CourseController::class, 'showCurriculumforClass'])->name('curriculum.show');
  
    Route::get('/get-curriculum-details/{curriculum}', [CourseController::class, 'getCurriculumDetails'])
    ->name('curriculum.details');

    Route::get('/get-related-lessons/{curriculum}', [CourseController::class, 'getRelatedLessons'])
    ->name('curriculum.related_lessons');

    Route::get('/get-topic-details/{topic}/{curriculum}', [CourseController::class, 'getTopicDetails']);
    Route::get('/curriculum/{curriculumId}/topic/{topicId}', [CourseController::class, 'getRelatedTopicLessons']);

    Route::delete('/school/{id}', [SchoolController::class, 'deleteSchool'])->name('school.delete');
    Route::get('/get-grade-distribution/{courseCode}', [SchoolController::class, 'getGradeDistribution']);
    Route::get('/course/{courseCode}/gradedistribution', [SchoolController::class, 'getGradeDistribution']);

    Route::get('/terms/{academicSession}', [SchoolController::class, 'getTermsByAcademicSession']);
    Route::get('/compile-results/{studentId}', [TeacherController::class, 'compileResults'])->name('compile.results');
    Route::get('/result_page', [TeacherController::class, 'showResults'])->name('result.page');

    Route::post('/publish-result', [TeacherController::class, 'publishResult'])->name('publish_result');
    Route::get('/view-student-result/{student_id}/{academic_session_id}/{term_id}', [HomeController::class, 'viewStudentResult'])->name('view_student_result');


    Route::get('/find-wards', [HomeController::class, 'findWards'])->name('find-wards');
    Route::post('/add-ward',  [HomeController::class, 'addWard'])->name('add-ward');
    Route::delete('/remove-ward/{ward}', [HomeController::class, 'removeWard'])->name('remove-ward');

    Route::get('/ward/confirm/{token}', [HomeController::class, 'confirmGuardian'])->name('ward.confirm');      

    Route::get('/student/{student_id}/progress', [HomeController::class, 'viewStudentProgress'])->name('student.progress');


});

Route::middleware(['auth', 'superadmin'])->group(function () {
    // Routes accessible only by super admins

    // Example: Creating school packages
    Route::get('/create-school-package', [SuperAdminController::class, 'manageSchoolPackage'])->name('manage_school_packages');
    Route::get('/create-curriculum', [SuperAdminController::class, 'manageCurriculum'])->name('manage_curriculum');
    Route::post('/school-packages', [SuperAdminController::class, 'createPackage']);
    Route::post('/packages/{id}/edit', [SuperAdminController::class, 'editPackage'])->name('packages.edit');
    
    Route::delete('/packages/{id}', [SuperAdminController::class, 'deletePackage'])->name('packages.delete');

    Route::post('/curriculum/{id}/edit', [SuperAdminController::class, 'editCurriculum'])->name('curriculum.edit');
    Route::delete('/curriculum/{id}', [SuperAdminController::class, 'deleteCurriculum'])->name('curriculum.delete');

    Route::post('/curriculum_topic/{id}/edit', [SuperAdminController::class, 'editCurriculumTopic'])->name('curriculum_topic.edit');
    Route::delete('/curriculum_topic/{id}', [SuperAdminController::class, 'deleteCurriculumTopic'])->name('curriculum_topic.delete');

    Route::get('/admin/confirm-payment/{payment_session_id}', [SuperAdminController::class, 'adminConfirmPayment'])->name('admin_confirm_payment');

    Route::post('/admin_confirm-transfer',  [SuperAdminController::class, 'confirmTransfer'])->name('admin_confirm_transfer');

    Route::get('/create-user-package', [SuperAdminController::class, 'manageUserPackage'])->name('manage_user_packages');

    Route::post('/user-packages', [SuperAdminController::class, 'createUserPackage']);
    Route::post('/user_packages/{id}/edit', [SuperAdminController::class, 'editUserPackage'])->name('user_packages.edit');
    
    Route::delete('/user_packages/{id}', [SuperAdminController::class, 'deleteUserPackage'])->name('user_packages.delete');

    Route::post('/store_curricula', [SuperAdminController::class, 'storeCurriculum'])->name('store_curricula');
    Route::post('/store_curriculum_topic/{curriculum_id}', [SuperAdminController::class, 'storeCurriculumTopic'])->name('store_curriculum_topic');

    Route::get('/create-academic_session', [SuperAdminController::class, 'manageAcademicSession'])->name('manage_academic_sessions');
    Route::get('/create-test', [SuperAdminController::class, 'manageTest'])->name('manage_tests');
    Route::post('/academic_sessions', [SuperAdminController::class, 'createAcademicSession']);
    Route::post('/academic_sessions/{id}/edit', [SuperAdminController::class, 'editAcademicSession'])->name('academic_sessions.edit');
    
    Route::delete('/academic_sessions/{id}', [SuperAdminController::class, 'deleteAcademicSession'])->name('academic_sessions.delete');

    Route::post('/add_term/{academic_session}', [SuperAdminController::class, 'addTerm'])->name('add_term');
    Route::get('/terms/{id}', [SuperAdminController::class, 'getTermDetails']);

    Route::post('/edit_term/{id}', [SuperAdminController::class, 'editTerm']);
    Route::delete('/delete-term/{id}', [SuperAdminController::class, 'deleteTerm']);


    Route::get('/manage_all_schools', [SuperAdminController::class, 'manageAllSchools'])->name('manage_all_schools');

    // In web.php

    Route::post('/tests', [SuperAdminController::class, 'storeTest'])->name('tests.store');

    // Add other routes for competitions, quizzes, etc.
});

Route::middleware(['auth', 'school_owner'])->group(function () {
    Route::get('/manage_schools', [SchoolController::class, 'manageSchools'])->name('manage_schools');
    Route::get('create_school', [SchoolController::class, 'createSchool'])->name('create_school');
    Route::get('/school-packages', [SchoolController::class, 'showPackages'])->name('school-packages');
    Route::get('/create-school/{packageId}', [SchoolController::class, 'showCreateSchoolForm'])->name('create-school');
    Route::post('/store-school', [SchoolController::class, 'storeSchool'])->name('store-school');
    Route::post('/check-unique-school-name', 'SchoolController@checkUniqueSchoolName')->name('check-unique-school-name');
    Route::post('/check-unique-school-email', 'SchoolController@checkUniqueSchoolEmail')->name('check-unique-school-email');
    Route::post('/school/{id}/edit', [SchoolController::class, 'editSchool'])->name('school.edit');
    
    Route::post('/activate-school/{schoolId}', [SchoolController::class, 'activateSchool']);
    Route::get('/payment', [PaymentController::class, 'showPackagePaymentPage']);
    
    Route::get('/create-activation', [PaymentController::class, 'createActivation'])->name('create_activation');

    Route::get('/schools/{id}', [SchoolController::class, 'Ownershow'])->name('schools.show');

    Route::get('/school/{schoolId}/{view}', [SchoolController::class, 'showSchool'])
    ->name('school.show')
    ->where(['schoolId' => '[0-9]+', 'view' => 'admin|teachers|students|classes|events|curriculum']);
    Route::post('/make-admin/{user}', [SchoolController::class,'confirmAndMakeAdmin'])->name('make.admin');

    Route::post('/grant-permission/{adminId}', [SchoolController::class, 'grantPermission']);
    Route::post('/remove-admin/{adminId}', [SchoolController::class, 'removeAdmin'])->name('remove.admin');
    Route::post('/update-academic-session', [SchoolController::class, 'updateAcademicSession'])->name('update.academic.session');
    Route::post('/update-term', [SchoolController::class, 'updateTerm'])->name('update.term');




});

Route::group(['middleware' => 'verifyAdminPermissions:confirm_student'], function () {
    // Your routes that require admin confirmation and permission_confirm_student
    Route::post('/make-student/{user}', [AdminController::class,'confirmAndMakeStudent'])->name('make.student');
    Route::get('/manage-students/{schoolId}', [AdminController::class, 'showStudents'])->name('manage-students');

    Route::post('/remove-student/{studentId}', [AdminController::class, 'removeStudent'])->name('remove.student');
});


Route::group(['middleware' => 'verifyAdminPermissions:create_class'], function () {
    // Your routes that require admin confirmation and permission_create_class
    
    Route::get('/manage-classes/{schoolId}', [AdminController::class, 'showClass'])->name('manage-classes');
    Route::post('/school-class', [AdminController::class, 'createClass'])->name('school-class');


    Route::post('/class/{id}/edit', [AdminController::class, 'editClass'])->name('class.edit');
    Route::post('/class_section/{id}/add', [AdminController::class, 'addClassSection'])->name('class_section.add');

    Route::post('/section/{id}/edit', [AdminController::class, 'editSection'])->name('section.edit');
    Route::delete('/section/{id}', [AdminController::class, 'deleteSection'])->name('section.delete');


    Route::post('/make-student-section/{user}', [AdminController::class,'confirmAndAddStudentToSection'])->name('make.student-section');

    
});

Route::group(['middleware' => 'verifyAdminPermissions:confirm_teacher'], function () {
    // Your routes that require admin confirmation and permission_confirm_student
    Route::post('/make-teacher/{user}', [AdminController::class,'confirmAndMakeTeacher'])->name('make.teacher');
    Route::get('/manage-teachers/{schoolId}', [AdminController::class, 'showTeachers'])->name('manage-teachers');
    Route::post('/make-teacher/{user}', [AdminController::class,'confirmAndMakeTeacher'])->name('make.teacher');
    Route::post('/remove-teacher/{teacherId}', [AdminController::class, 'removeTeacher'])->name('remove.teacher');

});

Route::group(['middleware' => 'verifyAdminPermissions:create_course'], function () {
    
    Route::get('/manage-courses/{schoolId}', [AdminController::class, 'showCourses'])->name('manage-courses');
    Route::post('/school-course', [AdminController::class, 'createCourse'])->name('school-course');
    Route::post('/delete-course/{courseId}', [AdminController::class, 'deleteCourse'])->name('delete.course');

    Route::post('/update_class_section_course', [AdminController::class, 'updateSectionTeacherCourse'])->name('update.section_teacher_course');
    
    Route::get('/fetch-class-sections/{courseId}', [AdminController::class, 'fetchClassSections']);
    Route::post('/course/{id}/edit', [AdminController::class, 'editCourse'])->name('course.edit');

    
});


Route::group(['middleware' => 'verifyAdminPermissions:create_event'], function () {
    
    Route::get('/manage-events/{schoolId}', [AdminController::class, 'showEvents'])->name('manage-events');
    Route::post('/school-event', [AdminController::class, 'createEvent'])->name('school-event');
    Route::post('/delete-event/{eventId}', [AdminController::class, 'deleteEvent'])->name('delete.event');

    Route::post('/event/{id}/edit', [AdminController::class, 'editEvent'])->name('event.edit');
    // Route::post('/update_class_section_course', [AdminController::class, 'updateSectionTeacherCourse'])->name('update.section_teacher_course');
    
    // Route::get('/fetch-class-sections/{courseId}', [AdminController::class, 'fetchClassSections']);
    // Route::post('/course/{id}/edit', [AdminController::class, 'editCourse'])->name('course.edit');

    
});

Route::middleware(['auth', 'verifyTeacher'])->group(function () {
    // Your routes for authenticated teachers
    Route::get('/manage-form_classes/{teacherId}', [TeacherController::class, 'showFormClasses'])->name('manage-form_classes');
    // routes/web.php

    Route::post('/toggle-attendance', [TeacherController::class, 'toggleAttendance'])->name('toggleAttendance');
    // web.php

    Route::get('/assignment/{courseId}/{classSectionId}/{teacherId}', [TeacherController::class, 'assignmentPage'])->name('assignment');
    Route::get('/assessment/{courseId}/{classSectionId}/{teacherId}', [TeacherController::class, 'assessmentPage'])->name('assessment');
    Route::get('/exam/{courseId}/{classSectionId}/{teacherId}', [TeacherController::class, 'examPage'])->name('exam');

    Route::post('/create_assignmment', [TeacherController::class, 'createAssignment'])->name('create_assignmment');
    Route::post('saveGrade', [TeacherController::class, 'saveGrade'])->name('saveGrade');
    Route::post('/assignment/{id}/edit', [TeacherController::class, 'editAssignment'])->name('assignment.edit');
    Route::delete('/assignment/{assignmentId}', [TeacherController::class, 'deleteAssignment'])->name('delete.assignment');


    Route::post('/create_assessment', [TeacherController::class, 'createAssessment'])->name('create_assessment');
    Route::post('saveGradeAssessment', [TeacherController::class, 'saveGradeAssessment'])->name('saveGradeAssessment');
    Route::post('/assessment/{id}/edit', [TeacherController::class, 'editAssessment'])->name('assessment.edit');
    Route::delete('/assessment/{assessmentId}', [TeacherController::class, 'deleteAssessment'])->name('delete.assessment');


    Route::post('/create_exam', [TeacherController::class, 'createExam'])->name('create_exam');
    Route::post('saveGradeExam', [TeacherController::class, 'saveGradeExam'])->name('saveGradeExam');
    Route::post('/exam/{id}/edit', [TeacherController::class, 'editExam'])->name('exam.edit');
    Route::delete('/exam/{examId}', [TeacherController::class, 'deleteExam'])->name('delete.exam');
    Route::post('/archive/{model}/{assignment}', [TeacherController::class, 'archive'])->name('archive');

    Route::post('/toggle-assignment/{model}/{assignmentId}', [TeacherController::class, 'toggleAssignmentStatus'])->name('toggle.assignment.status');

    Route::post('/toggle-assessment/{model}/{assessmentId}', [TeacherController::class, 'toggleAssignmentStatus'])->name('toggle.assessment.status');


    Route::post('/toggle-exam/{model}/{examId}', [TeacherController::class, 'toggleAssignmentStatus'])->name('toggle.exam.status');

    Route::post('/promotion_criteria', [TeacherController::class, 'storePromotionCriteria'])->name('promotion_criteria.store');

    Route::get('/fetch-criteria-values', [TeacherController::class, 'fetchCriteriaValues'])->name('fetch.criteria.values');

    Route::post('/update_promotion_criteria', [TeacherController::class, 'updatePromotionCriteria'])->name('update_promotion_criteria');
    Route::post('/promote-students', [TeacherController::class, 'promoteStudents'])->name('promote_students');

    Route::post('/get-next-class', [TeacherController::class, 'getNextClass'])->name('get_next_class');



});
