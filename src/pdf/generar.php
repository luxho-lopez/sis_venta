<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';
$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 12);
$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion, p.codigo FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");
$pdf->Cell(195, 35, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->Image("../../assets/img/logo1.png", 10, 5, 50, 30, 'PNG');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(176, 5, utf8_decode($datos['direccion']), 0, 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, utf8_decode($datos['email']), 0, 1, 'L');
$pdf->Ln();


// DATOS DEL CLIENTE

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(102, 184, 106);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(196, 6, "Datos del cliente", 1, 1, 'C', 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(126, 5, utf8_decode('Nombre'), 0, 0);
$pdf->Cell(80, 5, utf8_decode('Teléfono'), 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(126, 5, utf8_decode($datosC['nombre'] . ' ' . $datosC['apellido']), 0, 0);
$pdf->Cell(80, 5, utf8_decode($datosC['telefono']), 0, 0, 'L');
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(176, 5, utf8_decode($datosC['direccion']), 0, 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Colonia: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(176, 5, utf8_decode($datosC['colonia']), 0, 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Ciudad: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(176, 5, utf8_decode($datosC['ciudad']), 0, 1);

// FIN DATOS DEL CLIENTE


$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(196, 6, "Detalles del Producto", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(14, 5, utf8_decode('N°'), 0, 0, 'L');
$pdf->Cell(85, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(25, 5, 'Codigo', 0, 0, 'C');
$pdf->Cell(25, 5, 'Cantidad', 0, 0, 'C');
$pdf->Cell(22, 5, 'Precio', 0, 0, 'C');
$pdf->Cell(25, 5, 'Sub Total.', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(1);


$contador = 1;
while ($row = mysqli_fetch_assoc($ventas)) {
    $pdf->Cell(14, 5, $contador, 0, 0, 'L');
    
    $pdf->Cell(85, 5, $row['descripcion'], 0, 0, 'L' );
    // En este van los numero de series. le puce cantidad para que no muestre error
    $pdf->Cell(25, 5, $row['codigo'], 1, 0, 'L' );
    
    $pdf->Cell(25, 5, $row['cantidad'], 0, 0, 'C');
    $pdf->Cell(22, 5, $row['precio'], 0, 0, 'R');
    $pdf->Cell(25, 5, number_format($row['cantidad'] * $row['precio'], 2, '.', ','), 0, 1, 'R');
    $pdf->Ln();
    $contador++;
}
$pdf->Output("ventas.pdf", "I");

?>
