@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item active">{{ $page->title }}</li>
            </ol>
        </nav>

        <h1 class="mb-4">{{ $page->title }}</h1>
        
        <div class="page-content">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection

