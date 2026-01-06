{{-- resources/views/parent/reviews/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Write a Review') }} — {{ $school->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc ms-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-6 rounded shadow">
                {{-- IMPORTANT: use the same param your route expects (slug vs model). --}}
                <form method="POST" action="{{ route('parent.reviews.store', $school->slug) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('Student Number') }}
                        </label>
                        <input type="text"
                               name="student_number"
                               value="{{ old('student_number') }}"
                               class="mt-1 w-full border-gray-300 rounded p-2 focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>

                    @php
                        $fields = [
                            'hygiene' => __('Hygiene'),
                            'management' => __('Management'),
                            'education_quality' => __('Education Quality'),
                            'parent_communication' => __('Parent Communication'),
                        ];
                    @endphp

                    @foreach($fields as $name => $label)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ $label }} (0–5)
                            </label>
                            <input type="number"
                                   name="{{ $name }}"
                                   min="0" max="5"
                                   value="{{ old($name, 0) }}"
                                   class="mt-1 w-full border-gray-300 rounded p-2 focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>
                    @endforeach

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('Comment (optional)') }}
                        </label>
                        <textarea name="comment"
                                  rows="4"
                                  class="mt-1 w-full border-gray-300 rounded p-2 focus:border-indigo-500 focus:ring-indigo-500">{{ old('comment') }}</textarea>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">
                        {{ __('Submit Review') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
