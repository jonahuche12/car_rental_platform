@if ($results->isNotEmpty())
    <h5>Events</h5>
    <ul class="users-list event-list clearfix">
        @foreach ($results as $event)
            <li>
                <a href="#" class="event-link" data-event-id="{{ $event->id }}" data-event-title="{{ $event->title }}">
                    <img src="{{ $event->thumbnail ? asset($event->thumbnail) : asset('assets/img/default.jpeg') }}" alt="{{ $event->title }}">
                </a>
                <a class="users-list-name" href="#" class="event-link" data-event-id="{{ $event->id }}" data-event-title="{{ $event->title }}">{{ $event->title }}</a>
                <span class="users-list-date">{{ $event->event_date }}</span>
            </li>
        @endforeach
    </ul>
@else
    <p>No events found for the search term <b>{{ $term }}</b>.</p>
@endif
