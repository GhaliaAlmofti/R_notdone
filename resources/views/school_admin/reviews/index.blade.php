<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('Student Number Verification') }}</h1>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($reviews->count() === 0)
            <p class="text-gray-600">{{ __('No reviews pending verification.') }}</p>
        @else
            <div class="space-y-3">
                @foreach($reviews as $review)
                    <div class="bg-white p-4 rounded shadow">

                        <div class="text-sm text-gray-600 mb-2">
                            {{ __('School') }}: <b>{{ $review->school->name }}</b>
                        </div>

                        <div class="text-lg">
                            <b>{{ __('Student Number') }}:</b> {{ $review->student_number }}
                        </div>

                        {{-- Approve / Reject --}}
                        <div class="flex flex-wrap gap-2 mt-3">
                            <form method="POST" action="{{ route('school_admin.reviews.approve', $review) }}">
                                @csrf
                                <button class="px-3 py-2 bg-indigo-600 text-white rounded">
                                    {{ __('Verify & Approve') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('school_admin.reviews.reject', $review) }}">
                                @csrf
                                <input name="rejection_reason"
                                       class="border rounded p-2 text-sm"
                                       placeholder="{{ __('Rejection reason (required)') }}"
                                       required>
                                <button class="px-3 py-2 bg-red-600 text-white rounded">
                                    {{ __('Reject') }}
                                </button>
                            </form>
                        </div>

                        {{-- ✅ Report (School Admin) --}}
                        <div class="mt-3" x-data="{ openReport: false }">
                            <button type="button"
                                    @click="openReport = !openReport"
                                    class="text-sm text-red-600 hover:underline">
                                {{ __('Report') }}
                            </button>

                            <div x-show="openReport" class="mt-2">
                                <form method="POST"
                                      action="{{ route('school_admin.reviews.report', $review) }}"
                                      class="flex gap-2">
                                    @csrf

                                    <input name="report_reason"
                                           class="border rounded p-2 text-sm w-full"
                                           placeholder="{{ __('Reason (required)') }}"
                                           required>

                                    <button class="px-3 py-2 bg-red-600 text-white rounded text-sm">
                                        {{ __('Send') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
