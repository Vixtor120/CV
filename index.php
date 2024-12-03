<?php
// Activa la visualización de errores para depuración
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
include('conexion.php');

// Conexión a la base de datos
$conn = connectPostgreSQL();

// Obtener los datos del CV
$cv_info = getCVInfoPostgreSQL($conn);

// Cerrar la conexión
pg_close($conn);

// Comprobación para asegurarse de que los datos están disponibles
if (!$cv_info) {
    die('No se pudo obtener la información del CV');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Victor Hidalgo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header {
            background-color: #007bff;
        }
        .card-body h4 {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .experience-item, .education-item, .skill-item, .language-item {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3>Currículum Vitae</h3>
            </div>
            <div class="card-body">
                <h4>Información Personal</h4>
                <p><strong>Nombre:</strong> <?php echo $cv_info['name']; ?></p>
                <p><strong>Profesión:</strong> <?php echo $cv_info['profession']; ?></p>
                <p><strong>Correo Electrónico:</strong> <?php echo $cv_info['email']; ?></p>

                <h4>Experiencia</h4>
                <?php if (isset($cv_info['experiences']) && !empty($cv_info['experiences'])): ?>
                    <div class="list-group">
                        <?php foreach ($cv_info['experiences'] as $experience): ?>
                            <div class="list-group-item experience-item">
                                <h5 class="mb-1"><?php echo $experience['role']; ?> en <?php echo $experience['company']; ?></h5>
                                <p class="mb-1"><?php echo $experience['description']; ?></p>
                                <small><?php echo $experience['start_date']; ?> - <?php echo $experience['end_date']; ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No hay experiencias registradas.</p>
                <?php endif; ?>

                <h4>Educación</h4>
                <?php if (isset($cv_info['education']) && !empty($cv_info['education'])): ?>
                    <div class="list-group">
                        <?php foreach ($cv_info['education'] as $education): ?>
                            <div class="list-group-item education-item">
                                <h5 class="mb-1"><?php echo $education['degree']; ?> en <?php echo $education['institution']; ?></h5>
                                <small><?php echo $education['start_year']; ?> - <?php echo $education['end_year']; ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No hay educación registrada.</p>
                <?php endif; ?>

                <h4>Habilidades</h4>
                <?php if (isset($cv_info['skills']) && !empty($cv_info['skills'])): ?>
                    <ul class="list-group">
                        <?php foreach ($cv_info['skills'] as $skill): ?>
                            <li class="list-group-item skill-item"><?php echo $skill['skill']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No hay habilidades registradas.</p>
                <?php endif; ?>

                <h4>Idiomas</h4>
                <?php if (isset($cv_info['languages']) && !empty($cv_info['languages'])): ?>
                    <ul class="list-group">
                        <?php foreach ($cv_info['languages'] as $language): ?>
                            <li class="list-group-item language-item"><?php echo $language['language']; ?> - <?php echo $language['level']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No hay idiomas registrados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
