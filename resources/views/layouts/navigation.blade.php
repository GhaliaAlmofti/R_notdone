@php
    $navSchool = null;

    if (auth()->check() && auth()->user()->role === 'school_admin') {
        $navSchool = auth()->user()->adminSchool;
    }
    $currentLocale = app()->getLocale();
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7x1 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side -->
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                @auth
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        {{-- PARENT --}}
                        @if(auth()->user()->role === 'parent')
                            <x-nav-link :href="route('parent.schools.index')"
                                        :active="request()->routeIs('parent.schools.*')">
                                {{ __('Schools') }}
                            </x-nav-link>

                            <x-nav-link :href="route('parent.reviews.my')"
                                        :active="request()->routeIs('parent.reviews.my')">
                                {{ __('My Reviews') }}
                            </x-nav-link>
                        @endif

                        {{-- SUPER ADMIN --}}
                        @if(auth()->user()->role === 'super_admin')
                            <x-nav-link :href="route('admin.reviews.moderation')"
                                        :active="request()->routeIs('admin.reviews.*')">
                                {{ __('Moderation') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.reports.index')"
                                        :active="request()->routeIs('admin.reports.*')">
                                {{ __('Reports') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.schools.index')"
                                        :active="request()->routeIs('admin.schools.*')">
                                {{ __('Schools') }}
                            </x-nav-link>
                        @endif

                        {{-- SCHOOL ADMIN --}}
                        @if(auth()->user()->role === 'school_admin' && $navSchool)
                            <x-nav-link :href="route('school_admin.profile.edit')"
                                        :active="request()->routeIs('school_admin.profile.*')">
                                {{ __('School Profile') }}
                            </x-nav-link>

                            <x-nav-link :href="route('school_admin.reviews.index')"
                                        :active="request()->routeIs('school_admin.reviews.*')">
                                {{ __('Verification') }}
                            </x-nav-link>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ auth()->user()?->name }}</div>

                                @if(auth()->user()->role === 'school_admin' && $navSchool?->logo_path)
                                    <div class="ms-2">
                                        <img src="{{ asset('storage/'.$navSchool->logo_path) }}"
                                             alt="logo"
                                             class="h-6 w-6 rounded-full object-cover">
                                    </div>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <div class="space-x-4">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Login
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Register
                            </a>
                        @endif
                    </div>
                @endguest

                {{-- Language Switcher --}}
                <div class="flex items-center gap-2 ms-4">
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="text-sm px-2 py-1 rounded border {{ $currentLocale === 'en' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700' }}">
                        EN
                    </a>
                    <a href="{{ route('lang.switch', 'ar') }}"
                       class="text-sm px-2 py-1 rounded border {{ $currentLocale === 'ar' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700' }}">
                        AR
                    </a>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

