<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Data</title>
    <meta name="csrf-token" content="{{csrf_token() }}">
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
        <h1>Venue</h1>
      @if(session()->has('success'))
        <h3 class='text1'>{{session()->get('success')}}</h3>
      @endif

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

      <form action="{{ route('admin.venues.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Venue Name</label>
                <input type="text" id="name" name="name" required class="mt-2">
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="city_id">City</label>
                <select name="city_id" id="city_id" class="form-control" required>
                    <option value="">-- Select City --</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>

        <div class="form-group">
        <label for="status">Status</label>
        <select name="status" required>
            <option value="available">Available</option>
            <option value="booked">Booked</option>
        </select>
        </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required class="mt-2">
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" required class="mt-2">
            </div>

            <div class="form-group">
                <label for="capacity">Capacity</label>
               <input type="number" name="capacity" class="form-control" required class="mt-2">
            </div>

            {{-- Facilities --}}
        <div class="form-group">
        <label>Facilities</label>
        <div style="display:flex; flex-wrap: wrap; gap:12px; margin-top:8px;">
        @foreach($facilities as $facility)
            <label style="display:flex; align-items:center; gap:8px; padding:8px 10px; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer;">
                <input
                    type="checkbox"
                    name="facilities[]"
                    value="{{ $facility->id }}"
                    {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}
                >
                {{-- kalau kamu sudah punya helper facility_icon(), boleh tampilkan --}}
                {{-- <span>{!! facility_icon($facility->name) !!}</span> --}}
                <span>{{ $facility->name }}</span>
            </label>
             @endforeach
        </div>
        @error('facilities.*')
        <div style="color:#b91c1c; font-size:12px; margin-top:6px;">{{ $message }}</div>
        @enderror
    </div>
            <div class="form-group">
                <label for="image">Upload Image</label>
                <input type="file" name="images[]" multiple accept="image/*" class="mt-2" required>
            </div>

            <div class="form-group">
                <label for="images">Upload Gambar</label>
                <input type="file" name="images[]" id="images" class="form-control" multiple>
                <small class="text-gray-500">Bisa pilih lebih dari 1 gambar</small>
            </div>

            <button type="submit" class="btn-submit">Save Data</button>
        </form>
    </div>
</body>
</html>
