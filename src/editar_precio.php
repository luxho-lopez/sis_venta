<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header("Location: permisos.php");
}
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['proveedor']) || empty($_POST['marca']) || empty($_POST['modelo']) || empty($_POST['producto']) || empty($_POST['precio'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $iva = 1.16;
    $chaz = 0.86;
    $idlistaprecio = $_GET['id'];
    $proveedor = $_POST['proveedor'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $precio_iva = $precio * $iva;
    $chaz_chaz = $precio_iva / $chaz;
    $estado = $_POST['estado'];
    $query_update = mysqli_query($conexion, "UPDATE listadeprecio SET id_proveedor = '$proveedor', id_marca = '$marca', modelo = '$modelo', descripcion_producto = '$producto', precio_compra = '$precio', precio_iva = '$precio_iva', chaz_chaz = '$chaz_chaz', estado = '$estado'  WHERE idlistaprecio = $idlistaprecio");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Producto Modificado
            </div>';
    } else {
      $alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
    }
  }
}

// Validar producto

if (empty($_REQUEST['id'])) {
  header("Location: lista_precio.php");
} else {
  $id_producto = $_REQUEST['id'];
  if (!is_numeric($id_producto)) {
    header("Location: lista_precio.php");
  }
  $query_producto = mysqli_query($conexion, "SELECT * FROM listadeprecio WHERE idlistaprecio = $id_producto");
  $result_producto = mysqli_num_rows($query_producto);

  if ($result_producto > 0) {
    $data_producto = mysqli_fetch_assoc($query_producto);
  } else {
    header("Location: lista_precio.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar producto
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="proveedor">Proveedor</label>
            <input type="text" placeholder=" " name="proveedor" id="proveedor" class="form-control" value="<?php echo $data_producto['id_proveedor']; ?>">
          </div>
          <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" placeholder=" " name="marca" id="marca" class="form-control" value="<?php echo $data_producto['id_marca']; ?>">
          </div>
          <div class="form-group">
            <label for="modelo">Modelo</label>
            <input type="text" placeholder="Ingrese modelo del producto" name="modelo" id="modelo" class="form-control" value="<?php echo $data_producto['modelo']; ?>">
          </div>
          <div class="form-group">
            <label for="producto">Producto</label>
            <input type="text" class="form-control" placeholder="Ingrese nombre del producto" name="producto" id="producto" value="<?php echo $data_producto['descripcion_producto']; ?>">
          </div>
          <div class="form-group">
            <label for="precio">Precio</label>
            <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio" value="<?php echo $data_producto['precio_compra']; ?>">
          </div>
          <div class="form-group">
            <label for="estado">Para activar coloque 1, para desactivar coloque 0</label>
            <input type="text" placeholder=" " class="form-control" name="estado" id="estado" value="<?php echo $data_producto['estado']; ?>">

          </div>
          <input type="submit" value="Actualizar Producto" class="btn btn-primary">
          <a href="lista_precio.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>