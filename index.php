<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Milk Tea Shop</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f1f1f1;
    }

    header {
      background-color: #e0e7e9;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
    }

    nav ul li a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }

    .hero {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      padding: 20px;
      background-color: #f7f9fa;
    }

    .hero-text {
      background-color: #e0e7e9;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 500px;
      margin: 20px;
    }

    .hero img {
      width: 300px;
      height: 300px;
      border-radius: 50%;
      margin: 20px;
    }

    /* Milk Tea Product Section */
    .product-section {
      background-color: #f7f7f7;
      text-align: center;
      padding: 40px 20px;
    }

    .product-section h2 {
      font-size: 2.5rem;
      color: #760b2b;
      margin-bottom: 40px;
    }

    .products {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }

    .product {
      background-color: #fff;
      border-radius: 20px;
      padding: 20px;
      width: 280px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
    }

    .product:hover {
      transform: translateY(-5px);
    }

    .product img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-radius: 20px;
    }

    .product h3 {
      color: #760b2b;
      margin: 15px 0 10px;
      font-size: 1.2rem;
      text-transform: capitalize;
    }

    .order-btn {
      background-color: transparent;
      color: #760b2b;
      border: 2px solid #760b2b;
      border-radius: 30px;
      padding: 10px 25px;
      font-weight: bold;
      font-size: 1rem;
      margin-top: 10px;
      cursor: pointer;
      box-shadow: 0 4px 0 #760b2b;
      transition: all 0.3s ease;
    }

    .order-btn:hover {
      background-color: #760b2b;
      color: #fff;
      transform: scale(1.05);
    }

    /* Footer Styles */
    footer {
      background-color: #e0e7e9;
      color:rgb(0, 0, 0);
      padding: 40px 20px 20px 20px;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      flex-wrap: wrap;
      max-width: 1200px;
      margin: auto;
    }

    .footer-left h2 {
      font-size: 2em;
      font-weight: bold;
      margin: 0;
    }

    .footer-center {
      text-align: center;
      flex: 1;
    }

    .footer-center a,
    .footer-center p {
      display: block;
      color: #6b0f1a;
      text-decoration: none;
      margin: 5px 0;
    }

    .footer-center a:hover {
      text-decoration: underline;
      color: #8e2041;
    }

    .footer-right {
      text-align: right;
    }

    .footer-right p {
      margin: 0 0 10px 0;
    }

    .social-icons a {
      margin-left: 10px;
      text-decoration: none;
    }

    .social-icons img {
      width: 24px;
      height: 24px;
      filter: grayscale(100%);
      transition: filter 0.3s ease, transform 0.3s ease;
    }

    .social-icons a:hover img {
      filter: none;
      transform: scale(1.1);
    }

    @media (max-width: 768px) {
      .footer-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .footer-left, .footer-right {
        text-align: center;
        margin: 10px 0;
      }

      .social-icons a {
        margin: 0 5px;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1><div>BOBA GO MILK TEA SHOP</div></h1>
    <nav>
      <ul>
        <li><a href="about_us.php">About us</a></li>
        <li><a href="register.php">Register </a></li>
        <li><a href="login.php">Login</a></li>
      </ul>
    </nav>
  </header>

  <div class="hero">
    <div class="hero-text">
      <h1>BOBA GO MILK TEA SHOP</h1>
      <p>Savor the perfect blend of premium tea, creamy milk, and chewy boba!<br>Customize your drink with flavors, sizes, and sweetness levels. Enjoy fast service and great taste—order online now!</p>
    </div>
    <img src="../image/milk.png" alt="Milk Tea Image">
  </div>

  <section class="product-section">
    <h2>Our Milk Tea</h2>
    <div class="products">
      <div class="product">
        <img src="../image/product1.jpg" alt="Milk Tea Mix">
        <h3>mix</h3>
        <button class="order-btn">Order now</button>
      </div>
      <div class="product">
        <img src="../image/product2.jpg" alt="Milk Tea Gong Cha">
        <h3>gong cha</h3>
        <button class="order-btn">Order now</button>
      </div>
      <div class="product">
        <img src="../image/product3.jpg" alt="Milk Tea Only Milk">
        <h3>milk</h3>
        <button class="order-btn">Order now</button>
      </div>
    </div>
  </section>

  <footer>
    <div class="footer-content">
      <div class="footer-left">
        <h2>bobagomilktea</h2>
      </div>
      
      <div class="footer-right">
        <p>glenalcagula@gmail.com<br>(123) 456-7890</p>
        
      </div>
    </div>
  </footer>
</body>
</html>
