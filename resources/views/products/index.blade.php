<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
   
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   
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
        .filter-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        select, input {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="text"] {
            flex-grow: 1;
        }
        .btn-search {
            padding: 8px 16px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-search:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        .active {
            color: green;
        }
        .inactive {
            color: red;
        }
        .btn-detail {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-detail:hover {
            background-color: #0056b3;
        }
        .btn-edit {
            padding: 5px 10px;
            background-color: #ffc107;
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .btn-delete {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .btn-add {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .btn-add:hover {
            background-color: #218838;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            max-width: 500px;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .alert-success {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Products</h1>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <button type="button" class="btn-add" onclick="openAddModal()">Add Product</button>

    <div class="filter-form">
        <input type="text" onkeypress="searchProducts(event)" id="searchQuery" placeholder="Search by name..." value="">
        <button type="button" class="btn-search" onclick="executeSearch()">Search</button>
    </div>

    <div class="filter-form">
        <form method="GET" action="{{ route('products.index') }}">
            <label for="category">Filter by Category:</label>
            <select name="category_id" id="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productsTable">
            @include('products.partials._product-row')
        </tbody>
    </table>

    <!-- Add Product Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Product</h2>
                <span class="close" onclick="closeModal('addModal')">&times;</span>
            </div>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="add_name">Name</label>
                    <input type="text" name="name" id="add_name" required>
                </div>
                <div class="form-group">
                    <label for="add_description">Description</label>
                    <textarea name="description" id="add_description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="add_price">Price</label>
                    <input type="number" name="price" id="add_price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="add_stock">Stock</label>
                    <input type="number" name="stock" id="add_stock" required>
                </div>
                <div class="form-group">
                    <label for="add_category">Category</label>
                    <select name="category_id" id="add_category" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" checked> Active
                    </label>
                </div>
                <button type="submit" class="btn-search">Save Product</button>
            </form>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Product</h2>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="edit_name">Name</label>
                    <input type="text" name="name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label for="edit_description">Description</label>
                    <textarea name="description" id="edit_description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_price">Price</label>
                    <input type="number" name="price" id="edit_price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="edit_stock">Stock</label>
                    <input type="number" name="stock" id="edit_stock" required>
                </div>
                <div class="form-group">
                    <label for="edit_category">Category</label>
                    <select name="category_id" id="edit_category" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" id="edit_active" value="1"> Active
                    </label>
                </div>
                <button type="submit" class="btn-search">Update Product</button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Product</h2>
                <span class="close" onclick="closeModal('deleteModal')">&times;</span>
            </div>
            <p>Are you sure you want to delete product <strong id="deleteProductName"></strong>?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn-edit" onclick="closeModal('deleteModal')">Cancel</button>
                    <button type="submit" class="btn-delete">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function executeSearch()
        {
            const query = $('#searchQuery').val();
            const categoryId = $('#category').val();


            // const query = Document.getElementById('searchQuery').value;
            // const categoryId = Document.getElementById('category').value;


            console.log(query, categoryId);
            
            axios.get('{{ route("products.search") }}', {
                params: {
                    q: query,
                    category_id: categoryId
                }
            })
            .then(function (response) {
               // console.log(response.data);
                $('#productsTable').html(response.data);
            })
            .catch(function (error) {
                console.error('Error searching products:', error);
            });
}

        function searchProducts(e) {
      
            if(e.key !== 'Enter') return;
            executeSearch();
        }

        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function openEditModal(product) {
            const form = document.getElementById('editForm');
            form.action = `/products/${product.id}`;
            
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_stock').value = product.stock;
            document.getElementById('edit_category').value = product.category_id;
            document.getElementById('edit_active').checked = product.is_active;
            
            document.getElementById('editModal').style.display = 'block';
        }

        function openDeleteModal(productId, productName) {
            const form = document.getElementById('deleteForm');
            form.action = `/products/${productId}`;
            document.getElementById('deleteProductName').textContent = productName;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
