<x-mail::message>
# A comment was sposted on your idea

{{ $comment->user->name }} commented on your idea:

**{{ $comment->idea->title }}**

Comment: {{ $comment->body }}

<x-mail::button :url="route('idea.show',$comment->idea)">
Go to idea
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
