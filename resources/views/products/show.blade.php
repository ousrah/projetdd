<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .product-detail {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .product-detail p {
            margin: 10px 0;
            font-size: 16px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .price {
            font-size: 24px;
            color: #2ecc71;
            font-weight: bold;
        }
        .status {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 14px;
        }
        .active {
            background-color: #d4edda;
            color: #155724;
        }
        .inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Product Details</h1>
    
    <div class="product-detail">
        <p><span class="label">Name:</span> {{ $product->name }}</p>
        <p><span class="label">Description:</span> {{ $product->description }}</p>
        <p><span class="label">Price:</span> <span class="price">${{ number_format($product->price, 2) }}</span></p>
        <p><span class="label">Stock:</span> {{ $product->stock }} units</p>
        <p><span class="label">Status:</span> 
            <span class="status {{ $product->is_active ? 'active' : 'inactive' }}">
                {{ $product->is_active ? 'Active' : 'Inactive' }}
            </span>
        </p>
        <p><span class="label">Category:</span> {{ $product->category->name }}</p>
    </div>
    
    <a href="{{ route('products.index') }}" class="back-link">← Back to Products</a>
</body>
</html>
