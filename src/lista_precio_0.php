<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "lista_precio";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $iva = 1.16;
    $chaz = 0.86;
    $proveedor = $_POST['proveedor'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $precio_iva = $precio * $iva;
    $chaz_chaz = $precio_iva / $chaz;
    $activo = $_POST['activo'];
    $usuario_id = $_SESSION['idUser'];
    $alert = "";
    if (empty($proveedor) || empty($marca) || empty($modelo) || empty($producto) || empty($precio) || $precio <  0 || $activo < 0) {
        $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
    } else {
        $query = mysqli_query($conexion, "SELECT * FROM listadeprecio WHERE modelo = '$modelo'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
                        El modelo ya existe
                    </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO listadeprecio(id_proveedor,id_marca,modelo,descripcion_producto,precio_compra,precio_iva,chaz_chaz,estado) values ('$proveedor', '$marca', '$modelo', '$producto', '$precio', '$precio_iva', '$chaz_chaz','$activo')");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                Producto Registrado
              </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el producto
              </div>';
            }
        }
    }
}
?>
<br>
<br>
<!-- <a href="lista_precio_0.php"><button class="btn btn-info mb-2" type="button"><i class="fas fa-audio-description"></i></button></a> -->
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <!-- <th>#</th> -->
                <th>Proveedor</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Producto</th>
                <th>Compra</th>
                <th>IVA</th>
                <th>Chaz</th>
                <th></th>
                <th>Opción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM listadeprecio WHERE estado = 0");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    if ($data['estado'] == 1) {
                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                    } else {
                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                    }
            ?>
                    <tr>

                        <td><?php echo $data['id_proveedor']; ?></td>
                        <td><?php echo $data['id_marca']; ?></td>
                        <td><?php echo $data['modelo']; ?></td>
                        <td><?php echo $data['descripcion_producto']; ?></td>
                        <td><?php echo $data['precio_compra']; ?></td>
                        <td><?php echo $data['precio_iva']; ?></td>
                        <td><?php echo $data['chaz_chaz']; ?></td>
                        <td><?php echo $estado ?></td>
                        <td>
                            <?php if ($data['estado'] == 0) { ?>

                                <a href="editar_precio.php?id=<?php echo $data['idlistaprecio']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>

                                <!-- <form action="eliminar_precio.php?id=<?php echo $data['idlistaprecio']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form> -->
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>

    </table>
</div>
<div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Producto</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="proveedor">Proveedor</label>
                        <input type="text" placeholder="Proveedor" name="proveedor" id="proveedor" class="form-control" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <input type="text" placeholder="Marca" name="marca" id="marca" class="form-control" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo</label>
                        <input type="text" placeholder="Ingrese el Modelo del producto" name="modelo" id="modelo" class="form-control" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <input type="text" placeholder="Ingrese descripción del producto" name="producto" id="producto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio de compra</label>
                        <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                    </div>
                    <div class="form-group">
                        <label for="activo">Activo</label>
                        <input type="number" placeholder="1 | 0" class="form-control" name="activo" id="activo">
                    </div>
                    <input type="submit" value="Guardar Producto" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>