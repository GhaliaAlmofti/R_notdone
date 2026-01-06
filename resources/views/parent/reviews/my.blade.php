<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('My Reviews') }}</h1>

            <a href="{{ route('parent.schools.index') }}"
               class="text-sm text-gray-600 underline hover:text-gray-900">
                {{ __('Back to Schools') }}
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($reviews->count() === 0)
            <div class="bg-white p-6 rounded shadow">
                <p class="text-gray-700">{{ __('You have not submitted any reviews yet.') }}</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($reviews as $review)
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-sm text-gray-600">
                                    {{ __('School') }}:
                                    <a class="underline hover:text-gray-900"
                                       href="{{ route('parent.schools.show', $review->school->slug) }}">
                                        {{ $review->school->name }}
                                    </a>
                                </div>

                                <div class="text-sm text-gray-600 mt-1">
                                    ⭐ {{ number_format((float)($review->overall_rating ?? 0), 1) }}
                                    • {{ $review->created_at->format('Y-m-d') }}
                                </div>
                            </div>

                            {{-- Status badge --}}
                            @php
                                $status = $review->status;
                                $badge = match($status) {
                                    'pending_moderation' => 'bg-yellow-100 text-yellow-800',
                                    'pending_verification' => 'bg-blue-100 text-blue-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp

                            <span class="px-3 py-1 rounded text-xs font-semibold {{ $badge }}">
                                {{ __(str_replace('_', ' ', ucfirst($status))) }}
                            </span>
                        </div>

                        <div class="mt-3 text-gray-900">
                            <b>{{ __('Comment') }}:</b>
                            {{ $review->comment ?? __('No comment provided') }}
                        </div>

                        {{-- Extra flags --}}
                        @if($review->is_reported)
                            <div class="mt-2 text-sm text-red-700">
                                ⚑ {{ __('This review was reported.') }}
                            </div>
                        @endif

                        {{-- Show report status if current user reported this review --}}
                        @if($review->is_reported && $review->reported_by === auth()->id())
                            <div class="text-sm mt-1">
                                <span class="font-semibold text-red-600">{{ __('Reported') }}</span>
                                —
                                <span class="text-gray-700">
                                    {{
                                        $review->report_status === 'pending' ? __('Pending') :
                                        ($review->report_status === 'resolved_valid' ? __('Resolved (Valid)') :
                                        ($review->report_status === 'resolved_invalid' ? __('Resolved (Invalid)') : __('Pending'))
                                    }}
                                </span>
                            </div>
                        @endif

                        @if($review->status === 'rejected' && $review->rejection_reason)
                            <div class="mt-2 text-sm text-red-700">
                                <b>{{ __('Rejection reason') }}:</b> {{ $review->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
