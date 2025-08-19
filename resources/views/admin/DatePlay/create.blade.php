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
    <!-- <div class="container">
        <h1>Date Available</h1>
      @if(session()->has('succes'))
        <h3 class='text1'>{{session()->get('succes')}}</h3>
      @endif
      
        <form action='/dateplay/create' method="POST">
            @csrf

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="timestamp" id="Time" name="start_time" required class='mt-2'>
            </div>
          
            <button type="submit" class="btn-submit">Simpan Data</button>
        </form>
    </div> -->

<div class="container">
 <form action="{{route('create.date')}}" method="POST">
    @csrf
    <div class="form-group">
        <label for="Tanggal">Tanggal dan Waktu</label>
        <input type="date" 
               id="Tanggal" 
               name="Tanggal" 
               required 
               class="form-control"
               value="{{ old('Tanggal') }}">
    </div>
    <button type="submit" class="btn-submit">Simpan Data</button>
</form>
    </div>
</body>
</html>