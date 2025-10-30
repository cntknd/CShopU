@extends('layouts.Users.app')

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <div class="text-center mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="CSU Logo" class="h-20 mx-auto mb-4">
                    <h1 class="text-xl font-bold text-gray-800">CAGAYAN STATE UNIVERSITY</h1>
                    <h2 class="text-lg font-semibold text-gray-700">APARRI CAMPUS</h2>
                    <h3 class="text-md text-gray-600">Business and Resource Mobilization Office</h3>
                    <h4 class="text-md font-semibold text-gray-800 mt-4">HELP US SERVE YOU BETTER!</h4>
                </div>

                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-gray-700 mb-2">
                        This Client Satisfaction Measurement (CSM) tracks the customer experience of government clients. Your feedback on your recently concluded transaction with this office provide a better service. Personal information shared will be kept confidential and you always have the option to not answer this form.
                    </p>
                    <p class="text-sm text-gray-700 font-semibold">
                        INSTRUCTIONS: Check mark (✓) your answer to the Citizen's Charter (CC) questions. The Citizen's Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times among others.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-md" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('feedback.store') }}" method="post" class="space-y-6">
                    @csrf

                    <!-- Client Information -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Client type:</label>
                                <div class="mt-1 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="client_type" value="citizen" class="form-radio" required>
                                        <span class="ml-2">Citizen</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="client_type" value="business" class="form-radio">
                                        <span class="ml-2">Business</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="client_type" value="government" class="form-radio">
                                        <span class="ml-2">Government (Employee or another agency)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sex:</label>
                                    <div class="mt-1 space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="sex" value="male" class="form-radio" required>
                                            <span class="ml-2">Male</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="sex" value="female" class="form-radio">
                                            <span class="ml-2">Female</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Age:</label>
                                    <input type="number" name="age" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Region of residence:</label>
                                    <input type="text" name="region" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Service Availed:</label>
                                    <input type="text" name="service" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>
                        </div>

                        <!-- CC Questions -->
                        <div class="p-4 bg-gray-50 rounded-lg space-y-4">
                            <h3 class="font-medium text-gray-900">Citizen's Charter Questions</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CC1. Which of the following best describes your awareness of CC?</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="cc1" value="1" class="form-radio" required>
                                        <span class="ml-2">I know what a CC is and I saw this office's CC.</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc1" value="2" class="form-radio">
                                        <span class="ml-2">I know what a CC is and I did not see this office's CC.</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc1" value="3" class="form-radio">
                                        <span class="ml-2">I learned of the CC only when I saw this office's CC.</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc1" value="4" class="form-radio">
                                        <span class="ml-2">I do not know what a CC is and I did not see this office's CC.</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CC2. If aware of CC (answered 1-3 in CC1), would you say that the CC of this office was...?</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="cc2" value="1" class="form-radio">
                                        <span class="ml-2">Easy to see</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc2" value="2" class="form-radio">
                                        <span class="ml-2">Somewhat easy to see</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc2" value="3" class="form-radio">
                                        <span class="ml-2">Difficult to see</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc2" value="4" class="form-radio">
                                        <span class="ml-2">Not visible at all</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc2" value="na" class="form-radio">
                                        <span class="ml-2">N/A</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CC3. If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="cc3" value="1" class="form-radio">
                                        <span class="ml-2">Helped very much</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc3" value="2" class="form-radio">
                                        <span class="ml-2">Somewhat helped</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc3" value="3" class="form-radio">
                                        <span class="ml-2">Did not help</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cc3" value="na" class="form-radio">
                                        <span class="ml-2">N/A</span>
                                    </label>
                                </div>
                            </div>
                        </div>

            

                    <!-- Service Quality Questions -->
                    <div class="bg-white shadow overflow-hidden rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Service Quality Assessment</h3>
                            
                            @php
                            $questions = [
                                "SQD1" => "I am satisfied with the service that I availed.",
                                "SQD2" => "I spent a reasonable amount of time for my transaction.",
                                "SQD3" => "The office followed the transaction\'s requirements and steps based on the information provided.",
                                "SQD4" => "The steps (including payment) I needed to do for my transaction were easy and simple.",
                                "SQD5" => "I easily found information about my transaction from the office\'s website.",
                                "SQD6" => "I paid a reasonable amount of fees for my transaction. (if service was free, mark the \"N/A\" column)",
                                "SQD7" => "I am confident my online transaction was secure.",
                                "SQD8" => "The office\'s online support was available, and (if asked questions) online support was quick to respond.",
                                "SQD9" => "I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me."
                            ];
                            @endphp

                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Strongly Disagree</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disagree</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Neither</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Agree</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Strongly Agree</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">N/A</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($questions as $key => $question)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $question }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" name="{{ $key }}" value="1" class="form-radio" {{ old($key) == '1' ? 'checked' : '' }} required>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" name="{{ $key }}" value="2" class="form-radio" {{ old($key) == '2' ? 'checked' : '' }} required>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" name="{{ $key }}" value="3" class="form-radio" {{ old($key) == '3' ? 'checked' : '' }} required>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" name="{{ $key }}" value="4" class="form-radio" {{ old($key) == '4' ? 'checked' : '' }} required>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" name="{{ $key }}" value="5" class="form-radio" {{ old($key) == '5' ? 'checked' : '' }} required>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio" name="{{ $key }}" value="na" class="form-radio" {{ old($key) == 'na' ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Suggestions Field -->
                    <div>
                        <label for="suggestions" class="block text-sm font-medium text-gray-700">
                            Suggestions on how we can further improve our services (optional):
                        </label>
                        <textarea 
                            name="suggestions" 
                            id="suggestions" 
                            rows="3" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('suggestions') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email (optional):
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            value="{{ old('email') }}"
                        >
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection