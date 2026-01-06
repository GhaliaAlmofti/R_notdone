<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('Add School') }}</h1>

        <div class="bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('admin.schools.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">{{ __('School Name') }}</label>
                    <input name="name" value="{{ old('name') }}" class="border rounded p-2 w-full" required>
                    @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Area') }}</label>
                        <input name="area" value="{{ old('area') }}" class="border rounded p-2 w-full" required>
                        @error('area') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Category') }}</label>
                        <input name="category" value="{{ old('category') }}" class="border rounded p-2 w-full" required>
                        @error('category') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Level') }}</label>
                        <input name="level" value="{{ old('level') }}" class="border rounded p-2 w-full" required>
                        @error('level') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Gender Type') }}</label>
                        <select name="gender_type" class="border rounded p-2 w-full" required>
                            <option value="mixed" @selected(old('gender_type')==='mixed')>{{ __('Mixed') }}</option>
                            <option value="boys" @selected(old('gender_type')==='boys')>{{ __('Boys') }}</option>
                            <option value="girls" @selected(old('gender_type')==='girls')>{{ __('Girls') }}</option>
                        </select>
                        @error('gender_type') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
                        <input name="email" value="{{ old('email') }}" class="border rounded p-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Phone') }}</label>
                        <input name="phone" value="{{ old('phone') }}" class="border rounded p-2 w-full">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">{{ __('Address') }}</label>
                    <input name="address" value="{{ old('address') }}" class="border rounded p-2 w-full">
                </div>

                <div class="mt-6">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                        {{ __('Create School') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
