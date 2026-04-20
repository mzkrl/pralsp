
<main class="anitaliased">
    <x-navbar></x-navbar>
    <div class="flex text-4xl justify-between mt-10 bg-amber-100 pt-2 pb-2 pl-10 pr-10 shadow-2xl border-l">
        <h2 class="">data berita</h2>
        <a class="bg-green-500 rounded-2xl pl-1 pr-1 text-4xl" href="/berita/create">tambah</a>
    </div>
    <div class="mx-auto p-6 lg:p-8 shadow-2xl bg-gray-100 ml-0.5">
         @foreach ($data as $b )
         <div class="border-l pl-2">
             <h3 class="mt-6 text-3xl font-semibold text-gray-900">{{ $b->judul }}</h3>
             <p class="mt-4 text-xl text-gray-800 leading-relaxed">{{ $b->isi }}</p>
             <div class="mt-10 flex justify-between">
                <span class="justify-start">Author: {{ $b->author }}</span>
                <a class="bg-gray-100 text-red-500 text-xl justify-end" href="/berita/delete/{{ $b->id }}">hapus</a>
             </div>
         </div>
        @endforeach
    </div>  
</main>