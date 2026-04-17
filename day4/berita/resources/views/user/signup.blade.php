@if (session('error'))
    <p>{{ session('error') }}</p>
@endif
<h1>sign up</h1>
<form action="/user/signup" method="POSt">
    @csrf
    <input type="text" name="name" placeholder="username" id=""><br>
    <input type="text" name="email" placeholder="email" id=""><br>
    <input type="password" name="password" placeholder="password"><br>
    <button type="submit">simpan</button>
</form>