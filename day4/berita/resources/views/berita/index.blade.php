
<main class="anitaliased">
    <x-navbar></x-navbar>
    <div>
        <h2 class="">data berita</h2>
        <a href="/berita/create">tambah</a>
    </div>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
         @foreach ($data as $b )
            <h3 class="mt-6 text-xl font-semibold text-gray-900">{{ $b->judul }}</h3>
            <p class="mt-4 text-gray-800 text-sm leading-relaxed">{{ $b->isi }}</p>
            <div class="">
                <a class="" href="/berita/delete/{{ $b->id }}">hapus</a>
            </div>
        @endforeach
    </div>
</main>

