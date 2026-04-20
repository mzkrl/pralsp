<form action="/berita/store" method="post">
    @csrf
    <input type="text" name="judul" placeholder="judul" id=""><br>
    <textarea name="isi" id=""></textarea><br>
    <input type="hidden" name="author" value="{{ $author=session('name') }}"><br>
    <button type="submit">simpan</button>
</form>