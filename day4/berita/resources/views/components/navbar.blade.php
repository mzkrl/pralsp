<head>
    <title>hore</title>
    @vite('resources/css/app.css')
</head>

<nav class="flex bg-purple-100 shadow-xl py-6 px-8 justify-between">
    <h2 class="text-4xl font-bold flex my-auto text-gray-800">yayaya</h2>
    <li class="flex gap-2">
        <ul>
            <a href="/" class="btn btn-ghost flex my-auto text-gray-800 text-md font-bold py-2 px-6">
                Home
            </a>
        </ul>
        <ul>
            <a href="/berita" class="btn btn-ghost flex my-auto text-gray-800 text-md font-bold py-2 px-6">
                Dashboard
            </a>
        </ul>
        <ul>
            <a href="/berita/create" class="btn btn-ghost flex my-auto text-gray-800 text-md font-bold py-2 px-6">
                Tambah
            </a>
        </ul>
    </li>
    <li class="flex gap-2">

        @if (session('login') )
            <ul>
                <span class="btn btn-ghost flex my-auto text-gray-800 text-md font-bold py-2 px-6">Halo, {{ session('name') }}</span>
            </ul>
            <ul>
                <a href="/user/logout">
                    <button class="btn btn-ghost flex my-auto text-gray-800 text-md font-bold py-2 px-6">Log out</button>
                </a>
            </ul>
        @else
            <ul>
                <a href="/user/login">
                    <button class="btn btn-ghost flex my-auto text-gray-800 text-md font-bold py-2 px-6">Login</button>
                </a>
            </ul>
            <ul>
                <a href="/user/signup">
                    <button class="btn btn-ghost flex my-auto text-gray-800 text-md font-bold py-2 px-6">Sign Up</button>
                </a>
            </ul>
        @endif
    </li>
</nav>