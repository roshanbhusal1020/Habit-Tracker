@foreach($journal as $jor)
<h1>Title:{{$jor->title}} </h1>
<h3>Content:{{$jor->content}} </h3>
<a href= "{{ route('journal.edit', $jor->id) }}">Edit </a>
<br>
@endforeach
