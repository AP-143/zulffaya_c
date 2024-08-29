<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            color: #ecf0f1;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: #bdc3c7;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
            color: #ecf0f1;
        }

        .container {
            flex: 1;
            padding: 40px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            border-radius: 8px;
        }

        h1,
        h2 {
            color: #2c3e50;
        }

        .center-text {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #ecf0f1;
        }

        button,
        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover,
        input[type="submit"]:hover {
            background-color: #1c5980;
        }

        form div {
            margin-bottom: 15px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Menu</h2>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.orders.index') }}">
            Orders
        </a>
        <a href="{{ route('admin.orders.shipments') }}">Shipments</a>
    </div>
    <div class="container">

        <!-- Form untuk menambahkan produk -->
        <h2>Tambah Produk</h2>
        <form id="addProductForm" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="name">Nama Produk:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="price">Harga (Rp):</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div>
                <label for="stock">Stok:</label>
                <input type="number" id="stock" name="stock" required>
            </div>
            <div>
                <label for="image">Gambar Produk:</label>
                <input type="file" id="image" name="image" required>
            </div>
            <input type="submit" value="Tambah Produk">
        </form>

        <!-- Daftar produk -->
        <h2 class="center-text">Daftar Produk</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Harga (Rp)</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                @foreach($products as $product)
                <tr id="product-{{ $product->id }}">
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @php
                        $imagePath = asset('storage/' . $product->image);
                        Log::info('Image Path: ' . $imagePath);
                        @endphp
                        <img src="{{ $imagePath }}" alt="{{ $product->name }}" width="50">
                    </td>
                    <td>
                        <!-- Form untuk menghapus produk -->
                        <button class="deleteProductBtn" data-id="{{ $product->id }}">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#addProductForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.products.store") }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Tambahkan produk baru ke tabel
                        var newProduct = `
        <tr id="product-${response.id}">
            <td>${response.id}</td>
            <td>${response.name}</td>
            <td>${response.price}</td>
            <td>${response.stock}</td>
            <td><img src="{{ asset('storage/') }}/${response.image}" alt="${response.name}" width="50"></td>
            <td>
                <button class="deleteProductBtn" data-id="${response.id}">Hapus</button>
            </td>
        </tr>
    `;
                        $('#productTableBody').append(newProduct);
                        $('#addProductForm')[0].reset();
                    },
                    error: function(response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            });
            // Event handler untuk menghapus produk
            $(document).on('click', '.deleteProductBtn', function() {
                var productId = $(this).data('id');
                var row = $(this).closest('tr');

                $.ajax({
                    type: 'POST',
                    url: '{{ url("admin/products") }}/' + productId,
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        row.remove();
                    },
                    error: function(response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            });
        });
    </script>
</body>

</html>