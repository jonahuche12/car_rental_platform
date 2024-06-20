@extends('layouts.app')

@section('title')
Central School System - {{ $user->profile->full_name}}
@endsection

@section('style')
<style>
  .complete_profile{
    display: none;
  }
  .profile_pic_style{
    cursor: pointer; 
    position: absolute; 
    bottom: -10px; 
    right: 100px;
  }
  .qualification-container {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
  }
  .lesson-form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .form-group label {
            color: #333;
        }
        .file-info {
            color: #666;
        }
        .video-preview-container {
            position: relative;
            padding-top: 56.25%;
            background-color: #000;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }
        .video-placeholder {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }
        .video-preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .btn-group {
            margin-top: 20px;
        }
        .spinner-border {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        #thumbnailPreviewContainer {
            margin-top: 10px;
        }
        #thumbnailPreview {
            max-width: 100%;
            max-height: 100%;
        }
        .text-dark small {
            color: #666;
        }
 

</style>
@endsection
@section('page_title', "Dashboard")
@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb3', "Dashboard")

@section('content')

@include('sidebar')

    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
  
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card">
                <div class="mb-4 p-2">
                <a href="{{ route('user.wallet', ['userId' => $user->id]) }}" class="text-decoration-none">
                    <strong>Wallet Balance:</strong> ₦{{ number_format($wallet_balance, 2) }}
                   </a>
                </div>
                @if (!$has_wallet)
                    <div class="alert alert-warning alert-dismissible fade show small-text" role="alert">
                        You don't have a wallet. Please <a href="{{ route('user.package') }}" class="alert-link">upgrade your account</a> to create a wallet so that your earnings can be saved.
                        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif ($user->userPackage && $user->userPackage->name !== 'Premium')
                    <div class="alert alert-info alert-dismissible fade show small-text" role="alert">
                        Upgrade to our <a href="{{ route('user.package') }}" class="text-light badge bg-success p-2">Premium package</a> to unlock more features.
                        <button type="button" class="close text-light" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                   
                    <li class="nav-item small-text">
                        <a class="nav-link active" href="#lessons" data-toggle="tab">
                            <i class="fas fa-book-open nav-icon"></i>
                            Lessons <sup class="text-green"> {{$lesson_count }} </sup>
                        </a>
                    </li>
                    <li class="nav-item small-text">
                        <a class="nav-link" href="#courses" data-toggle="tab">
                            <i class="fas fa-book nav-icon"></i>
                            Courses
                        </a>
                    </li>
                    <li class="nav-item small-text">
                        <a class="nav-link " href="#analytics" data-toggle="tab">
                            <i class="fas fa-chart-bar nav-icon"></i>
                            Analytics
                        </a>
                    </li>
                    <li class="nav-item small-text">
                        <a class="nav-link " href="#wards" data-toggle="tab">
                            <i class="fas fa-users nav-icon"></i>
                            Wards
                        </a>
                    </li>

                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class=" tab-pane" id="courses">
                    <!-- Post -->
                    <div class="post">
                      <div class="row">
                        <div class="col-12">
                          <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">Courses You handle </h3>
                            </div>
                            <!-- ./card-header -->
                            <div class="card-body table-responsive">
                              <table class="table table-bordered table-hover">
                                <thead>
                                  <tr class="bg-dark" >
                                    <th>#</th>
                                    <th>Course</th>
                                    <!-- <th>Class</th> -->
                                    <!-- <th>No of Students</th> -->
                                    <th>Compulsory</th>
                                    <th>Description</th>
                                  </tr>
                                </thead>
                                <tbody>
                                @foreach(auth()->user()->teacher_courses()->get() as $course)
                                <tr data-widget="expandable-table"  aria-expanded="false">
                                    <td>{{$loop->iteration }}</td>
                                    <td><a class="text-primary"> {{$course->name}}</a></td>
                                    <td class="text-success">
                                        @if($course->compulsory)
                                            <span class=""><i class="fa fa-check"></i>compulsory</span>
                                        @else
                                            <span class="text-secondary"><i class="fa fa-check"></i>elective</span>
                                        @endif
                                    </td>
                                    <td>{{$course->description}}</td>
                                </tr>
                                <tr class="expandable-body">
                                    <td colspan="5">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Class Name</th>
                                                    <th>Number of Students</th>
                                                    <th>Records</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($course->class_sections()->wherePivot('teacher_id', auth()->id())->get() as $class)
                                                    <tr>
                                                        <td>{{$class->name}}</td>
                                                        <td>{{$class->students->count()}}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="{{ route('assignment', ['courseId' => $course->id, 'classSectionId' => $class->id, 'teacherId' => auth()->id()]) }}" class="btn btn-sm bg-primary">
                                                                    <i class="fas fa-file"></i><span class="d-none d-sm-inline"> Assignment</span>
                                                                </a>
                                                                <a href="{{ route('assessment', ['courseId' => $course->id, 'classSectionId' => $class->id, 'teacherId' => auth()->id()]) }}" class="btn btn-sm bg-primary">
                                                                    <i class="fas fa-tasks"></i><span class="d-none d-sm-inline"> Assessment</span>
                                                                </a>
                                                                <a href="{{ route('exam', ['courseId' => $course->id, 'classSectionId' => $class->id, 'teacherId' => auth()->id()]) }}" class="btn btn-sm bg-primary">
                                                                    <i class="fas fa-book"></i><span class="d-none d-sm-inline"> Exam</span>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach


                                </tbody>
                              </table>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <!-- /.card -->
                        </div>
                      </div>
                    </div>
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane active" id="lessons">
                    @include('partials.create_lesson')
                    <div class="lessons-container" >
                        <div class="row">
                            @foreach ($lessons_per_page as $lesson)
                                @include('partials.lesson_card', ['lesson' => $lesson])
                            @endforeach
                        </div>
                    </div>
                </div>

                @foreach ($lessons_per_page as $lesson)
                    @include('partials.remove_lesson_modal', ['lesson' => $lesson])
                @endforeach

                                <!-- /.tab-pane -->
                  <div class="tab-pane" id="analytics">
                      <canvas id="lessonAnalyticsChart" style="height: 400px; width: 100%;"></canvas>

                      <div id="lessonSummary" class="table-responsive">
                        <h5 class="mb-4">
                            Lesson Analytics
                            <button class="btn btn-sm btn-link text-decoration-none" type="button" data-toggle="collapse" data-target="#lessonTableCollapse" aria-expanded="false" aria-controls="lessonTableCollapse" onclick="toggleCollapseIcon(this)">
                                <i id="collapseIcon" class="fas fa-chevron-down text-primary"></i> <!-- Icon for collapse -->
                            </button>
                        </h5>
                        <div class="collapse" id="lessonTableCollapse">
                            @if ($lessonAnalyticsData->isEmpty())
                                <p>No lessons found.</p>
                            @else
                                <table class="table table-striped">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="small-text" scope="col">Lesson Title</th>
                                            <th class="small-text" scope="col">Views</th>
                                            <th class="small-text" scope="col">Lesson Earnings</th>
                                            <th class="small-text" scope="col">Teacher Earnings</th>
                                            <th class="small-text" scope="col">School Earnings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lessonAnalyticsData as $lesson)
                                            <tr>
                                                <td class="small-text">{{ $lesson['title'] }}</td>
                                                <td class="small-text">{{ $lesson['views'] }}</td>
                                                <td class="small-text">₦{{ number_format($lesson['lesson_earnings'], 2) }}</td>
                                                <td class="small-text">₦{{ number_format($lesson['teacher_earnings'], 2) }}</td>
                                                <td class="small-text">₦{{ number_format($lesson['school_earnings'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>



                  </div>

                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="wards">
                  @include('partials.wards')
                </div>
                </div>




                  <!-- Edit Lesson Modal -->
                  <div class="modal fade" id="editLessonModal" tabindex="-1" role="dialog" aria-labelledby="editLessonModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLessonModalLabel">Edit Lesson</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <form id="editLessonForm" enctype="multipart/form-data">
                                          <div class="alert alert-success" id="success-edit-message" style="display:none;"></div>
                                          <div class="alert alert-danger" id="error-edit-message" style="display:none;"></div>
                                  <div class="form-group">
                                      <label for="editLessonTitle">Title</label>
                                      <input type="text" class="form-control" id="editLessonTitle" name="edit_title">
                                  </div>
                                  <div class="form-group">
                                      <label for="editLessonSubject">Subject</label>
                                      <select name="edit_subject" class="form-control" id="editLessonSubject"></select>
                                  </div>
                                  <div class="form-group">
                                      <label for="editLessonClassLevel">Class Level</label>
                                      <select name="edit_class_level" class="form-control" id="editLessonClassLevel"></select>
                                  </div>
                                  <div class="form-group">
                                      <label for="editLessonDescription">Description</label>
                                      <textarea class="form-control" id="editLessonDescription" name="edit_description"></textarea>
                                  </div>
                                  <div class="form-group">
                                      <label for="lessonEditThumbnail" class="file-label">
                                          <i class="fas fa-image"></i> Thumbnail
                                      </label>
                                      <input type="file" class="form-control-file" id="lessonEditThumbnail" name="edit_thumbnail" onchange="previewEditThumbnail('lessonEditThumbnail', 'thumbnailEditPreview')" accept="image/*">
                                      <span class="file-info">Upload a thumbnail (Max size: 2MB)</span>
                                      <div id="thumbnailEditPreviewContainer">
                                          <img id="thumbnailEditPreview" src="#" alt="Thumbnail Preview" style="max-width: 100%; max-height: 100%; display: none;">
                                          <button type="button" class="btn btn-link" onclick="$('#lessonEditThumbnail').click();">
                                              <i class="fas fa-edit"></i> Change Thumbnail
                                          </button>
                                      </div>
                                  </div>
                                  <!-- Hidden field to store lesson ID -->
                                  <input type="hidden" id="editLessonId" name="lesson_id">
                              </form>




                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn bg-primary" id="saveLessonChangesBtn">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

@endsection

@section('scripts')
<script>
  var uniqueSubjectNames = {!! json_encode($uniqueSubjectNames) !!};
</script>


<script>
    function toggleCollapseIcon(button) {
        var icon = button.querySelector('i');
        if (icon.classList.contains('fa-chevron-down')) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
</script>
<script>
    $(document).ready(function () {
        // Retrieve analytics data for lessons (assuming lessonAnalyticsData is passed from the controller)
        var lessonAnalyticsData = {!! json_encode($lessonAnalyticsData) !!};

        // Extract data for the chart
        var lessonTitles = lessonAnalyticsData.map(function (lesson) {
            return lesson.title;
        });

        var lessonViews = lessonAnalyticsData.map(function (lesson) {
            return lesson.views;
        });

        var teacherEarnings = lessonAnalyticsData.map(function (lesson) {
            return parseFloat(lesson.teacher_earnings);
        });

        var schoolEarnings = lessonAnalyticsData.map(function (lesson) {
            return parseFloat(lesson.school_earnings);
        });

        // Chart.js configuration
        var ctx = document.getElementById('lessonAnalyticsChart').getContext('2d');

        var lessonAnalyticsChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: lessonTitles,
                datasets: [{
                    label: 'Views',
                    data: lessonViews,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Lesson Views'
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var lessonIndex = tooltipItem.index;
                            var lesson = lessonAnalyticsData[lessonIndex];
                            var views = lesson.views;
                            var teacherEarnings = parseFloat(lesson.teacher_earnings);
                            var schoolEarnings = parseFloat(lesson.school_earnings);

                            // Format tooltip label with views and earnings information
                            return 'Views: ' + views + ' | Teacher: ₦' + teacherEarnings.toFixed(2) + ' | School: ₦' + schoolEarnings.toFixed(2);
                        }
                    }
                },
                plugins: {
                    annotation: {
                        annotations: lessonAnalyticsData.map(function (lesson, index) {
                            return {
                                type: 'line',
                                mode: 'horizontal',
                                scaleID: 'y-axis-0',
                                value: index,
                                borderColor: 'black',
                                borderWidth: 1,
                                label: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    content: 'Teacher: ₦' + parseFloat(lesson.teacher_earnings).toFixed(2) + ' | School: ₦' + parseFloat(lesson.school_earnings).toFixed(2),
                                    enabled: true
                                }
                            };
                        })
                    }
                }
            }
        });
    });
</script>
<script>
 var canLoadMore = {
    lessons: true,
};
var currentPageLessons = {
    lessons: 0,
};
var displayedViewedLessonIds = [];
var loadingLessons = false;

function populateDisplayedIds() {
    $('.viewed_lessons').each(function() {
        var viewedLessonId = $(this).data('viewed_lesson-id');
        if (viewedLessonId && !displayedViewedLessonIds.includes(viewedLessonId)) {
            displayedViewedLessonIds.push(viewedLessonId);
        }
    });
}

$(document).ready(function() {
    populateDisplayedIds();

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var activeTabId = $(e.target).attr('href').substring(1);
        loadMoreData(activeTabId);
    });

    $(window).on('scroll', function() {
        if (isNearBottom()) {
            var activeTabId = $('.tab-pane.active').attr('id');
            loadMoreData(activeTabId);
        }
    });
});

function isNearBottom() {
    return $(window).scrollTop() + $(window).height() >= $(document).height() - 100;
}

function createLessonHtml(lesson, tabId) {
    var lessonClass = tabId === 'viewed_lessons' ? 'viewed_lessons' : 'fav_lessons';
    var title = lesson.title || '';
    var description = lesson.description || '';
    var teacherName = lesson.teacher_name || '';

    var lessonHtml = '<div class="col-md-4">';
    lessonHtml += '<div class="card lesson-card">';
    lessonHtml += '<div class="thumbnail-container position-relative">';
    lessonHtml += '<a href="/lessons/' + lesson.id + '" class="' + lessonClass + '" data-' + lessonClass + '-id="' + lesson.id + '">';
    if (lesson.thumbnail) {
        lessonHtml += '<div class="thumbnail-with-play">';
        lessonHtml += '<img src="' + lesson.thumbnail + '" alt="' + title + '" class="img-fluid lesson-thumbnail">';
        lessonHtml += '<div class="play-icon-overlay"><i class="fas fa-play"></i></div>';
        lessonHtml += '</div>';
    } else {
        lessonHtml += '<div class="no-thumbnail">';
        lessonHtml += '<div class="video-icon"><i class="fas fa-video"></i></div>';
        lessonHtml += '<div class="overlay"></div>';
        lessonHtml += '<img src="{{ asset('assets/img/default.jpeg') }}" alt="Default Thumbnail" class="img-fluid">';
        lessonHtml += '</div>';
    }
    lessonHtml += '<p class="badge bg-primary" style="position:relative; z-index:99; float:left"><small><b>' + lesson.school_connects_required + ' SC</b></small></p>';
    lessonHtml += '<p class="lesson-date small-text" style="float:right">' + lesson.created_at + '</p>';
    lessonHtml += '</a>';
    lessonHtml += '</div>';
    lessonHtml += '<div class="lesson-details">';
    lessonHtml += '<p><small><b>' + teacherName + '</b></small></p>';
    lessonHtml += '<h5><small>' + title.substring(0, 15) + '</small></h5>';
    lessonHtml += '<p><small>' + description.substring(0, 200) + '</small></p>';
    lessonHtml += '</div>';
    lessonHtml += '</div>';
    lessonHtml += '</div>';
    return lessonHtml;
}

function loadMoreData(activeTabId) {
    if (!loadingLessons && canLoadMore[activeTabId]) {
        loadingLessons = true;
        var route, displayedIds;

        if (activeTabId === 'lessons') {
            route = "{{ route('load.more.viewedlessons') }}";
            displayedIds = displayedViewedLessonIds;
        }

        $.ajax({
            url: route,
            type: "GET",
            data: {
                page: currentPageLessons[activeTabId],
                displayedLessonIds: displayedIds
            },
            beforeSend: function() {
                $('#loader').append('<div class="loader-container"><div class="loader"><i class="fas fa-spinner fa-spin"></i> Loading Lessons...</div></div>');
            },
            success: function(response) {
                if (response && response.lessons && response.lessons.length > 0) {
                    currentPageLessons[activeTabId]++;
                    $.each(response.lessons, function(index, item) {
                        if (!displayedIds.includes(item.id)) {
                            var newItem = createLessonHtml(item, activeTabId);
                            $('#' + activeTabId + ' .lessons-container .row').append(newItem);
                            displayedIds.push(item.id);
                        }
                    });
                } else {
                    canLoadMore[activeTabId] = false;
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log("AJAX Lessons Error:", error);
            },
            complete: function() {
                loadingLessons = false;
                $('.loader-container').remove();
            }
        });
    }
}

</script>

@endsection