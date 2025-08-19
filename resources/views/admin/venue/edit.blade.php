<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Data</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* File CSS (bisa juga eksternal) */
        :root {
            --primary: #4f46e5;
            --secondary: #f9fafb;
            --text: #1f2937;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            color: var(--text);
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn-submit {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #4338ca;
        }

        .datetime-group {
            display: flex;
            gap: 15px;
        }

        .datetime-group > div {
            flex: 1;
        }
        .text1 {
          color:white;
          background:green;
          padding: 5px;
          border-radius:5px;
          box-shadow: 2px 2px 5px 2px gray;
        }


        @media (max-width: 480px) {
            .datetime-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Date Available</h1>
      @if(session()->has('succes'))
        <h3 class='text1'>{{session()->get('succes')}}</h3>
      @endif

       <form action="{{ route('admin.venues.update', $venue->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Tampilkan error validasi agar tahu kenapa gagal --}}
    @if ($errors->any())
        <div style="background:#fee2e2;color:#b91c1c;padding:10px;border-radius:8px;margin-bottom:16px;">
            <strong>Periksa input kamu:</strong>
            <ul style="margin:6px 0 0 18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-group">
        <label for="name">Venue</label>
        <input type="text" id="name" name="name" required
               value="{{ old('name', $venue->name) }}">
    </div>

    <div class="form-group">
        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $venue->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="city_id">City</label>
        <select name="city_id" id="city_id" required>
            <option value="">-- Select City --</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}"
                    {{ old('city_id', $venue->city_id) == $city->id ? 'selected' : '' }}>
                    {{ $city->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Tambahkan status biar validasi tidak diam-diam gagal --}}
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" required>
            <option value="available">Available</option>
            <option value="booked">Booked</option>
        </select>
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" required
               value="{{ old('address', $venue->address) }}">
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" id="price" name="price" step="0.01" min="0" required
               value="{{ old('price', $venue->price) }}">
    </div>

    <div class="form-group">
        <label for="capacity">Capacity</label>
        <input type="number" id="capacity" name="capacity" min="1" required
               value="{{ old('capacity', $venue->capacity) }}">
    </div>

    {{-- Preview gambar utama saat ini --}}
    @if ($venue->image)
        <div class="form-group" style="margin-bottom:10px">
            <label>Current Image</label><br>
            <img src="{{ asset('storage/'.$venue->image) }}" alt="current image" style="max-width:200px;border-radius:8px">
        </div>
    @endif

    <div class="form-group">
        <label for="image">Upload New Main Image (optional)</label>
        <input type="file" name="image" id="image" accept="image/*">
    </div>

    {{-- (Opsional) tambah banyak gambar --}}
    {{-- <div class="form-group">
        <label for="images">Gallery Images (optional, multiple)</label>
        <input type="file" name="images[]" id="images" accept="image/*" multiple>
    </div> --}}

    <button type="submit" class="btn-submit">Ubah</button>
</form>
    </div>
</body>
</html>
