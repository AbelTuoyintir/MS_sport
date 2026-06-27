<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Barlow', sans-serif; background-color: #06090e; color: #e8edf4; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 bg-gray-900 border border-gray-800 rounded-xl shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black font-heading text-white tracking-wide uppercase">MP League</h1>
            <p class="text-gray-400 text-sm">Sign in to manage your team</p>
        </div>

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-900/30 border border-red-800 text-red-400 text-sm rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" id="email" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-cyan-500 transition-colors" placeholder="email@example.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" id="password" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-cyan-500 transition-colors" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black font-heading uppercase py-3 rounded-lg transition-colors tracking-widest">
                Sign In
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-800 text-center">
            <p class="text-gray-500 text-sm">Don't have a team? <a href="{{ route('team.register.form') }}" class="text-cyan-500 hover:underline">Register now</a></p>
        </div>
    </div>
</body>
</html>
