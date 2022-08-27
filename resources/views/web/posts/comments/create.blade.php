<!DOCTYPE HTML>
<html>
<head>
    <title>掲示板</title>
</head>
<body>

<h1>掲示板</h1>

@if (session('flash_message_ok'))
    <div class="flash_message">
        {{ session('flash_message_ok') }}
    </div>
@endif
@if (session('flash_message_ng'))
    <div class="flash_message">
        {{ session('flash_message_ng') }}
    </div>
@endif

@if($post->hasFullComment())
    <div>コメント数が上限に達しているため、これ以上コメントできません</div>
@endif
@foreach($errors->all() as $error)
  {{ $error }}
@endforeach
<h2>Title: {{ $post->title }}&nbsp;投稿日時:&nbsp;{{ $post->createdAt->format('Y/m/d H:i:s') }}</h2>
{{ $post->body }}<br>
@foreach($post->comments as $comment)
    {{ $comment->title }}
@endforeach
<br><hr>

<h2>フォーム</h2>
<form action="{{ route('web.post.comment.store', [$post->id]) }}" method="POST">
    タイトル:<br>
    <input name="title">
    <br>
    コメント:<br>
    <textarea name="body" rows="4" cols="40"></textarea>
    <br>
    <button class="btn btn-success"@if($post->hasFullComment()) disabled @endif> 送信 </button>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

</body>
</html>
