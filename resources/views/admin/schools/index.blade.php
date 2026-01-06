<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">{{ __('Schools') }}</h1>

            <a href="{{ route('admin.schools.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded">
                {{ __('Add School') }}
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">{{ __('Name') }}</th>
                        <th class="p-3 text-left">{{ __('Area') }}</th>
                        <th class="p-3 text-left">{{ __('Admin') }}</th>
                        <th class="p-3 text-left">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schools as $school)
                        <tr class="border-t">
                            <td class="p-3">{{ $school->name }}</td>
                            <td class="p-3">{{ $school->area }}</td>
                            <td class="p-3">
                                {{ $school->admin?->email ?? __('Not assigned') }}
                            </td>

                            {{-- ✅ UPDATED ACTIONS COLUMN --}}
                            <td class="p-3 space-x-3">
                                <a class="text-indigo-600 hover:underline"
                                   href="{{ route('admin.schools.edit', $school) }}">
                                    {{ __('Edit') }}
                                </a>

                                <a class="text-indigo-600 hover:underline"
                                   href="{{ route('admin.schools.assign_admin_form', $school) }}">
                                    {{ __('Assign Admin') }}
                                </a>

                                @if($school->admin_user_id)
                                    <form class="inline"
                                          method="POST"
                                          action="{{ route('admin.schools.unassign_admin', $school) }}">
                                        @csrf
                                        <button type="submit"
                                                class="text-yellow-700 hover:underline">
                                            {{ __('Unassign Admin') }}
                                        </button>
                                    </form>
                                @endif

                                <form class="inline"
                                      method="POST"
                                      action="{{ route('admin.schools.destroy', $school) }}"
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this school?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $schools->links() }}
        </div>
    </div>
</x-app-layout>
