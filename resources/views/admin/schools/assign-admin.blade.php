<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-2">{{ __('Assign School Admin') }}</h1>
        <p class="text-gray-600 mb-6">{{ __('School') }}: <b>{{ $school->name }}</b></p>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded shadow">
            <form method="POST" action="{{ route('admin.schools.assign_admin', $school) }}">
                @csrf

                <h2 class="font-semibold mb-2">{{ __('Option 1: Assign existing admin') }}</h2>
                <select name="existing_admin_id" class="border rounded p-2 w-full mb-4">
                    <option value="">{{ __('-- Choose --') }}</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" @selected(old('existing_admin_id') == $admin->id)>
                            {{ $admin->name }} ({{ $admin->email }})
                        </option>
                    @endforeach
                </select>
                @error('existing_admin_id') <div class="text-red-600 text-sm mb-4">{{ $message }}</div> @enderror

                <hr class="my-6">

                <h2 class="font-semibold mb-2">{{ __('Option 2: Create a new admin') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Name') }}</label>
                        <input name="new_admin_name" value="{{ old('new_admin_name') }}" class="border rounded p-2 w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Email') }}</label>
                        <input name="new_admin_email" value="{{ old('new_admin_email') }}" class="border rounded p-2 w-full">
                        @error('new_admin_email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Password') }}</label>
                        <input name="new_admin_password" type="password" class="border rounded p-2 w-full">
                        @error('new_admin_password') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">{{ __('Phone') }}</label>
                        <input name="phone" value="{{ old('phone') }}" class="border rounded p-2 w-full">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">{{ __('City') }}</label>
                        <input name="city" value="{{ old('city') }}" class="border rounded p-2 w-full">
                    </div>
                </div>

                <div class="mt-6">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                        {{ __('Assign Admin') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
