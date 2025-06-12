<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SSO Client App</title>
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
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Dashboard</h2>
                        <p class="text-lg text-gray-600 mb-8">
                            You are successfully logged in via Single Sign-On (SSO)!
                        </p>
                        
                        <!-- User Information Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg max-w-md mx-auto">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">User Information</h3>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->updated_at->format('M d, Y H:i:s') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Navigation Links -->
                        <div class="mt-8 flex justify-center space-x-4">
                            <a href="/profile" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                View Profile
                            </a>
                            <a href="/" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-md shadow-lg">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('success-message').style.display = 'none';
            }, 5000);
        </script>
    @endif

    @if(session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-md shadow-lg">
            {{ session('error') }}
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 5000);
        </script>
    @endif
</body>
</html>
