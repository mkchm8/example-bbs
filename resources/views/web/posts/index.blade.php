<!DOCTYPE HTML>
<html>
<head>
    <title>掲示板</title>
</head>
<body>

<h1>掲示板</h1>

@isset($posts)
    @foreach ($posts as $post)
        <h2>Title: @if($post->hasFullComment()){{ $post->title }}@else<a href="{{ route('web.post.comment.create', [$post->id]) }}">{{ $post->title }}</a>@endif&nbsp;投稿日時:&nbsp;{{ $post->createdAt->format('Y/m/d H:i:s') }}</h2>
        {{ $post->body }}<br>
        @foreach($post->comments as $comment)
            {{ $comment->title }}
        @endforeach
        <br><hr>
    @endforeach
@endisset
</body>
</html>
