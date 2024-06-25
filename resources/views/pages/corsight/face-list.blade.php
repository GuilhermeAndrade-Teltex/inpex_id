@vite(['resources/js/pages/corsight/face-list.module.js'])

@extends('layouts.app')

@section('main')
<section role="main" class="content-body">
    @include('components.breadcrumbs')

    <div class="row">
        <div id="appearance-images" class="col-md-12">
            <ul id="faces-list" style="list-style-type: none; padding: 0; display: flex; flex-wrap: wrap;">
                @foreach($faces as $face)
                    <li class="listItem" style="opacity: 1; transform: none; margin: 10px;">
                        <div>
                            <img src="{{ asset('storage/' . $face->face_crop_img) }}" alt="Face Image"
                                style="width: 160px; height: 240px; object-fit: cover;">
                            <p>{{ $face->poi_display_name }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
@endsection