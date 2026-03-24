@forelse($products as $product)
    <tr>
        <td>{{ $product->name }}</td>
        <td>{{ $product->description }}</td>
        <td>${{ number_format($product->price, 2) }}</td>
        <td>{{ $product->stock }}</td>
        <td>
            <span class="{{ $product->is_active ? 'active' : 'inactive' }}">
                {{ $product->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td>{{ $product->category->name }}</td>
        <td>
            <a href="{{ route('products.show', $product->id) }}" class="btn-detail">Detail</a>
            <button type="button" class="btn-edit" onclick="openEditModal({{ json_encode($product) }})">Edit</button>
            <button type="button" class="btn-delete" onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')">Delete</button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7">No products found.</td>
    </tr>
@endforelse
