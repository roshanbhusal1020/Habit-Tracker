<script>


</script>

<h1>helllllllllllll </h1>

<button>Add a habit</button>

<table>
    <ul>
        @for ($i=0; $i <$daysInMonth; $i++)
            <li> {{$i}}</li>
            <input type="checkbox">
        @endfor
        <td> {{$daysInMonth}}   </td>
</ul>
</table>

