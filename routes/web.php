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

    Route::get('/check-profile', [ProfileController::class, 'checkProfile']);
    
    Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
    Route::post('/update-profile-picture', [ProfileController::class, 'updateProfilePicture']);
    Route::post('/qualifications', [HomeController::class, 'storeQualification']);
   

    Route::get('/search-schools', [ProfileController::class,'searchSchools'])->name('search-schools');
    Route::get('/payment/activate/{package_id}', [PaymentController::class, 'userActivationPage'])
    ->name('payment.activate');
    Route::get('/view_curriculum/{classId}', [HomeController::class, 'viewCurriculum'])->name('view_curriculum');
    Route::get('/student/{studentId}', [HomeController::class, 'showStudent'])->name('student');



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
    
    Route::delete('/school/{id}', [SchoolController::class, 'deleteSchool'])->name('school.delete');
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

    Route::get('/view_section/{sectionId}', [AdminController::class, 'viewSection'])->name('view_section');

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

    
});

Route::middleware(['auth', 'verifyTeacher'])->group(function () {
    // Your routes for authenticated teachers
    Route::get('/manage-form_classes/{teacherId}', [TeacherController::class, 'showFormClasses'])->name('manage-form_classes');
    // routes/web.php

    Route::post('/toggle-attendance', [TeacherController::class, 'toggleAttendance'])->name('toggleAttendance');

});
