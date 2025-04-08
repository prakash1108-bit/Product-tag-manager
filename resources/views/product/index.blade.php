<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Product List</title>
</head>

<body>

    <div class="container">
        <h2>Products</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Create New Product</a>
        <a href="{{ route('rules.index') }}" class="btn btn-primary">Rules Manager</a>

        @if (session('success'))
            <div class="alert alert-success mt-2" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mt-2" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Vendor</th>
                    <th>Type</th>
                    <th>Tags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->vendor }}</td>
                        <td>{{ $product->type }}</td>
                        <td>{{ $product->tags }}</td>

                        <td>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
