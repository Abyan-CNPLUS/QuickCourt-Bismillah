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
        <h1>Food</h1>
      @if(session()->has('success'))
    <h3 class='text1'>{{session()->get('success')}}</h3>
@endif


      <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required class="mt-2">
            </div>

            <div class="form-group">
                <label for="categories_id">Category</label>
                <select name="categories_id" id="categories_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id}}">{{ $category->name}}</option>
                    @endforeach
                </select>
            </div>
             <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" required class="mt-2">
            </div>

            <div class="form-group">
                <label for="venue_id">Venue</label>
                <select name="venue_id" id="venue_id" class="form-control" required>
                <option value="">-- Select Venue --</option>
                 @foreach($venues as $venue)
                <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                @endforeach
            </select>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <input type="text" id="description" name="description" required class="mt-2">
            </div>
            
            <div class="form-group">
                <label for="image">Upload Image</label>
                <input type="file" id="image" name="image" accept="image/*" class="mt-2" required>
            </div>

            <button type="submit" class="btn-submit">Save Data</button>
        </form>
    </div>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#category_id").on("change", function(){
            var id = $(this).val();

            if (id){
                $.ajax({
                    url: '/menu/' + id,  // ✅ disesuaikan dengan route kamu
                    type: 'GET',
                    dataType: 'json',
                    success: function(data){
                        console.log('✅ DATA:', data);
                    },
                    error: function(xhr, status, error){
                        console.error('❌ AJAX Error:', status, error);
                        console.log(xhr.responseText);
                    }
                });
            } else {
                console.log('⚠️ Tidak ada ID dipilih');
            }
        });
    });
</script>
</body>
</html>
