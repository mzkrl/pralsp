<main class="bg-gray-300">
    <x-navbar></x-navbar>
    @if (session('login') )
        <p>halo {{ session('name') }}</p>
        <a href="/user/logout">logout</a>
    @else
        <p>halo ga login</p>
        <a href="/user/login">login</a>
        <a href="/user/signup">sign up</a>
    @endif
</main>
