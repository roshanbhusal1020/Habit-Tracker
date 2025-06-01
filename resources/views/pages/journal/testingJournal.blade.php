<h1> TESTING JOURNAL BLADE   </h1>

<form action="{{route('journal.store')}}" method='POST'>
@csrf 
    <div>
        <label>Title:</label>
        <input type="text" name="title" placeholder="Enter yout journal title"> </input>
    </div>
    <div>
        <label>Content:</label>
        <textarea name="content" placeholder="Write your journal content here"></textarea>

    </div>
    <div>
        <button type="submit">Save Journal</button>
    </div>
    

</form>
