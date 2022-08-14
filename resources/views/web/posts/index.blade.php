<!DOCTYPE HTML>
<html>
<head>
    <title>掲示板</title>
</head>
<body>

<h1>掲示板</h1>

@isset($posts)
    @foreach ($posts as $post)
        <h2>Title: <a href="{{ route('web.post.comment.create', [$post->id]) }}">{{ $post->title }}</a></h2>
        {{ $post->body }}<br>
        @foreach($post->comments as $comment)
            {{ $comment->title }}
        @endforeach
        <br><hr>
    @endforeach
@endisset

<h2>フォーム</h2>
<form action="/create" method="POST">
    名前:<br>
    <input name="name">
    <br>
    コメント:<br>
    <textarea name="comment" rows="4" cols="40"></textarea>
    <br>
    {{ csrf_field() }}
    <button class="btn btn-success"> 送信 </button>
</form>

</body>
</html>
