<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4">

        <h1 class="text-2xl font-bold mb-2">
            {{ __('Manage School Photos') }}
        </h1>

        <p class="text-gray-600 mb-6">
            {{ __('School') }}: <b>{{ $school->name }}</b>
        </p>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Upload --}}
        <div class="bg-white p-4 rounded shadow mb-6">
            <h2 class="font-semibold mb-3">{{ __('Upload Photos') }}</h2>

            <form method="POST" action="{{ route('school_admin.photos.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf

                <input type="file"
                       name="photos[]"
                       multiple
                       accept="image/*"
                       class="border rounded p-2 w-full">

                @error('photos')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('photos.*')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                    {{ __('Upload') }}
                </button>
            </form>
        </div>

        {{-- Gallery --}}
        @if($photos->count() === 0)
            <p class="text-gray-600">{{ __('No photos yet.') }}</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($photos as $photo)
                    <div class="bg-white p-2 rounded shadow">
                        <img src="{{ asset('storage/'.$photo->path) }}"
                             class="w-full h-32 object-cover rounded"
                             alt="{{ $school->name }}">

                        <form method="POST" action="{{ route('school_admin.photos.destroy', $photo) }}" class="mt-2">
                            @csrf
                            @method('DELETE')

                            <button class="w-full px-3 py-2 bg-red-600 text-white rounded text-sm"
                                    onclick="return confirm('{{ __('Delete this photo?') }}')">
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
