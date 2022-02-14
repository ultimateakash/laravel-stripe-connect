@extends('layouts.main')
@section('title', 'Stripe Connect')
@section('content')
<div class='row'>
    <div class='col d-flex align-items-center justify-content-center'>
        <div class='card mt-5' style='width: 18rem;'>
            <a href='{{ $connectUri }}'>
                <img src="{{ asset('images/connect-purple.png') }}" class='img-thumbnail' alt='Connect With Stripe' />
            </a>
        </div>
    </div>
</div>
@endsection