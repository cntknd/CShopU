@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-header">
    <div class="text-center mb-4">
        <h1 class="page-title fw-bold">📝 User Feedback</h1>
        <p class="page-subtitle text-muted">Service Quality Dimension Responses</p>
    </div>
</div>
<style>
    .feedback-card{background:white;border-radius:15px;box-shadow:0 4px 15px rgba(0,0,0,.1);transition:all .3s ease;border:1px solid #e9ecef}
    .feedback-card:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,0,0,.15)}
    .rating-badge{padding:.5rem 1rem;border-radius:20px;font-weight:600;font-size:.875rem}
    .rating-excellent{background:#d4edda;color:#155724}
    .rating-good{background:#d1ecf1;color:#0c5460}
    .rating-average{background:#fff3cd;color:#856404}
    .rating-poor{background:#f8d7da;color:#721c24}
    .question-text {font-weight: 500; color: #333;}
    .feedback-header {background: #f8f9fa; padding: 1rem; border-radius: 10px; margin-bottom: 1rem;}
</style>
<div class="feedback-card p-4">
    @if($feedbacks->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-chat-dots display-4 text-muted mb-3"></i>
        <p class="h5 text-muted">No feedback available yet.</p>
        <p class="text-muted">Customer feedback will appear here when they submit reviews.</p>
    </div>
    @else
    @foreach ($feedbacks as $index => $feedback)
        <div class="mb-4 p-3 border rounded">
            <div class="feedback-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold me-3">#{{ $index + 1 }}</span>
                    <span class="me-3">{{ $feedback->user->email ?? 'N/A' }}</span>
                </div>
                <span class="small text-muted">{{ $feedback->created_at->format('M d, Y h:i A') }}</span>
            </div>

            <div class="table-responsive mt-3">
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
                            @if(isset($feedback->$key) && $feedback->$key !== 'na')
                                <tr>
                                    <td class="question-text">{{ $question }}</td>
                                    <td>
                                        <span class="rating-badge 
                                            @if($feedback->$key >= 4) rating-excellent
                                            @elseif($feedback->$key >= 3) rating-good
                                            @elseif($feedback->$key >= 2) rating-average
                                            @else rating-poor
                                            @endif">
                                            @if($feedback->$key >= 4)
                                                Excellent ({{ $feedback->$key }})
                                            @elseif($feedback->$key >= 3)
                                                Good ({{ $feedback->$key }})
                                            @elseif($feedback->$key >= 2)
                                                Average ({{ $feedback->$key }})
                                            @else
                                                Poor ({{ $feedback->$key }})
                                            @endif
                                        </span>
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
                <p class="mb-0 mt-2 p-3 bg-light rounded">{{ $feedback->suggestions }}</p>
            </div>
            @endif
        </div>
    @endforeach

        <div class="mt-4 d-flex justify-content-center">
            {{ $feedbacks->links() }}
        </div>
    @endif
</div>

@endsection
