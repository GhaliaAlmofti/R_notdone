<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4">

        {{-- ✅ Define $myReview safely (always exists) --}}
        @php
            $myReview = null;

            if (auth()->check()) {
                $myReview = \App\Models\Review::where('school_id', $school->id)
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->first();
            }
        @endphp

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pending message --}}
        @auth
            @if($myReview && $myReview->status !== 'approved')
                <div class="mt-4 p-3 rounded bg-yellow-100 text-yellow-800">
                    {{ __('Your review was submitted and is pending approval.') }}
                    <span class="font-semibold">({{ $myReview->status }})</span>
                </div>
            @endif
        @endauth

        {{-- Back --}}
        <a href="{{ route('parent.schools.index') }}"
           class="text-sm text-gray-600 underline hover:text-gray-900">
            Back to search
        </a>

        {{-- School Info --}}
        <div class="bg-white p-6 rounded-lg shadow mt-4">
            <div class="flex items-start justify-between gap-6">
                <div class="flex items-start gap-4">
                    {{-- ✅ School Logo --}}
                    @if($school->logo_path)
                        <img src="{{ asset('storage/'.$school->logo_path) }}"
                             alt="{{ $school->name }}"
                             class="h-20 w-20 rounded object-cover border">
                    @else
                        <div class="h-20 w-20 rounded bg-gray-100 flex items-center justify-center text-xs text-gray-500">
                            {{ __('No logo') }}
                        </div>
                    @endif

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $school->name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $school->address }}</p>
                        <p class="text-gray-600">
                            {{ $school->email }} • {{ $school->phone }}
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-sm text-gray-600">Average rating</div>
                    <div class="text-3xl font-bold text-gray-900">
                        {{ number_format((float)($avgRating ?? 0), 1) }}
                    </div>
                </div>
            </div>

            <hr class="my-5" />

            {{-- Quick Info --}}
            <h2 class="text-lg font-semibold mb-3 text-gray-900">Quick Info</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-800">
                <div><b>Area:</b> {{ $school->area }}</div>
                <div><b>Category:</b> {{ $school->category }}</div>
                <div><b>Level:</b> {{ $school->level }}</div>
                <div><b>President:</b> {{ $school->president_name }}</div>
                <div><b>Fees range:</b> {{ $school->fees_range }}</div>
                <div><b>Gender type:</b> {{ $school->gender_type }}</div>
                <div><b>Curriculum:</b> {{ $school->curriculum }}</div>
            </div>

            {{-- ✅ CTAs --}}
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('parent.schools.photos', $school->slug) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    {{ __('Photos') }}
                </a>

                @auth
                    @if(!$myReview)
                        <a href="{{ route('parent.reviews.create', $school->slug) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 text-sm">
                            {{ __('Write a Review') }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-700 text-white font-semibold rounded-md hover:bg-gray-800 text-sm">
                        {{ __('Login to Write a Review') }}
                    </a>
                @endauth
            </div>
        </div>

        {{-- Reviews Section --}}
        <div class="mt-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-900">{{ __('Approved Reviews') }}</h2>

                {{-- ✅ Secondary CTA --}}
                @auth
                    @if(!$myReview)
                        <a href="{{ route('parent.reviews.create', $school->slug) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            {{ __('Write a review') }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md text-sm shadow hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition">
                        {{ __('Login to write a review') }}
                    </a>
                @endauth
            </div>

            {{-- Reviews List --}}
            @if($approvedReviews->count() === 0)
                <p class="text-gray-600">{{ __('No reviews yet.') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($approvedReviews as $review)
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex items-start justify-between gap-4">
                                <div class="text-sm text-gray-600 mb-2">
                                    ⭐ {{ number_format((float)($review->overall_rating ?? 0), 1) }}
                                    • {{ $review->created_at->format('Y-m-d') }}
                                </div>
                            </div>

                            <p class="text-gray-900">
                                {{ $review->comment ?? __('No comment provided') }}
                            </p>

                            {{-- ✅ Report (auth only) --}}
                            @auth
                                <div class="mt-3" x-data="{ open: false }">
                                    <button type="button"
                                            @click="open = !open"
                                            class="text-sm text-red-600 hover:underline">
                                        {{ __('Report') }}
                                    </button>

                                    <div x-show="open" class="mt-2">
                                        <form method="POST" action="{{ route('parent.reviews.report', $review) }}" class="flex gap-2">
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
                            @endauth
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
