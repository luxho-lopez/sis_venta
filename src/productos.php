<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $factura = $_POST['factura'];
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $usuario_id = $_SESSION['idUser'];
    $alert = "";
    if (empty($factura) || empty($codigo) || empty($producto) || empty($precio) || $precio <  0 || empty($cantidad) || $cantidad < 0) {
        $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
    } else {
        $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
                        El código ya existe
                    </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,precio,existencia,num_factura,usuario_id) values ('$codigo', '$producto', '$precio','$cantidad','$factura','$usuario_id')");
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
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_producto"><i class="fas fa-plus"></i></button>
<a href="productos_0.php"><button class="btn btn-info mb-2" type="button"><i class="fas fa-audio-description"></i></button></a>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <!-- <th>#</th> -->
                <th></th>
                <th>Código</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Factura</th>
                <th>Fecha</th>
                <th>Opción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE existencia != 0");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    if ($data['existencia'] == 0) {
                        $estado = '<span class="badge badge-pill badge-danger">Inactivo</span>';
                    } else {
                        $estado = '<span class="badge badge-pill badge-success">Activo</span>';
                    }
            ?>
                    <tr>

                        <td><?php echo $data['existencia']; ?></td>
                        <td><?php echo $data['codigo']; ?></td>
                        <td><?php echo $data['descripcion']; ?></td>
                        <td><?php echo $data['precio']; ?></td>
                        <td><?php echo $data['num_factura']; ?></td>
                        <td><?php echo $data['created_at']; ?></td>
                        <!-- <td><?php echo $estado ?></td> -->
                        <td>
                            <?php if ($data['existencia'] != 0) { ?>
                                <a href="agregar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-audio-description'></i></a>

                                <a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>

                                <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
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
                        <label for="factura">Factura</label>
                        <input type="text" placeholder="Numero de factura" name="factura" id="factura" class="form-control" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="codigo">Código</label>
                        <input type="text" placeholder="Ingrese código del producto" name="codigo" id="codigo" class="form-control" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                    </div>
                    <input type="submit" value="Guardar Producto" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>