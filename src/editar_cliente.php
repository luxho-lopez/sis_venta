<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['direccion']) || empty($_POST['colonia']) || empty($_POST['ciudad'])  || empty($_FILES['archivo'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idcliente = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $colonia = $_POST['colonia'];
        $ciudad = $_POST['ciudad'];
        $area = $_POST['area'];
        $ayuntamiento = $_POST['ayuntamiento'];
        $nom_archivo = $_FILES['archivo']['name'];
        $temp_archivo = $_FILES['archivo']['tmp_name'];
        $ruta = "../assets/img/".$nom_archivo;
    
        move_uploaded_file($temp_archivo,$ruta);

        $sql_update = mysqli_query($conexion, "UPDATE cliente SET nombre = '$nombre', apellido = '$apellido', telefono = '$telefono', direccion = '$direccion', colonia = '$colonia', ciudad = '$ciudad', idarea = '$area', idayuntamiento = '$ayuntamiento', ce_inverso = '$nom_archivo' WHERE idcliente = $idcliente");

        if ($sql_update) {
            $alert = '<div class="alert alert-success" role="alert">Cliente Actualizado correctamente</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Cliente</div>';
        }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: clientes.php");
}
$idcliente = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: clientes.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idcliente = $data['idcliente'];
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];
        $colonia = $data['colonia'];
        $ciudad = $data['ciudad'];
        $area = $data['idarea'];
        $ayuntamiento = $data['idayuntamiento'];
        $inverso = $data['ce_inverso'];
        // $reverso = $data['ce_reverso'];

    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Cliente
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" placeholder="Ingrese Nombre" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" placeholder="Ingrese apellido" name="apellido" class="form-control" id="apellido" value="<?php echo $apellido; ?>">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="number" placeholder="Ingrese Teléfono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" placeholder="Ingrese Direccion" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>">
                        </div>
                        <div class="form-group">
                            <label for="colonia">Colonia</label>
                            <input type="text" placeholder="Ingrese colonia" name="colonia" class="form-control" id="colonia" value="<?php echo $colonia; ?>">
                        </div>
                        <div class="form-group">
                            <label for="ciudad">Ciudad</label>
                            <input type="text" placeholder="Ingrese ciudad" name="ciudad" class="form-control" id="ciudad" value="<?php echo $ciudad; ?>">
                        </div>
                        <div class="form-group">
                            <label for="area">Area de trabajo</label>
                            <input type="text" placeholder="Ingrese Área de Trabajo" name="area" class="form-control" id="area" value="<?php echo $area; ?>">
                        </div>
                        <div class="form-group">
                            <label for="ayuntamiento">Ayuntamiento</label>
                            <input type="text" placeholder="Ingrese Ayuntamiento" name="ayuntamiento" class="form-control" id="ayuntamiento" value="<?php echo $ayuntamiento; ?>">
                        </div>

                        <label class="form-group" for="archivo">Identificacion Oficial - Inverso
                            <input type="file" class="form-control" id="archivo" name="archivo" multiple>
                        </label>
                        <!-- <label class="form-group" for="reverso">Identificacion Oficial - Reverso
                            <input type="file" class="form-control" id="reverso" name="reverso" multiple>
                        </label> -->

                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Cliente</button>
                        <a href="clientes.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>