<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">{{ __('Admin Dashboard') }}</h1>

        <div class="bg-white p-6 rounded shadow">
            <a href="{{ route('admin.reviews.moderation') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                {{ __('Moderate Reviews') }}
            </a>
        </div>
    </div>
</x-app-layout>
