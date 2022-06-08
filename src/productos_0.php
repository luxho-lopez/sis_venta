 <?php include_once "includes/header.php";
    include "../conexion.php";
    $id_user = $_SESSION['idUser'];
    $permiso = "productos";
    $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
    $existe = mysqli_fetch_all($sql);
    if (empty($existe) && $id_user != 1) {
        header("Location: permisos.php");
    }

    ?>
    <br><br>
<!-- <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_producto"><i class="fas fa-plus"></i></button> -->
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
                <th>Opción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE existencia = 0");
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
                        <!-- <td><?php echo $estado ?></td> -->
                        <td>
                            <?php if ($data['existencia'] == 0) { ?>
                                <a href="agregar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-audio-description'></i></a>

                                <!-- <a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>

                                <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button> -->
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>

    </table>
</div>


<?php include_once "includes/footer.php"; ?>