<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4" x-data="{ tab: '{{ session('tab', 'profile') }}' }">
        <h1 class="text-2xl font-bold mb-4">{{ __('School Profile') }}</h1>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabs --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex gap-6">
                <button type="button"
                        @click="tab='profile'"
                        :class="tab==='profile' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300'"
                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                    {{ __('Profile Info') }}
                </button>

                <button type="button"
                        @click="tab='photos'"
                        :class="tab==='photos' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300'"
                        class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                    {{ __('Photos') }}
                </button>

                <button type="button"
                        @click="tab = 'published'"
                        :class="tab === 'published' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-600 hover:text-gray-800'"
                        class="px-4 py-2 border-b-2 font-medium text-sm">
                    {{ __('Published Reviews') }}
                </button>
            </nav>
        </div>

        {{-- ===================== TAB 1: PROFILE INFO ===================== --}}
        <div x-show="tab==='profile'" x-cloak>
            <div class="bg-white p-6 rounded shadow">
                <form method="POST"
                      action="{{ route('school_admin.profile.update') }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Logo --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('School Logo') }}
                        </label>

                        @if($school->logo_path)
                            <img src="{{ asset('storage/'.$school->logo_path) }}"
                                 class="h-20 w-20 rounded object-cover mb-2"
                                 alt="logo">
                        @endif

                        <input type="file" name="logo" class="border rounded p-2 w-full">
                        @error('logo')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Basic info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('School Name') }}</label>
                            <input name="name"
                                   value="{{ old('name', $school->name) }}"
                                   class="border rounded p-2 w-full"
                                   required>
                            @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
                            <input name="email"
                                   value="{{ old('email', $school->email) }}"
                                   class="border rounded p-2 w-full">
                            @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Phone') }}</label>
                            <input name="phone"
                                   value="{{ old('phone', $school->phone) }}"
                                   class="border rounded p-2 w-full">
                            @error('phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Address') }}</label>
                            <input name="address"
                                   value="{{ old('address', $school->address) }}"
                                   class="border rounded p-2 w-full">
                            @error('address') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-6">

                    {{-- Classification --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Area') }}</label>
                            <input name="area"
                                   value="{{ old('area', $school->area) }}"
                                   class="border rounded p-2 w-full"
                                   required>
                            @error('area') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Category') }}</label>
                            <input name="category"
                                   value="{{ old('category', $school->category) }}"
                                   class="border rounded p-2 w-full"
                                   required>
                            @error('category') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Level') }}</label>
                            <input name="level"
                                   value="{{ old('level', $school->level) }}"
                                   class="border rounded p-2 w-full"
                                   required>
                            @error('level') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Gender Type') }}</label>
                            <select name="gender_type" class="border rounded p-2 w-full" required>
                                <option value="mixed" @selected(old('gender_type', $school->gender_type) === 'mixed')>
                                    {{ __('Mixed') }}
                                </option>
                                <option value="boys" @selected(old('gender_type', $school->gender_type) === 'boys')>
                                    {{ __('Boys') }}
                                </option>
                                <option value="girls" @selected(old('gender_type', $school->gender_type) === 'girls')>
                                    {{ __('Girls') }}
                                </option>
                            </select>
                            @error('gender_type') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('President Name') }}</label>
                            <input name="president_name"
                                   value="{{ old('president_name', $school->president_name) }}"
                                   class="border rounded p-2 w-full">
                            @error('president_name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">{{ __('Fees Range') }}</label>
                            <input name="fees_range"
                                   value="{{ old('fees_range', $school->fees_range) }}"
                                   class="border rounded p-2 w-full">
                            @error('fees_range') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">{{ __('Curriculum') }}</label>
                            <input name="curriculum"
                                   value="{{ old('curriculum', $school->curriculum) }}"
                                   class="border rounded p-2 w-full">
                            @error('curriculum') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===================== TAB 2: PHOTOS ===================== --}}
        <div x-show="tab==='photos'" x-cloak>
            <div class="bg-white p-6 rounded shadow">

                {{-- Upload --}}
                <h2 class="text-lg font-semibold mb-3">{{ __('School Photos') }}</h2>

                <form method="POST"
                      action="{{ route('school_admin.photos.store') }}"
                      enctype="multipart/form-data"
                      class="mb-6">
                    @csrf

                    <label class="block text-sm font-medium mb-2">
                        {{ __('Upload photos (multiple)') }}
                    </label>

                    <input type="file"
                           name="photos[]"
                           multiple
                           accept="image/*"
                           class="border rounded p-2 w-full">

                    @error('photos')
                        <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
                    @enderror
                    @error('photos.*')
                        <div class="text-sm text-red-600 mt-2">{{ $message }}</div>
                    @enderror

                    <button class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        {{ __('Upload') }}
                    </button>
                </form>

                {{-- Grid --}}
                @php
                    $limitedPhotos = ($photos ?? collect())->take(8);
                @endphp

                @if($limitedPhotos->count() === 0)
                    <p class="text-gray-600">{{ __('No photos uploaded yet.') }}</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($limitedPhotos as $photo)
                            <div class="border rounded-lg overflow-hidden bg-gray-50">
                                <img src="{{ asset('storage/'.$photo->path) }}"
                                     class="w-full aspect-square object-cover"
                                     alt="photo">

                                <div class="p-2">
                                    <form method="POST"
                                          action="{{ route('school_admin.photos.destroy', $photo) }}"
                                          onsubmit="return confirm('{{ __('Delete this photo?') }}')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="w-full text-sm px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(($photos ?? collect())->count() > 8)
                        <p class="text-sm text-gray-600 mt-3">
                            {{ __('Showing 8 of :total photos. Upload more or delete old ones.', ['total' => ($photos ?? collect())->count()]) }}
                        </p>
                    @endif
                @endif

            </div>
        </div>

        {{-- =========================
     TAB: Published Reviews
        ========================= --}}
<div x-show="tab === 'published'" class="mt-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('Published Reviews') }}</h2>
            <div class="text-sm text-gray-500">
                {{ __('Total') }}: {{ ($publishedReviews ?? collect())->count() }}
            </div>
        </div>

        @if(($publishedReviews ?? collect())->count() === 0)
            <p class="text-gray-600">{{ __('No published reviews yet.') }}</p>
        @else
            <div class="space-y-3">
                @foreach($publishedReviews as $review)
                    <div class="border rounded-lg p-4">
                        {{-- Header --}}
                        <div class="flex items-start justify-between gap-4">
                            <div class="text-sm text-gray-600">
                                ⭐ {{ number_format((float)($review->overall_rating ?? 0), 1) }}
                                • {{ $review->created_at?->format('Y-m-d') }}
                            </div>

                            {{-- If already reported, show badge --}}
                            @if($review->is_reported)
                                <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-700">
                                    {{ __('Reported') }}
                                </span>
                            @endif
                        </div>

                        {{-- Comment --}}
                        <p class="mt-2 text-gray-900">
                            {{ $review->comment ?? __('(No comment)') }}
                        </p>

                        {{-- Report action (only if not reported yet) --}}
                        @if(!$review->is_reported)
                            <div class="mt-3" x-data="{ openReport: false }">
                                <button type="button"
                                        @click="openReport = !openReport"
                                        class="text-sm text-red-600 hover:underline">
                                    {{ __('Report this review') }}
                                </button>

                                <div x-show="openReport" class="mt-2">
                                    <form method="POST"
                                          action="{{ route('school_admin.reviews.report', $review) }}"
                                          class="flex flex-col md:flex-row gap-2">
                                        @csrf

                                        <input name="report_reason"
                                               class="border rounded p-2 text-sm w-full"
                                               placeholder="{{ __('Reason (required)') }}"
                                               required
                                               maxlength="500">

                                        <button class="px-3 py-2 bg-red-600 text-white rounded text-sm">
                                            {{ __('Send Report') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>


    </div>
</x-app-layout>

