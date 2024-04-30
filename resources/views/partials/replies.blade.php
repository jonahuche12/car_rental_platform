@foreach ($replies as $reply)
    <div class="reply">
        <div class="reply-header">
            <strong>{{ $reply->user->profile->full_name }}</strong>
            <small class="text-muted small-text"> <b>{{ $reply->created_at->diffForHumans() }}</b> </small>
        </div>
        <div class="reply-body">
            {{ $reply->content }}
        </div>
        <div class="reply-footer">
            @if ($reply->replies->isNotEmpty())
                <button class="btn btn-sm btn-clear text-purple toggle-nested-replies" data-reply-id="{{ $reply->id }}">
                    <i class="fas fa-comment-alt"></i> {{ $reply->replies->count() }} Replies
                    <i class="fas fa-caret-down ml-1"></i> <!-- Default icon when replies are collapsed -->
                </button>
            @endif
            <button class="btn btn-sm btn-clear text-primary toggle-reply-form" data-comment-id="{{ $reply->comment_id }}" data-parent-reply-id="{{ $reply->id }}">
                <i class="fas fa-reply-all"></i> Reply
            </button>
           
        </div>

        <!-- Reply Form for this reply -->
        <form action="{{ route('comment.reply', ['comment' => $reply->comment_id]) }}" method="POST" class="reply-form mt-2" id="form-reply-{{$reply->id}}" style="display:none;">
            @csrf
            <input type="hidden" name="parent_reply_id" value="{{ $reply->id }}">
            <div class="input-group">
                <input type="text" name="reply_content" class="form-control" placeholder="Type a reply">
                <div class="input-group-append">
                    <button type="submit" class="btn bg-purple"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </form>

        <!-- Nested replies container -->
        <div class="nested-replies ml-4" id="nested-replies-{{$reply->id}}" style="display:none;">
            @include('partials.replies', ['replies' => $reply->replies])
        </div>
    </div>
@endforeach
