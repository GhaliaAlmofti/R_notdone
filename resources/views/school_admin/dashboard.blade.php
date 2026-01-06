<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('School Admin Dashboard') }}</h1>

        @if(!$school)
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                {{ __('You are not assigned to a school yet. Please contact the super admin.') }}
            </div>
        @else
            <div class="bg-white p-6 rounded shadow">
                <div class="text-lg font-semibold">{{ $school->name }}</div>
                <div class="text-gray-600 mt-1">
                    {{ $school->area }} • {{ $school->category }} • {{ $school->level }}
                </div>

                <div class="mt-4 flex gap-3">
                    <a class="px-4 py-2 bg-indigo-600 text-white rounded"
                       href="{{ route('school_admin.reviews.index') }}">
                        {{ __('Pending Verification') }}
                    </a>

                    <a class="px-4 py-2 bg-gray-700 text-white rounded"
                       href="#">
                        {{ __('Edit School Profile') }}
                    </a>

                    <a href="{{ route('school_admin.photos.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded">
                        {{ __('Manage Photos') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

