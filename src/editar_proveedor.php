<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "proveedor";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  header("Location: permisos.php");
}
if (!empty($_POST)) {
  $alert = "";
  if (empty($_POST['rfc']) || empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['sucursal']) || empty($_POST['cuenta']) || empty($_POST['referencia'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $codproveedor = $_GET['id'];
    $rfc = $_POST['rfc'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $sucursal = $_POST['sucursal'];
    $cuenta = $_POST['cuenta'];
    $referencia = $_POST['referencia'];
    $query_update = mysqli_query($conexion, "UPDATE proveedor SET prov_rfc = '$rfc', prov_name = '$nombre', prov_phone = '$telefono', prov_address = '$direccion', suc_bank = '$sucursal', account_bank = '$cuenta', ref_bank = '$referencia' WHERE codproveedor = $codproveedor");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Proveedor Modificado
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
  header("Location: proveedor.php");
} else {
  $id_proveedor = $_REQUEST['id'];
  if (!is_numeric($id_proveedor)) {
    header("Location: proveedor.php");
  }
  $query_proveedor = mysqli_query($conexion, "SELECT * FROM proveedor WHERE codproveedor = $id_proveedor");
  $result_proveedor = mysqli_num_rows($query_proveedor);

  if ($result_proveedor > 0) {
    $data_proveedor = mysqli_fetch_assoc($query_proveedor);
  } else {
    header("Location: proveedor.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar Proveedor
      </div>
      <div class="card-body">
        <form action="" method="post">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="rfc">RFC</label>
            <input type="text" placeholder="Ingrese el RFC del proveecor" name="rfc" id="rfc" class="form-control" value="<?php echo $data_proveedor['prov_rfc']; ?>">
          </div>
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" placeholder="Ingrese nombre del proveedor" name="nombre" id="nombre" class="form-control" value="<?php echo $data_proveedor['prov_name']; ?>">
          </div>
          <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" placeholder="Ingrese numero telefónico" class="form-control" name="telefono" id="telefono" value="<?php echo $data_proveedor['prov_phone']; ?>">
          </div>
          <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" placeholder="Ingrese dirección del proveedor" class="form-control" name="direccion" id="direccion" value="<?php echo $data_proveedor['prov_address']; ?>">
          </div>
          <div class="form-group">
            <label for="sucursal">Sucursal</label>
            <input type="number" placeholder="Ingrese Sucursal bancaria del proveedor" class="form-control" name="sucursal" id="sucursal" value="<?php echo $data_proveedor['suc_bank']; ?>">
          </div>
          <div class="form-group">
            <label for="cuenta">Cuenta</label>
            <input type="number" placeholder="Ingrese Cuenta bancaria del proveedor" class="form-control" name="cuenta" id="cuenta" value="<?php echo $data_proveedor['account_bank']; ?>">
          </div>
          <div class="form-group">
            <label for="referencia">Referencia</label>
            <input type="number" placeholder="Ingrese Referencia bancaria del proveedor" class="form-control" name="referencia" id="referencia" value="<?php echo $data_proveedor['ref_bank']; ?>">
          </div>
          <input type="submit" value="Actualizar Proveedor" class="btn btn-primary">
          <a href="proveedor.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>