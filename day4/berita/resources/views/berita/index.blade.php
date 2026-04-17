
<main class="anitaliased">
    <x-navbar></x-navbar>
    <div class="flex text-4xl justify-between mt-10 bg-amber-100 pt-2 pb-2 pl-10 pr-10 shadow-2xl border-l">
        <h2 class="">data berita</h2>
        <a href="/berita/create">tambah</a>
    </div>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
         @foreach ($data as $b )
         <div class="border-l pl-2 text-5xl">
             <h3 class="mt-6 font-semibold text-gray-900">{{ $b->judul }}</h3>
             <p class="mt-4 text-xl text-gray-800 leading-relaxed">{{ $b->isi }}</p>
             <div class="mt-10 text-red-500">
                 <a class="bg-gray-100 " href="/berita/delete/{{ $b->id }}">hapus</a>
             </div>
         </div>
        @endforeach
    </div>
</main>

