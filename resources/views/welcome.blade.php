<!-- resources/views/welcome.blade.php -->

@extends('layouts.layout')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1>Welcome to Yahtzee!</h1>
            <p>Ready to play the game?</p>
            <button id="startGame" name="startGame" class="btn btn-primary">Start Game</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        var gameInterfaceUrl = "{{ route('game.interface') }}";
        document.getElementById('startGame').addEventListener('click', function() {
            console.log("Clicked!");
            let displayName = prompt("Enter your display name:");
            if (displayName) {
                fetch('/display-name', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({displayName: displayName})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = gameInterfaceUrl;
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again later.');
                });
            } else {
                alert('Please enter a display name to continue.');
            }
        });
    });
</script>
@endpush
