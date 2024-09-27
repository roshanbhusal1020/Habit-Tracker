
<form action="{{route('journal.update', $journal->id )}}" method="POST" >
    @csrf
    @method('PUT')

    <div>
        <label for="title"> Title</label>
        <input type="text" name="title" value="{{old('title', $journal->title)}}" required>
    </div>

    <div>
        <label for="content">Content</label>
        <textarea name="content" required> {{old('content', $journal->content)}}</textarea>
    </div>
    <button type="submit">Update Journal</button>
</form>