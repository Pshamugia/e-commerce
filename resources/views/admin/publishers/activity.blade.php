@extends('admin.layouts.app')

@section('title', 'ბუკინისტები')

@section('content')
<div class="container" style="position: relative; margin-top:55px;">
    <h1>ბუკინისტები</h1>
    <table class="table">
        <thead>
            <tr>
                <th>სახელი</th>
                <th>ელფოსტა</th>
                <th>ტელეფონი</th>
                <th>მისამართი</th>
                <th>ატვირთული მასალა</th>
            </tr>
        </thead>
        <tbody>
            @foreach($publishers as $publisher)
                <tr>
                    <td>{{ $publisher->name }}</td>
                    <td>{{ $publisher->email }}</td>
                    <td>{{ $publisher->phone ?? 'N/A' }}</td>
                    <td> {{ $publisher->address ?? 'N/A' }} </td>
                    <td>
                        @if($publisher->books->isNotEmpty())
                            <ul>
                                @foreach($publisher->books as $book)
                                    <li><a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}">
                                        {{ $book->title }}
                                    </a></li>
                                @endforeach
                            </ul>
                        @else
                            <span>No books uploaded</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    
    
</div>
@endsection