@extends('layouts.app')

@section('content')

    {{-- 1. NAVBAR --}}
    @include('partials.navbar')

    {{-- 2. HERO SECTION --}}
    @include('partials.hero-section')

    {{-- 3. FEATURE CARDS --}}
    @include('partials.feature-cards', ['ai_tools' => $ai_tools])

    {{-- 4. HOW IT WORKS --}}
    @include('partials.how-it-works')
    <img src="{{ asset('assets/images/footer-white-mid.png') }}" alt="" class="pb-">
@endsection

{{--@section('scripts')
     5. JAVASCRIPT 
    @include('partials.scripts')
@endsection--}}