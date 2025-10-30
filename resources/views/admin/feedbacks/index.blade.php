@extends('admin.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="CSU Logo" class="h-20 mx-auto mb-4">
        <h1 class="text-xl font-bold text-gray-800">CAGAYAN STATE UNIVERSITY</h1>
        <h2 class="text-lg font-semibold text-gray-700">APARRI CAMPUS</h2>
        <h3 class="text-md text-gray-600">Business and Resource Mobilization Office</h3>
        <p class="text-sm text-gray-500 mt-2">Feedback Reports</p>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($feedbacks as $index => $feedback)
                <div class="feedback-entry mb-4 p-4 border rounded">
                    <div class="row mb-3">
                        <div class="col">
                            <strong class="me-2">Client Type:</strong> {{ ucfirst($feedback->client_type) }}
                        </div>
                        <div class="col">
                            <strong class="me-2">Email:</strong> {{ $feedback->email }}
                        </div>
                        <div class="col">
                            <strong class="me-2">Date:</strong> {{ $feedback->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="70%">Service Quality Dimension Statement</th>
                                    <th width="30%">Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $questions = [
                                        'SQD1' => 'I am satisfied with the service rendered by CSU.',
                                        'SQD2' => 'I spent reasonable amount of time on my transaction.',
                                        'SQD3' => 'The office followed the transaction\'s requirements and steps based on information provided.',
                                        'SQD4' => 'The transaction was simple and convenient.',
                                        'SQD5' => 'I easily found information about the service.',
                                        'SQD6' => 'I paid a reasonable amount for the service.',
                                        'SQD7' => 'I feel safe and secure when doing transactions online.',
                                        'SQD8' => 'I got assistance from staff when doing online transactions.'
                                    ];
                                @endphp

                                @foreach($questions as $key => $question)
                                    @if($feedback->$key !== 'na')
                                        <tr>
                                            <td>{{ $question }}</td>
                                            <td>
                                                @if($feedback->$key < 3)
                                                    <span class="badge bg-danger">Poor ({{ $feedback->$key }})</span>
                                                @elseif($feedback->$key < 4)
                                                    <span class="badge bg-warning">Average ({{ $feedback->$key }})</span>
                                                @else
                                                    <span class="badge bg-success">Good ({{ $feedback->$key }})</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($feedback->suggestions)
                        <div class="mt-3">
                            <strong>Comments/Suggestions:</strong>
                            <p class="mb-0">{{ $feedback->suggestions }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-4">
                    No feedbacks found
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection