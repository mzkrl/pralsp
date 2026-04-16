<form action="/berita/store" method="post">
    @csrf
    <input type="text" name="judul" placeholder="judul" id=""><br>
    <textarea name="isi" id=""></textarea><br>
    <button type="submit">simpan</button>
</form>