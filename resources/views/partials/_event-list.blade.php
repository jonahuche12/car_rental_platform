<!-- resources/views/partials/_event-list.blade.php -->

<div class="timeline timeline-inverse">
    @foreach($top_events as $event)
        <div class="time-label">
            <span class="badge bg-blue">
                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
            </span>
        </div>
        <div>
            <div class="timeline-item" data-event-id="{{ $event->id }}">
                <span class="time"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
                <h4 class="timeline-header"><a href="#">{{ $event->title }}</a></h4>
                <div class="timeline-body">
                    <b class="timeline-header"><a href="#">{{$event->school->name}}</a></b>
                    @if($event->banner_picture || $school->logo)
                        <div>
                            <a href="#">
                                <img src="{{ $event->banner_picture ? asset('storage/' . $event->banner_picture) : ($school->logo ? asset('storage/' . $school->logo) : asset('path_to_camera_icon')) }}" alt="Event Banner" class="img-fluid mt-2" style="max-width: 200px;">
                            </a>
                        </div>
                    @endif
                    {{ $event->description }}
                </div>
                <div class="timeline-footer">
                    <div>
                        <i class="fa fa-heart p-2"></i>
                        <i class="fa fa-comments p-2"></i>
                    </div>
                    <p class="badge bg-primary"><a href="#">{{$event->academicSession->name}}</a></p>
                </div>
            </div>
        </div>
    @endforeach
</div>
