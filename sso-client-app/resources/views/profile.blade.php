<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - SSO Client App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">SSO Client App</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">Welcome, {{ Auth::user()->name }}!</span>
                        <form method="POST" action="{{ route('sso.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg p-8">
                    <div class="max-w-2xl mx-auto">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">User Profile</h2>
                        
                        <!-- Profile Card -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Profile Information</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Personal details and account information.</p>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Full name</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ Auth::user()->name }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Email address</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ Auth::user()->email }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Account created</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ Auth::user()->created_at->format('M d, Y H:i:s') }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Last updated</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ Auth::user()->updated_at->format('M d, Y H:i:s') }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Authentication method</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Single Sign-On (SSO)
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">SSO Token Status</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            @if(session('sso_token'))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-center space-x-4">
                            <a href="/dashboard" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Back to Dashboard
                            </a>
                            <a href="/" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Home
                            </a>
                        </div>

                        <!-- SSO Information -->
                        <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">SSO Information</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Your account is authenticated through Single Sign-On. This means:</p>
                                        <ul class="mt-2 list-disc list-inside">
                                            <li>You can access multiple applications with one login</li>
                                            <li>Your credentials are managed centrally and securely</li>
                                            <li>When you log out, you'll be logged out of all connected applications</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
