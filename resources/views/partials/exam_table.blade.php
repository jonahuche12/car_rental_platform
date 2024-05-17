@php
    use App\Models\AcademicSession;
    use App\Models\Term;
@endphp


<table class="table">
    <thead>
        <tr>
            <th class="small-text">Name</th>
            <th class="small-text">Score</th>
            <th class="small-text">%</th>
            <th class="small-text">Grade</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalByTerm = [];
            $countByTerm = [];
        @endphp

        @foreach($grades->where('course_id', $course_id)->whereNotNull('exam_id') as $grade)
            @php
                $exam = $grade->exam;

                // Ensure exam has academic session and term
                if ($exam->academicSession == null || $exam->term == null) {
                    $latestAcademicSession = AcademicSession::latest()->first();
                    $latestTerm = Term::latest()->first();

                    if ($exam->academicSession == null) {
                        $exam->academicSession()->associate($latestAcademicSession);
                    }

                    if ($exam->term == null) {
                        $exam->term()->associate($latestTerm);
                    }

                    $exam->save();
                }

                // Calculate totals and counts for each term
                if ($grade->exam->academicSession->id == $school_session->id && $grade->exam->term->name == $school_term->name) {
                    $termId = $grade->exam->term->id;
                    $totalByTerm[$termId] = isset($totalByTerm[$termId]) ? $totalByTerm[$termId] + $grade->score : $grade->score;
                    $countByTerm[$termId] = isset($countByTerm[$termId]) ? $countByTerm[$termId] + 1 : 1;
                }

                // Calculate percentage and grade for each grade
                $percentage = 0;
                if ($grade->exam) {
                    $complete_score = $grade->exam->complete_score;
                    $percentage = is_numeric($complete_score) ? $grade->calculatePercentage($grade->score, $complete_score) : 0;
                }
            @endphp

            @if($grade->exam->academicSession->id == $school_session->id && $grade->exam->term->name == $school_term->name)
                <tr>
                    <td class="small-text">
                        @if($grade->exam->archived)
                            <i class="fa fa-archive text-success"></i>
                        @endif
                        {{$grade->exam->name}}
                    </td>
                    <td class="small-text">{{$grade->score}} <span style="font-size:9px">out of</span> {{$grade->complete_score}} </td>
                    <td class="small-text">{{$percentage}}%</td>
                    <td class="small-text">{{$grade->calculateGrade($percentage)}}</td>
                </tr>
            @endif
        @endforeach

        @foreach ($totalByTerm as $termId => $total)
            <tr>
                <td></td>
                <td></td>
                <td class="small-text"><strong>Total </strong></td>
                <td class="small-text"><b>{{$total}}</b></td>
            </tr>
            <tr>
                <td></td>
                <td class="small-text"></td>
                <td class="small-text"><strong>Average</strong></td>
                <td class="small-text"><b>
                    @php
                        $average = isset($countByTerm[$termId]) && $countByTerm[$termId] > 0 ? $total / $countByTerm[$termId] : 0;
                        $avgpercentage = is_numeric($total) ? $grade->calculatePercentage($average, $total) : 0;
                    @endphp
                    {{ $average }}
                </b></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="small-text"><strong>Grade Average</strong></td>
                <td class="small-text"><b>{{$grade->calculateGrade($avgpercentage)}}</b></td>
            </tr>
        @endforeach
    </tbody>
</table>
