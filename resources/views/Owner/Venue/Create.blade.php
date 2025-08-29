<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Venue</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
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
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        input[type="text"],
        input[type="number"],
        select,
        textarea,
        input[type="file"] {
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
        .btn-submit:hover { background-color: #4338ca; }
        .text1 {
            color: white;
            background: green;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 2px 2px 5px 2px gray;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Venue</h1>

        {{-- Success message --}}
        @if(session()->has('success'))
            <h3 class="text1">{{ session('success') }}</h3>
        @endif

        {{-- Error messages --}}
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

            {{-- Venue Name --}}
            <div class="form-group">
                <label for="name">Venue Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            {{-- Category --}}
            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected':'' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- City --}}
            <div class="form-group">
                <label for="city_id">City</label>
                <select name="city_id" id="city_id" required>
                    <option value="">-- Select City --</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected':'' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" required>
                    <option value="available" {{ old('status')=='available' ? 'selected':'' }}>Available</option>
                    <option value="booked" {{ old('status')=='booked' ? 'selected':'' }}>Booked</option>
                </select>
            </div>

            {{-- Address --}}
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" required>
            </div>

            {{-- Price --}}
            <div class="form-group">
                <label for="price">Price (Rp)</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" required>
            </div>

            {{-- Capacity --}}
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}" required>
            </div>

            {{-- Facilities --}}
            <div class="form-group">
                <label>Facilities</label>
                <div style="display:flex; flex-wrap: wrap; gap:12px; margin-top:8px;">
                    @foreach($facilities as $facility)
                        <label style="display:flex; align-items:center; gap:8px; padding:8px 10px; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer;">
                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                {{ in_array($facility->id, old('facilities', [])) ? 'checked' : '' }}>
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

            {{-- Images --}}
            <div class="form-group">
                <label for="images">Upload Images</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*">
                <small style="color:#6b7280;">Bisa pilih lebih dari 1 gambar</small>
            </div>

            <button type="submit" class="btn-submit">Save Venue</button>
        </form>
    </div>
</body>
</html>
