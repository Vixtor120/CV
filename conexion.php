<?php
// Conexión a PostgreSQL
function connectPostgreSQL() {
    $host = 'localhost';
    $port = '5432';
    $dbname = 'cv_pg_db';
    $user = 'cv_pg_user';
    $password = 'password';
    
    // Establecer conexión
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    // Comprobar conexión
    if (!$conn) {
        die("Conexión fallida: " . pg_last_error());
    }
    return $conn;
}

// Función para obtener toda la información del CV, incluyendo experiencias, educación, habilidades e idiomas
function getCVInfoPostgreSQL($conn) {
    // Obtener datos de la tabla cv_info
    $query = "SELECT * FROM cv_info LIMIT 1";
    $result = pg_query($conn, $query);

    if (!$result) {
        die("Error en la consulta: " . pg_last_error($conn));
    }

    // Si se obtiene un CV
    if (pg_num_rows($result) > 0) {
        $cv_info = pg_fetch_assoc($result); // Información básica del CV

        // Obtener experiencias
        $query_experience = "SELECT company, role, description, start_date, end_date FROM experiences WHERE cv_id = $1";
        $result_experience = pg_query_params($conn, $query_experience, array($cv_info['id']));
        if (!$result_experience) {
            die("Error en la consulta de experiencias: " . pg_last_error($conn));
        }
        $cv_info['experiences'] = pg_fetch_all($result_experience);
        

        $query_education = "SELECT institution, degree, start_year, end_year FROM education WHERE cv_id = $1";
        $result_education = pg_query_params($conn, $query_education, array($cv_info['id']));
        if (!$result_education) {
            die("Error en la consulta de educación: " . pg_last_error($conn));
        }
        $cv_info['education'] = pg_fetch_all($result_education);
        

        // Obtener habilidades
        $query_skills = "SELECT skill FROM skills WHERE cv_id = $1";
        $result_skills = pg_query_params($conn, $query_skills, array($cv_info['id']));
        if (!$result_skills) {
            die("Error en la consulta de habilidades: " . pg_last_error($conn));
        }
        $cv_info['skills'] = pg_fetch_all($result_skills);

        // Obtener idiomas
        $query_languages = "SELECT language, level FROM languages WHERE cv_id = $1";
        $result_languages = pg_query_params($conn, $query_languages, array($cv_info['id']));
        if (!$result_languages) {
            die("Error en la consulta de idiomas: " . pg_last_error($conn));
        }
        $cv_info['languages'] = pg_fetch_all($result_languages);

        return $cv_info; // Retornar todos los datos del CV
    } else {
        die("No se encontraron resultados para el CV."); // Si no se encuentra el CV
    }
}


?>
