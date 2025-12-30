<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .input-error {
            border-color: #ef4444;
        }

        .input-error:focus {
            border-color: #ef4444;
            --tw-ring-color: rgba(239, 68, 68, 0.5);
        }
    </style>
</head>

<body class="bg-gray-50">
    <main class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
        <div class="w-full max-w-sm">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h1>
                <p class="text-gray-500 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <!-- Alert untuk status logout -->
                @if (session('status'))
                    <div class="bg-green-100 border border-green-200 rounded-md p-3 text-sm text-green-800 mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="/login" method="POST" class="space-y-4">
                    @csrf
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-900">
                            Email
                        </label>
                        <input id="email" name="email" type="email" placeholder="nama@example.com"
                            value="{{ old('email') }}"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('email') input-error @enderror" />
                        @error('email')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-900">
                                Password
                            </label>
                            <a href="#" class="text-xs text-blue-600 hover:underline">
                                Lupa password?
                            </a>
                        </div>
                        <input id="password" name="password" type="password" placeholder="••••••••"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('email') input-error @enderror" />
                        @error('email')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md font-medium hover:bg-blue-700 transition-colors mt-2">
                        Masuk
                    </button>
                </form>
            </div>

            <!-- Sign Up Link -->
            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="#" class="text-blue-600 font-medium hover:underline">
                    Daftar di sini
                </a>
            </p>
        </div>
    </main>
</body>

</html>
