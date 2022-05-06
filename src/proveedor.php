 <?php include_once "includes/header.php";
    include "../conexion.php";
    $id_user = $_SESSION['idUser'];
    $permiso = "proveedor";
    $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
    $existe = mysqli_fetch_all($sql);
    if (empty($existe) && $id_user != 1) {
        header("Location: permisos.php");
    }
    if (!empty($_POST)) {
        $rfc = $_POST['rfc'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $sucursal = $_POST['sucursal'];
        $cuenta = $_POST['cuenta'];
        $referencia = $_POST['referencia'];
        $usuario_id = $_SESSION['idUser'];
        $alert = "";
        if (empty($rfc) || empty($nombre) || empty($telefono) || empty($direccion) || empty($sucursal) || $sucursal <  0 || empty($cuenta) || $cuenta < 0 || empty($referencia) || $referencia < 0) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM proveedor WHERE prov_rfc = '$rfc'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        El código ya existe
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO proveedor(prov_rfc,prov_name,prov_phone,prov_address,suc_bank,account_bank,ref_bank) values ('$rfc', '$nombre', '$telefono','$direccion','$sucursal','$cuenta','$referencia')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Proveedor Registrado
              </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el proveedor
              </div>';
                }
            }
        }
    }
    ?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_proveedor"><i class="fas fa-plus"></i></button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <!-- <th>#</th> -->
                <th>RFC</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Suc. Banco</th>
                <th>Cuenta</th>
                <th>Referencia</th>
                <!-- <th>Estado</th> -->
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT * FROM proveedor");
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

                        <td><?php echo $data['prov_rfc']; ?></td>
                        <td><?php echo $data['prov_name']; ?></td>
                        <td><?php echo $data['prov_phone']; ?></td>
                        <td><?php echo $data['prov_address']; ?></td>
                        <td><?php echo $data['suc_bank']; ?></td>
                        <td><?php echo $data['account_bank']; ?></td>
                        <td><?php echo $data['ref_bank']; ?></td>
                        <!-- <td><?php echo $estado ?></td> -->
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                
                                <a href="editar_proveedor.php?id=<?php echo $data['codproveedor']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                <form action="eliminar_proveedor.php?id=<?php echo $data['codproveedor']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>

    </table>
</div>
<div id="nuevo_proveedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Proveedor</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="rfc">RFC</label>
                        <input type="text" placeholder="Ingrese el RFC del proveecor" name="rfc" id="rfc" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" placeholder="Ingrese nombre del proveedor" name="nombre" id="nombre" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" placeholder="Ingrese numero telefónico" class="form-control" name="telefono" id="telefono">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" placeholder="Ingrese dirección del proveedor" class="form-control" name="direccion" id="direccion">
                    </div>
                    <div class="form-group">
                        <label for="sucursal">Sucursal</label>
                        <input type="number" placeholder="Ingrese Sucursal bancaria del proveedor" class="form-control" name="sucursal" id="sucursal">
                    </div>
                    <div class="form-group">
                        <label for="cuenta">Cuenta</label>
                        <input type="number" placeholder="Ingrese Cuenta bancaria del proveedor" class="form-control" name="cuenta" id="cuenta">
                    </div>
                    <div class="form-group">
                        <label for="referencia">Referencia</label>
                        <input type="number" placeholder="Ingrese Referencia bancaria del proveedor" class="form-control" name="referencia" id="referencia">
                    </div>
                    <input type="submit" value="Guardar Proveedor" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>