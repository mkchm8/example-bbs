<!DOCTYPE HTML>
<html>
<head>
    <title>掲示板</title>
</head>
<body>

<h1>掲示板</h1>

<h2>Title: {{ $post->title }}&nbsp;投稿日時:&nbsp;{{ $post->createdAt->format('Y/m/d H:i:s') }}</h2>
{{ $post->body }}<br>
@foreach($post->comments as $comment)
    {{ $comment->title }}
@endforeach
<br><hr>

<h2>フォーム</h2>
<form action="" method="POST">
    タイトル:<br>
    <input name="title">
    <br>
    コメント:<br>
    <textarea name="body" rows="4" cols="40"></textarea>
    <br>
    <button class="btn btn-success"> 送信 </button>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

</body>
</html>
