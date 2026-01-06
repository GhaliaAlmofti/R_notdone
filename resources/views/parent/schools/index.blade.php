{{-- resources/views/parent/schools/index.blade.php --}}
<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">{{ __('Search Schools') }}</h1>

        <form method="GET"
              action="{{ route('parent.schools.index') }}"
              class="bg-white p-4 rounded shadow space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    {{ __('School name') }}
                </label>
                <input name="q"
                       value="{{ $q ?? '' }}"
                       class="w-full border-gray-300 rounded p-2 focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="{{ __('Type a school name...') }}" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Area') }}</label>
                    <select name="area" class="w-full border-gray-300 rounded p-2 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('All') }}</option>
                        @foreach(($areas ?? []) as $a)
                            <option value="{{ $a }}" @selected(($area ?? '') === $a)>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Category') }}</label>
                    <select name="category" class="w-full border-gray-300 rounded p-2 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('All') }}</option>
                        @foreach(($categories ?? []) as $c)
                            <option value="{{ $c }}" @selected(($category ?? '') === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('Level') }}</label>
                    <select name="level" class="w-full border-gray-300 rounded p-2 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('All') }}</option>
                        @foreach(($levels ?? []) as $l)
                            <option value="{{ $l }}" @selected(($level ?? '') === $l)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition">
                {{ __('Search') }}
            </button>
        </form>

        <div class="mt-8">
            @php
                $qVal = $q ?? '';
                $areaVal = $area ?? '';
                $categoryVal = $category ?? '';
                $levelVal = $level ?? '';
            @endphp

            @if($qVal === '' && $areaVal === '' && $categoryVal === '' && $levelVal === '')
                <p class="text-gray-600">{{ __('Start searching to see schools.') }}</p>
            @else
                <h2 class="text-lg font-semibold mb-3">{{ __('Results') }}</h2>

                @if(($schools ?? collect())->count() === 0)
                    <p class="text-gray-600">{{ __('No schools found.') }}</p>
                @else
                    <div class="space-y-3">
                        @foreach($schools as $school)
                            <a href="{{ route('parent.schools.show', $school->slug) }}"
                               class="block bg-white p-4 rounded shadow hover:shadow-md transition">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="font-bold">{{ $school->name }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ __('Avg rating:') }}
                                            {{ number_format((float)($school->avg_rating ?? 0), 1) }}
                                            •
                                            {{ __('Reviews:') }}
                                            {{ (int)($school->approved_reviews_count ?? 0) }}
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-500 text-right">
                                        {{ $school->area }} • {{ $school->category }} • {{ $school->level }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
