

<x-app-layout>
    <h1>gwkki</h1>
@foreach ($habits as $habit)
    <h1 > {{$habit->name}}</h1>
    <h1 > {{$habit->type}}</h1>

@endforeach

</x-app-layout>
