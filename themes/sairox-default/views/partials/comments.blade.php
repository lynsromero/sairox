@php
    $comments = \App\Models\Comment::where('commentable_type', get_class($post ?? $page))
        ->where('commentable_id', ($post ?? $page)->id)
        ->where('status', 'approved')
        ->whereNull('parent_id')
        ->with('replies')
        ->orderBy('created_at', 'asc')
        ->get();
@endphp

<section class="mt-12 pt-8 border-t">
    <h2 class="text-2xl font-bold text-gray-900 mb-8">Comments</h2>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($comments->isNotEmpty())
    <div class="space-y-6">
        @foreach($comments as $comment)
        <div class="bg-gray-50 rounded-lg p-4" id="comment-{{ $comment->id }}">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <strong class="text-gray-700">{{ $comment->author_name }}</strong>
                <span>·</span>
                <span>{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-gray-700">{{ $comment->content }}</p>
            <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')" class="text-sm text-amber-600 hover:text-amber-700 mt-2">
                Reply
            </button>

            <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 ml-6">
                <form action="{{ route('comment.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="commentable_type" value="{{ get_class($post ?? $page) === 'App\Models\Page' ? 'page' : 'post' }}">
                    <input type="hidden" name="commentable_id" value="{{ ($post ?? $page)->id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                    @guest
                    <div>
                        <input type="text" name="author_name" placeholder="Your name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                    </div>
                    <div>
                        <input type="email" name="author_email" placeholder="Your email" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                    </div>
                    @endguest

                    <input type="text" name="website" class="hidden" autocomplete="off" tabindex="-1">
                    <div>
                        <textarea name="content" rows="3" placeholder="Write a reply..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" required></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition-colors">Submit Reply</button>
                </form>
            </div>

            @if($comment->replies->isNotEmpty())
            <div class="ml-6 mt-4 space-y-4">
                @foreach($comment->replies as $reply)
                <div class="bg-white rounded-lg p-3 border border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                        <strong class="text-gray-700">{{ $reply->author_name }}</strong>
                        <span>·</span>
                        <span>{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <p class="text-gray-500 mb-6">No comments yet. Be the first to comment!</p>
    @endif

    <div class="mt-8 pt-6 border-t">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Leave a Comment</h3>
        <form action="{{ route('comment.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="commentable_type" value="{{ get_class($post ?? $page) === 'App\Models\Page' ? 'page' : 'post' }}">
            <input type="hidden" name="commentable_id" value="{{ ($post ?? $page)->id }}">

            @guest
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <input type="text" name="author_name" placeholder="Your name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                </div>
                <div>
                    <input type="email" name="author_email" placeholder="Your email" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                </div>
            </div>
            @endguest

            <input type="text" name="website" class="hidden" autocomplete="off" tabindex="-1">
            <div>
                <textarea name="content" rows="4" placeholder="Write your comment..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" required></textarea>
            </div>
            <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">Post Comment</button>
        </form>
    </div>
</section>
