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
  if (empty($_POST['nombre']) || empty($_FILES['archivo'])) {
    $alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
  } else {
    $marca = $_GET['id'];
    $nombre = $_POST['nombre'];
    $nom_archivo = $_FILES['archivo']['name'];
    $temp_archivo = $_FILES['archivo']['tmp_name'];
    $ruta = "../assets/img/".$nom_archivo;

    move_uploaded_file($temp_archivo,$ruta);
    
    $query_update = mysqli_query($conexion, "UPDATE marcas SET marca = '$nombre', logo = '$nom_archivo' WHERE id_marca = $marca");
    if ($query_update) {
      $alert = '<div class="alert alert-primary" role="alert">
              Marca Modificada
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
  header("Location: marcas.php");
} else {
  $id_marca = $_REQUEST['id'];
  if (!is_numeric($id_marca)) {
    header("Location: marcas.php");
  }
  $query_marca = mysqli_query($conexion, "SELECT * FROM marcas WHERE id_marca = $id_marca");
  $result_marca = mysqli_num_rows($query_marca);

  if ($result_marca > 0) {
    $data_marca = mysqli_fetch_assoc($query_marca);
  } else {
    header("Location: marcas.php");
  }
}
?>
<div class="row">
  <div class="col-lg-6 m-auto">

    <div class="card">
      <div class="card-header bg-primary text-white">
        Modificar Marca
      </div>
      <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
          <?php echo isset($alert) ? $alert : ''; ?>
          <div class="form-group">
            <label for="nombre">Marca</label>
            <input type="text" placeholder="Nombre de la marca" name="nombre" id="nombre" class="form-control" value="<?php echo $data_marca['marca']; ?>">
          </div>

          <label class="form-group" for="logotipo">Logotipo
            <input type="file" class="form-control" id="archivo" name="archivo" multiple>
          </label>
          <br>
          <input type="submit" value="Actualizar Marca" class="btn btn-primary">
          <a href="marcas.php" class="btn btn-danger">Atras</a>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once "includes/footer.php"; ?>