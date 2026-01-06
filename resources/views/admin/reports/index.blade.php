<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('Reported Reviews') }}</h1>

        @if($reported->count() === 0)
            <p class="text-gray-600">{{ __('No reported reviews.') }}</p>
        @else
            <div class="space-y-3">
                @foreach($reported as $review)
                    <div class="bg-white p-4 rounded shadow">
                        <div class="text-sm text-gray-600 mb-2">
                            {{ __('School') }}: <b>{{ $review->school->name }}</b> •
                            {{ __('Parent') }}: <b>{{ $review->user->name }}</b>
                        </div>

                        <div class="text-sm text-gray-700 mb-2">
                            <b>{{ __('Reason') }}:</b> {{ $review->report_reason }}
                        </div>

                        <div class="text-sm text-gray-600 mb-2">
                            {{ __('Reported at') }}:
                            {{ optional($review->reported_at)->format('Y-m-d H:i') ?? __('(Unknown)') }}
                        </div>

                        <div class="text-sm text-gray-700 mb-2">
                            <b>{{ __('Reported by') }}:</b>
                            {{ $review->reporter?->name ?? __('Unknown') }}
                            @if($review->reporter?->email)
                                • {{ $review->reporter->email }}
                            @endif
                            @if($review->reporter?->role)
                                • {{ __('Role') }}: {{ $review->reporter->role }}
                            @endif
                        </div>

                        @if($review->reporter?->phone)
                            <div class="text-sm text-gray-600">
                                {{ __('Phone') }}: {{ $review->reporter->phone }}
                                @if($review->reporter?->city)
                                    • {{ __('City') }}: {{ $review->reporter->city }}
                                @endif
                            </div>
                        @endif

                        <p class="text-gray-900 mt-2">
                            <b>{{ __('Comment') }}:</b> {{ $review->comment ?? __('(No comment)') }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('admin.reports.dismiss', $review) }}">
                                @csrf
                                <button class="px-3 py-2 bg-gray-700 text-white rounded">
                                    {{ __('Dismiss report') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.reports.remove', $review) }}" class="flex gap-2">
                                @csrf
                                <input name="rejection_reason"
                                       class="border rounded p-2 text-sm"
                                       placeholder="{{ __('Removal reason (required)') }}"
                                       required>
                                <button class="px-3 py-2 bg-red-600 text-white rounded">
                                    {{ __('Remove review') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.reports.valid', $review) }}">
                                @csrf
                                <button class="px-3 py-2 bg-green-600 text-white rounded text-sm">
                                    {{ __('Valid') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.reports.invalid', $review) }}">
                                @csrf
                                <button class="px-3 py-2 bg-gray-700 text-white rounded text-sm">
                                    {{ __('Invalid') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
