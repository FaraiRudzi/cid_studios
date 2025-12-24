<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Photographer Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Photographer Login</h2>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('photographer.login') }}">
            @csrf

            <label for="username" class="block font-semibold mb-1">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                class="w-full border px-3 py-2 rounded mb-4" />

            <label for="password" class="block font-semibold mb-1">Password</label>
            <input type="password" id="password" name="password" required
                class="w-full border px-3 py-2 rounded mb-4" />

            <div class="flex items-center justify-between mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="mr-2" />
                    Remember Me
                </label>
                <a href="#" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Login
            </button>
        </form>
    </div>

</body>
</html>
