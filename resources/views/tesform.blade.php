<!DOCTYPE html>
<html>
<head>
    <title>Test Form</title>
</head>
<body>
    <h1>Test Form Submit</h1>
    <form action="{{ route('test.form.store') }}" method="POST">
        @csrf
        <label>Nama:</label>
        <input type="text" name="nama" required>
        <button type="submit">Kirim</button>
    </form>
</body>
</html>
        