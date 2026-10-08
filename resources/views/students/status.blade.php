@extends('layouts.app')
@section('content')
<p>Please sign in to verify your email.</p>
<a href="{{ route('login') }}">Sign in</a>
@endsection
