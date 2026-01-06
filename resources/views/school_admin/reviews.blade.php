<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">{{ __('Verify Reviews (School Admin)') }}</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        @if($reviews->count() === 0)
            <p class="text-gray-600">{{ __('No reviews pending verification.') }}</p>
        @else
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="bg-white p-5 rounded shadow">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="font-semibold">{{ $review->school->name }}</div>
                                <div class="text-sm text-gray-700">
                                    {{ __('Student Number') }}: <b>{{ $review->student_number }}</b>
                                </div>

                                {{-- IMPORTANT: No comment, no ratings here (no bias) --}}
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ __('You only verify the student number.') }}
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('school_admin.reviews.approve', $review) }}">
                                    @csrf
                                    <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        {{ __('Verify & Approve') }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('school_admin.reviews.reject', $review) }}">
                                    @csrf
                                    <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                        {{ __('Reject') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $reviews->links() }}</div>
        @endif
    </div>
</x-app-layout>
