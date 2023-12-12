<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Makanan</title>
</head>

<body>
  <h1>Menu Makanan</h1>

  <?php
  // Langkah 1: Menghubungkan ke Database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "bufina";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Periksa koneksi
  if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
  }

  // Langkah 2: Membuat Database
  $sql_create_db = "CREATE DATABASE IF NOT EXISTS namadatabase";
  if ($conn->query($sql_create_db) === TRUE) {
    echo "Database berhasil dibuat atau sudah ada.<br>";
  } else {
    echo "Error: " . $conn->error . "<br>";
  }

  // Pilih database
  $conn->select_db($dbname);

  // Langkah 3: Membuat Tabel
  $sql_create_table = "CREATE TABLE IF NOT EXISTS menu_makanan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_menu VARCHAR(255) NOT NULL,
        harga DECIMAL(10, 2) NOT NULL
    )";
  if ($conn->query($sql_create_table) === TRUE) {
    echo "Tabel berhasil dibuat atau sudah ada.<br>";
  } else {
    echo "Error: " . $conn->error . "<br>";
  }

  // Langkah 4: Menambahkan Data ke Tabel
  $sql_insert_data = "INSERT INTO menu_makanan (nama_menu, harga) VALUES 
        ('Nasi Goreng', 15000),
        ('Mie Goreng', 12000),
        ('Ayam Bakar', 25000)";
  if ($conn->query($sql_insert_data) === TRUE) {
    echo "Data berhasil ditambahkan ke tabel.<br>";
  } else {
    echo "Error: " . $conn->error . "<br>";
  }
  // Langkah 5: Memperbarui Data di Tabel
  if (isset($_POST['update'])) {
    $menu_id = $_POST['menu_id'];
    $new_price = $_POST['new_price'];

    // Hindari SQL injection dengan menggunakan prepared statement
    $sql_update_data = "UPDATE menu_makanan SET harga = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update_data);
    $stmt->bind_param("di", $new_price, $menu_id);

    if ($stmt->execute()) {
      echo "Data berhasil diperbarui.<br>";
    } else {
      echo "Error: " . $stmt->error . "<br>";
    }

    $stmt->close();
  }

  // Menampilkan Data dari Tabel
  $sql_select_data = "SELECT * FROM menu_makanan";
  $result = $conn->query($sql_select_data);

  if ($result->num_rows > 0) {
    echo "<h2>Daftar Menu Makanan:</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Action</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
      echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["nama_menu"] . "</td>
                <td>" . $row["harga"] . "</td>
                <td>
                    <form method='post' action=''>
                        <input type='hidden' name='menu_id' value='" . $row["id"] . "'>
                        <input type='number' name='new_price' placeholder='New Price'>
                        <input type='submit' name='update' value='Update'>
                    </form>
                </td>
            </tr>";
    }

    echo "</table>";
  } else {
    echo "Tidak ada data dalam tabel.";
  }

  // Tutup koneksi
  $conn->close();
  ?>

</body>

</html>