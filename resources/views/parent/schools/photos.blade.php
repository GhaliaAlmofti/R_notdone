<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">{{ __('School Photos') }}</h1>

            <a href="{{ route('parent.schools.show', $school->slug) }}"
               class="text-sm underline text-gray-600 hover:text-gray-900">
                {{ __('Back to school') }}
            </a>
        </div>

        <p class="text-gray-600 mb-6">
            {{ __('School') }}: <b>{{ $school->name }}</b>
        </p>

        @if($photos->count() === 0)
            <p class="text-gray-600">{{ __('No photos uploaded yet.') }}</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($photos as $photo)
                    <div class="w-full max-w-[220px] mx-auto bg-white rounded-lg shadow overflow-hidden">
                        <div class="w-full h-[140px] bg-gray-100">
                            <img
                                src="{{ asset('storage/'.$photo->path) }}"
                                alt="{{ $school->name }}"
                                class="w-full h-full object-cover"
                            >
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
