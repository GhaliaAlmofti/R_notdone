<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('Review Moderation') }}</h1>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($reviews->count() === 0)
            <p class="text-gray-600">{{ __('No reviews pending moderation.') }}</p>
        @else
            <div class="space-y-3">
                @foreach($reviews as $review)
                    <div class="bg-white p-4 rounded shadow">
                        <div class="text-sm text-gray-600 mb-2">
                            {{ __('School') }}: <b>{{ $review->school->name }}</b> •
                            {{ __('Parent') }}: <b>{{ $review->user->name }}</b> •
                            {{ __('Submitted') }}: {{ $review->created_at->format('Y-m-d') }}
                        </div>

                        <p class="mb-3">
                            <b>{{ __('Comment') }}:</b>
                            {{ $review->comment ?? __('(No comment)') }}
                        </p>

                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.reviews.approve_moderation', $review) }}">
                                @csrf
                                <button class="px-3 py-2 bg-indigo-600 text-white rounded">
                                    {{ __('Approve (send to school verification)') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.reviews.reject_moderation', $review) }}">
                                @csrf
                                <input
                                    name="rejection_reason"
                                    class="border rounded p-2 text-sm"
                                    placeholder="{{ __('Rejection reason (required)') }}"
                                    required
                                >
                                <button class="px-3 py-2 bg-red-600 text-white rounded">
                                    {{ __('Reject') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
