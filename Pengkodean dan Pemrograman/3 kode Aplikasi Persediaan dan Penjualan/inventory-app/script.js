// Data Produk (Simulasi Database)
const products = [
    { id: 1, name: "Laptop", price: 7000000, stock: 5 },
    { id: 2, name: "Mouse", price: 150000, stock: 10 },
    { id: 3, name: "Keyboard", price: 300000, stock: 7 }
];

const cart = [];

// Menampilkan Daftar Barang
function loadProducts() {
    const productList = document.getElementById("product-list");
    productList.innerHTML = "";

    products.forEach((product, index) => {
        productList.innerHTML += `
            <tr>
                <td>${product.name}</td>
                <td>Rp ${product.price}</td>
                <td>${product.stock}</td>
                <td><button onclick="addToCart(${index})">Beli</button></td>
            </tr>
        `;
    });
}

// Menambahkan Barang ke Keranjang
function addToCart(index) {
    const product = products[index];
    
    if (product.stock > 0) {
        let cartItem = cart.find(item => item.id === product.id);

        if (cartItem) {
            cartItem.quantity++;
        } else {
            cart.push({ ...product, quantity: 1 });
        }

        product.stock--;
        loadProducts();
        loadCart();
    } else {
        alert("Stok habis!");
    }
}

// Menampilkan Keranjang Belanja
function loadCart() {
    const cartList = document.getElementById("cart-list");
    const totalPriceElement = document.getElementById("total-price");
    cartList.innerHTML = "";
    let totalPrice = 0;

    cart.forEach((item, index) => {
        let totalItemPrice = item.price * item.quantity;
        totalPrice += totalItemPrice;

        cartList.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>Rp ${item.price}</td>
                <td>${item.quantity}</td>
                <td>Rp ${totalItemPrice}</td>
                <td><button onclick="removeFromCart(${index})">Hapus</button></td>
            </tr>
        `;
    });

    totalPriceElement.innerText = totalPrice;
}

// Menghapus Barang dari Keranjang
function removeFromCart(index) {
    const item = cart[index];
    const product = products.find(p => p.id === item.id);

    product.stock += item.quantity;
    cart.splice(index, 1);

    loadProducts();
    loadCart();
}

// Checkout (Mengosongkan Keranjang)
document.getElementById("checkout-btn").addEventListener("click", function() {
    if (cart.length === 0) {
        alert("Keranjang kosong!");
        return;
    }

    alert("Pembelian berhasil!");
    cart.length = 0; // Mengosongkan keranjang
    loadCart();
});

loadProducts();
