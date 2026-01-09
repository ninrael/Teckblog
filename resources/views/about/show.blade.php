@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <h1 class="mb-4">{{ $title }}</h1>
        
        <div class="about-content">
            @if($content)
                {!! $content !!}
            @else
                <div class="alert alert-info">
                    <p class="mb-0">Страница находится в разработке. Администратор скоро добавит информацию.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

