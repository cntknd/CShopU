<x-guest-layout>

    <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Register to CShopU</h1>

    <form method="POST" action="{{ route('register') }}" class="bg-white p-6 rounded-xl shadow-md max-w-2xl mx-auto">
        @csrf

        <!-- Title -->
        <h2 class="text-lg font-medium text-gray-900 mb-4">Register to Capstone Project</h2>

        <!-- Student ID -->
        <div class="mt-4">
            <x-input-label for="student_id" :value="__('Student ID')" />
            <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')" required autofocus />
            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
        </div>

        <!-- Name Fields (Side-by-side) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <!-- Last Name -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <!-- First Name -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Middle Initial -->
            <div>
                <x-input-label for="middle_initial" :value="__('Middle Initial')" />
                <x-text-input id="middle_initial" class="block mt-1 w-full" type="text" name="middle_initial" maxlength="1" :value="old('middle_initial')" />
                <x-input-error :messages="$errors->get('middle_initial')" class="mt-2" />
            </div>
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <!-- Centered “Already have an account?” link -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-red-700 font-medium">
                {{ __('Already have an account? Log in here.') }}
            </a>
        </div>
    </form>

</x-guest-layout>
