@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resume as $item)
            <tr>
                <td>
                    <a href="{{ route('resumes.show',$item->id) }}">{{ $item->title }} </a>

                </td>
                <td>
                    <div class="d-flex justify-content-end">
                        <div>
                            <a href="{{ route('resumes.edit',$item->id) }}" class="btn btn-primary">Edit</a>
                        </div>
                        <div class="ml-2">
                            <a href="{{ route('resumes.destroy',$item->id) }}" class="btn btn-danger">Delete</a>
                        </div>
                    </div>

                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>
@endsection
