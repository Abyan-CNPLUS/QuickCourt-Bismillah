<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Venue</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            color: #1f2937;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            color: #4f46e5;
            text-align: center;
            margin-bottom: 25px;
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; }
        input[type="text"], input[type="number"], select, textarea {
            width: 100%; padding: 10px; border: 1px solid #d1d5db;
            border-radius: 5px; font-size: 16px;
        }
        .btn-submit {
            background-color: #4f46e5; color: white; border: none;
            padding: 12px 20px; border-radius: 5px; cursor: pointer;
            font-size: 16px; width: 100%; transition: background-color 0.3s;
        }
        .btn-submit:hover { background-color: #4338ca; }
        .checkbox-list label { font-weight: normal; display: block; margin-bottom: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Venue</h1>

        {{-- Pesan sukses --}}
        @if(session()->has('success'))
            <div style="background:#d1fae5;color:#065f46;padding:10px;border-radius:6px;margin-bottom:15px;">
                {{ session()->get('success') }}
            </div>
        @endif

        {{-- Pesan error --}}
        @if ($errors->any())
            <div style="background:#fee2e2;color:#b91c1c;padding:10px;border-radius:6px;margin-bottom:15px;">
                <strong>Periksa input kamu:</strong>
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.venues.update', $venue->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Venue Name</label>
                <input type="text" name="name" value="{{ old('name',$venue->name) }}" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $venue->category_id == $cat->id ? 'selected':'' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>City</label>
                <select name="city_id" required>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ $venue->city_id == $city->id ? 'selected':'' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="available" {{ $venue->status == 'available' ? 'selected':'' }}>Available</option>
                    <option value="booked" {{ $venue->status == 'booked' ? 'selected':'' }}>Booked</option>
                </select>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address',$venue->address) }}" required>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" value="{{ old('price',$venue->price) }}" required>
            </div>

            <div class="form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" value="{{ old('capacity',$venue->capacity) }}" required>
            </div>

            <div class="form-group">
                <label>Facilities</label>
                <div class="checkbox-list">
                    @foreach($facilities as $f)
                        <label>
                            <input type="checkbox" name="facilities[]" value="{{ $f->id }}"
                                {{ $venue->facilities->contains($f->id) ? 'checked':'' }}>
                            {{ $f->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn-submit">Update Venue</button>
        </form>
    </div>
</body>
</html>
