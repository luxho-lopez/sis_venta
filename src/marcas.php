 <?php include_once "includes/header.php";
    include "../conexion.php";
    $id_user = $_SESSION['idUser'];
    $permiso = "marcas";
    $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
    $existe = mysqli_fetch_all($sql);
    if (empty($existe) && $id_user != 1) {
        header("Location: permisos.php");
    }
    if (!empty($_POST)) {
        $nombre = $_POST['nombre'];
        $nom_archivo = $_FILES['archivo']['name'];
        $temp_archivo = $_FILES['archivo']['tmp_name'];
        $ruta = "../assets/img/".$nom_archivo;

        move_uploaded_file($temp_archivo,$ruta);


        $usuario_id = $_SESSION['idUser'];
        $alert = "";
        if (empty($nombre) || empty($nom_archivo)) {
            $alert = '<div class="alert alert-danger" role="alert">
                Todo los campos son obligatorios
              </div>';
        } else {
            $query = mysqli_query($conexion, "SELECT * FROM marcas WHERE marca = '$nombre'");
            $result = mysqli_fetch_array($query);
            if ($result > 0) {
                $alert = '<div class="alert alert-warning" role="alert">
                        La marca ya esta registrada
                    </div>';
            } else {
                $query_insert = mysqli_query($conexion, "INSERT INTO marcas(marca,logo) values ('$nombre', '$nom_archivo')");
                if ($query_insert) {
                    $alert = '<div class="alert alert-success" role="alert">
                Marca Registrada
                </div>';
                } else {
                    $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar la marca
                </div>';
                }
            }
        }
    }
    ?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nueva_marca"><i class="fas fa-plus"></i></button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <!-- <th>#</th> -->
                <th>Marca</th>
                <th>Logotipo</th>
                <!-- <th>Estado</th> -->
                <th> </th>
            </tr>
        </thead>
        <tbody>
            <?php
                include "../conexion.php";
    
                $query = mysqli_query($conexion, "SELECT * FROM marcas");
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

                        <td><?php echo $data['marca']; ?></td>
                        <td><img src="../assets/img/<?php echo $data['logo']; ?>" alt="Logotipo" height="50"></td>

                        <!-- <td><?php echo $estado ?></td> -->
                        <td>
                            <?php if ($data['estado'] == 1) { ?>

                                <a href="editar_marca.php?id=<?php echo $data['id_marca']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>

                                <form action="eliminar_marca.php?id=<?php echo $data['id_marca']; ?>" method="post" class="confirmar d-inline">
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
<div id="nueva_marca" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nueva Marca</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre">Marca</label>
                        <input type="text" placeholder="Nombre de la marca" name="nombre" id="nombre" class="form-control">
                    </div>

                    <label class="form-group" for="logotipo">Logotipo
                        <input type="file" class="form-control" id="archivo" name="archivo" multiple>
                    </label>
                    <br>
                    <input type="submit" value="Guardar Marca" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>