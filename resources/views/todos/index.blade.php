@extends('layouts.app')

@section('styles')
<style>
    #outer {
        width: auto;
        text-align: center;
    }
    .inner {
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (Session::has('alert-success'))   
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('alert-success') }}
                        </div>   
                    @endif

                    @if (Session::has('alert-info'))   
                        <div class="alert alert-info" role="alert">
                            {{ Session::get('alert-info') }}
                        </div>   
                    @endif

                    @if (Session::has('error'))   
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('error') }}
                        </div>   
                    @endif

                    <a class="btn btn-sm btn-dark" href="{{ route('todos.create') }}">Create To-do List</a> 
                    <a class="btn btn-sm btn-warning" href="{{ route('todos.restore.view') }}" style="float: right;">Restore Deleted To-do Lists</a>
                    <hr>

                    <!-- Dropdown for Voice Selection -->
                    <div class="mb-3">
                        <label for="voiceSelection" class="form-label">Select Voice:</label>
                        <select id="voiceSelection" class="form-select">
                            <!-- Voice options will be dynamically populated -->
                        </select>
                    </div>

                    @if (count($todos) > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Completed</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
                                    <th scope="col">Action</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todos as $todo)
                                    <tr>
                                        <td>{{ $todo->title }}</td>
                                        <td>{{ $todo->description }}</td>
                                        <td>
                                            @if ($todo->is_completed == 1)
                                                <span class="badge bg-success">Completed</span>
                                            @else 
                                                <span class="badge bg-danger">Incomplete</span>
                                            @endif
                                        </td>
                                        <td>{{ $todo->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $todo->updated_at->format('Y-m-d H:i:s') }}</td>
                                        <td id="outer">
                                            <a class="inner btn btn-sm btn-success" href="{{ route('todos.show', $todo->id) }}">View</a>
                                            <a class="inner btn btn-sm btn-info" href="{{ route('todos.edit', $todo->id) }}">Edit</a>

                                            <form method="post" action="{{ route('todos.destroy') }}" class="inner">
                                                @csrf
                                                @method("DELETE")
                                                <input type="hidden" name="todo_id" value="{{ $todo->id }}">
                                                <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                            </form>

                                            <!-- Add Read Aloud Button -->
                                            <button class="inner btn btn-sm btn-primary" onclick="speakText('{{ $todo->title }}: {{ $todo->description }}')">
                                                🔊 Read Aloud
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    @else 
                        <h4>No To-do lists are created yet</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for TTS functionality -->
<script>
    const voiceSelect = document.getElementById('voiceSelection');
    let voices = [];

    // Populate the dropdown with available voices
    function populateVoiceList() {
        voices = window.speechSynthesis.getVoices();

        // Clear existing options
        voiceSelect.innerHTML = '';

        // Add voice options to the dropdown
        voices.forEach((voice, index) => {
            const option = document.createElement('option');
            option.textContent = `${voice.name} (${voice.lang})`;
            option.value = index;
            voiceSelect.appendChild(option);
        });
    }

    // Call populateVoiceList when voices change (some browsers need this)
    if (typeof speechSynthesis !== 'undefined' && speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = populateVoiceList;
    }

    // Function to speak the text with the selected voice
    function speakText(text) {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);

            // Get selected voice
            const selectedVoiceIndex = voiceSelect.value;
            if (voices[selectedVoiceIndex]) {
                utterance.voice = voices[selectedVoiceIndex];
            }

            // Set additional options (optional)
            utterance.lang = voices[selectedVoiceIndex]?.lang || 'en-US';
            utterance.rate = 1;
            utterance.pitch = 1;

            // Speak the text
            window.speechSynthesis.speak(utterance);
        } else {
            alert('Sorry, your browser does not support text-to-speech.');
        }
    }

    // Initialize voice list on page load
    document.addEventListener('DOMContentLoaded', populateVoiceList);
</script>
@endsection
