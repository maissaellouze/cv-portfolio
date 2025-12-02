<?php
include 'database/db.php'; 
function execute_statement($pdo, $sql, $params, $success_msg) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $success_msg;
    } catch (PDOException $e) {
        error_log("Erreur BDD : " . $e->getMessage());
        return "Erreur BDD : Opération impossible. Vérifiez les logs.";
    }
}

function sanitize_input($key, $method = INPUT_POST) {
    $value = filter_input($method, $key, FILTER_UNSAFE_RAW);
    return $value !== false ? htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;
}

$message = "";

// --- Compétences (Skills) ---
if (isset($_POST['add_skill']) || isset($_POST['update_skill'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;
    $category = sanitize_input('category');
    $skill_name = sanitize_input('skill_name');
    $level = filter_input(INPUT_POST, 'level', FILTER_VALIDATE_INT, array('options' => array('min_range' => 0, 'max_range' => 100)));
    
    if ($category && $skill_name && $level !== false) {
        if (isset($_POST['add_skill'])) {
            $sql = "INSERT INTO skills (category, skill_name, level) VALUES (?, ?, ?)";
            $params = [$category, $skill_name, $level];
            $message = execute_statement($pdo, $sql, $params, "Compétence ajoutée avec succès !");
        } elseif (isset($_POST['update_skill']) && $id) {
            $sql = "UPDATE skills SET category=?, skill_name=?, level=? WHERE id=?";
            $params = [$category, $skill_name, $level, $id];
            $message = execute_statement($pdo, $sql, $params, "Compétence mise à jour avec succès !");
        }
    } else { $message = "Erreur de validation des données pour la compétence."; }
}

// --- Expériences (Experiences) ---
if (isset($_POST['add_experience']) || isset($_POST['update_experience'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;
    $title = sanitize_input('title');
    $company = sanitize_input('company');
    $location = sanitize_input('location');
    $start_date = sanitize_input('start_date');
    $end_date = sanitize_input('end_date');
    $description = sanitize_input('description');
    $tech_stack = sanitize_input('tech_stack');

    if ($title && $start_date) {
        if (isset($_POST['add_experience'])) {
            $sql = "INSERT INTO experience (title, company, location, start_date, end_date, description, tech_stack) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $params = [$title, $company, $location, $start_date, $end_date, $description, $tech_stack];
            $message = execute_statement($pdo, $sql, $params, "Expérience ajoutée avec succès !");
        } elseif (isset($_POST['update_experience']) && $id) {
            $sql = "UPDATE experience SET title=?, company=?, location=?, start_date=?, end_date=?, description=?, tech_stack=? WHERE id=?";
            $params = [$title, $company, $location, $start_date, $end_date, $description, $tech_stack, $id];
            $message = execute_statement($pdo, $sql, $params, "Expérience mise à jour avec succès !");
        }
    } else { $message = "Erreur de validation des données pour l'expérience."; }
}


// --- Projets (Projects) ---
if (isset($_POST['add_project']) || isset($_POST['update_project'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;
    $title = sanitize_input('title');
    $description = sanitize_input('description');
    $start_date = sanitize_input('start_date');
    $end_date = sanitize_input('end_date');
    $features = sanitize_input('features');
    $tech_stack = sanitize_input('tech_stack');

    if ($title && $start_date) {
        if (isset($_POST['add_project'])) {
            $sql = "INSERT INTO projects (title, description, start_date, end_date, features, tech_stack) VALUES (?, ?, ?, ?, ?, ?)";
            $params = [$title, $description, $start_date, $end_date, $features, $tech_stack];
            $message = execute_statement($pdo, $sql, $params, "Projet ajouté avec succès !");
        } elseif (isset($_POST['update_project']) && $id) {
            $sql = "UPDATE projects SET title=?, description=?, start_date=?, end_date=?, features=?, tech_stack=? WHERE id=?";
            $params = [$title, $description, $start_date, $end_date, $features, $tech_stack, $id];
            $message = execute_statement($pdo, $sql, $params, "Projet mis à jour avec succès !");
        }
    } else { $message = "Erreur de validation des données pour le projet."; }
}

// --- Associations (Associations) ---
if (isset($_POST['add_association']) || isset($_POST['update_association'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?? null;
    $title = sanitize_input('title');
    $organization = sanitize_input('organization');
    $start_date = sanitize_input('start_date');
    $end_date = sanitize_input('end_date');
    $description = sanitize_input('description');
    $logo_url = sanitize_input('logo_url');

    if ($title && $organization && $start_date) {
        if (isset($_POST['add_association'])) {
            $sql = "INSERT INTO associations (title, organization, start_date, end_date, description, logo_url) VALUES (?, ?, ?, ?, ?, ?)";
            $params = [$title, $organization, $start_date, $end_date, $description, $logo_url];
            $message = execute_statement($pdo, $sql, $params, "Association ajoutée avec succès !");
        } elseif (isset($_POST['update_association']) && $id) {
            $sql = "UPDATE associations SET title=?, organization=?, start_date=?, end_date=?, description=?, logo_url=? WHERE id=?";
            $params = [$title, $organization, $start_date, $end_date, $description, $logo_url, $id];
            $message = execute_statement($pdo, $sql, $params, "Association mise à jour avec succès !");
        }
    } else { $message = "Erreur de validation des données pour l'association."; }
}

// --- Gestion des Suppressions (CRUD : DELETE) ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['type']) && isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $type = sanitize_input('type', INPUT_GET); 

    $table_map = [
        'skill' => 'skills',
        'experience' => 'experience',
        'project' => 'projects',
        'association' => 'associations'
    ];

    if ($id && isset($table_map[$type])) {
        $table = $table_map[$type];
        $sql = "DELETE FROM $table WHERE id = ?";
        $message = execute_statement($pdo, $sql, [$id], ucfirst($type) . " supprimé(e) avec succès !");
    } else {
        $message = "Erreur de suppression: Type ou ID invalide.";
    }
    // Redirection après suppression pour éviter la re-soumission
    header("Location: admin.php?message=" . urlencode($message) . "#" . $type . "s");
    exit();
}

// --- Récupération du message après redirection ---
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

// -------------------------
// 3. RÉCUPÉRATION DES DONNÉES
// -------------------------
try {
    $skills = $pdo->query("SELECT * FROM skills ORDER BY category, skill_name")->fetchAll(PDO::FETCH_ASSOC);
    $experiences = $pdo->query("SELECT * FROM experience ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
    $projects = $pdo->query("SELECT * FROM projects ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
    $associations = $pdo->query("SELECT * FROM associations ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur de récupération des données: " . $e->getMessage());
    $message = "Erreur critique : Impossible de charger les données.";
    $skills = $experiences = $projects = $associations = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - CV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root{
            --primary-color:#009688;
            --highlight-color:#26a69a;
            --accent-color:#00bfa5;
            --secondary-color:#555;
            --light-gray:#eee;
            --white:#fff;
            --light-bg:#f7f9f9;
            --shadow:0 4px 15px rgba(0,0,0,0.1);
            --shadow-hover: 0 8px 25px rgba(0,0,0,0.15);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            min-height: 100vh; 
            background: var(--light-bg); 
            color: var(--secondary-color); 
            /* Ajout d'un padding pour que le contenu ne soit pas masqué par la navbar fixe */
            padding-top: 60px; 
        }
        
        /* ------------------------- */
        /* NOUVEAUX STYLES NAV BAR FIXE HORIZONTALE */
        /* ------------------------- */
        .fixed-navbar {
            position: fixed; /* Rendre la barre fixe */
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: var(--primary-color);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 1001; /* Pour qu'elle reste au-dessus de tout */
        }

        .navbar-header {
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-links {
            display: flex;
            gap: 25px;
        }

        .navbar-links a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
            border-radius: 6px;
        }

        .navbar-links a:hover {
            background: var(--highlight-color);
        }
        
        /* Suppression des styles .sidebar, et modification de .main-content */
        .main-content { 
            /* Supprime flex: 1; et le décalage pour la sidebar */
            padding: 40px; 
            overflow-y: auto; 
            width: 100%;
        }
        
        /* Boutons et Header de section */
        .section { margin-bottom: 60px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid var(--light-gray); }
        .section-header h2 { margin: 0; font-size: 28px; color: var(--secondary-color); }
        .add-btn, .edit-btn, .delete-btn { padding: 10px 18px; background: var(--highlight-color); color: var(--white); border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; }
        .add-btn:hover { background: var(--accent-color); transform: translateY(-2px); box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1); }
        .edit-btn { background: var(--accent-color); padding: 8px 15px; }
        .edit-btn:hover { background: var(--primary-color); }
        .delete-btn { background: #dc3545; padding: 8px 15px; }
        .delete-btn:hover { background: #c82333; }

        /* Conteneurs et Cartes */
        .admin-list { display: flex; flex-direction: column; gap: 20px; }
        .cards-grid{display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:20px; margin-top:20px;}
        .card { background: var(--white); padding: 20px 25px; border-radius: 15px; box-shadow: var(--shadow); transition: all 0.3s ease; border-left: 5px solid var(--highlight-color); }
        .card:hover { box-shadow: var(--shadow-hover); transform: translateY(-3px); }
        
        /* Compétences spécifiques */
        .skill-group { margin-bottom: 20px; }
        .skill-group h3 { color: var(--primary-color); margin-bottom: 10px; font-size: 22px; padding-bottom: 5px; border-bottom: 2px dotted var(--light-gray); }
        .card-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--light-gray); }
        .card-item:last-child { border-bottom: none; }
        .skill-details { flex-grow: 1; margin-right: 20px; }
        .card-header { display: flex; justify-content: space-between; font-weight: 600; margin-bottom: 5px; }
        .card-header span:last-child { color: var(--accent-color); }
        .card-bar { height: 8px; background: var(--light-gray); border-radius: 4px; overflow: hidden; }
        .card-level { height: 100%; background: linear-gradient(90deg, var(--highlight-color), var(--accent-color)); }

        /* Expériences / Projets / Associations (structure de base) */
        .exp-item, .proj-item, .assoc-item {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .item-content { flex-grow: 1; }
        .item-content h3 { color: var(--highlight-color); font-size: 20px; margin-bottom: 5px; }
        .item-content h5 { color: var(--secondary-color); margin-bottom: 10px; font-weight: 400; font-style: italic;}
        .item-actions { display: flex; gap: 10px; flex-shrink: 0; }
        
        /* Projets spécifiques */
        .project-features-list { list-style: disc; margin-left: 20px; font-size: 0.9em; padding-left: 10px;}
        .project-features-list li { margin-bottom: 5px; }

        /* Associations spécifiques */
        .association-logo { flex-shrink: 0; width: 50px; height: 50px; object-fit: contain; margin-right: 15px; border-radius: 5px;}

        /* Styles pour les Modales */
        .modal {
            display: none; 
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: var(--white);
            margin: 5% auto;
            padding: 30px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: var(--shadow-hover);
            position: relative;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: var(--primary-color);
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content h3 {
            color: var(--secondary-color);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--light-gray);
            padding-bottom: 10px;
        }

        .modal-content label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .modal-content input[type="text"],
        .modal-content input[type="date"],
        .modal-content input[type="url"],
        .modal-content input[type="number"],
        .modal-content textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            box-sizing: border-box;
        }

        .modal-content textarea {
            resize: vertical;
            min-height: 100px;
        }

        .modal-content button[type="submit"] {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
        }

        .modal-content button[type="submit"]:hover {
            background: var(--accent-color);
        }
        /* admin.css (ou votre feuille de style utilisée par admin.php) */



/* Cibler spécifiquement l'icône à l'intérieur du lien et la mettre en blanc */
.fixed-navbar .back-to-cv-btn i {
    /* Ceci est la règle CORRECTE et RECOMMANDÉE */
    color: white; 
}
    </style>
</head>
<body>

<nav class="fixed-navbar">
    <div class="navbar-header">
         <a href="index.php" class="back-to-cv-btn" title="Retourner au CV public">
            <i class="fas fa-arrow-left"></i>
        </a> 
        <i class="fas fa-user-shield"></i> 
        <span>Admin CV</span>
       
        
    </div>
    <div class="navbar-links">
        <a href="#skills"><i class="fas fa-brain"></i> Compétences</a>
        <a href="#experience"><i class="fas fa-briefcase"></i> Expériences</a>
        <a href="#projects"><i class="fas fa-laptop-code"></i> Projets</a>
        <a href="#associations"><i class="fas fa-hands-helping"></i> Associations</a>
        
           
    </div>
</nav>

<div class="main-content">
    <h1>Tableau de Bord Administratif</h1>
    <?php if($message): ?>
        <p style='padding: 15px; background: #e6ffed; color: #155724; border: 1px solid #c3e6cb; border-radius: 8px; margin-bottom: 20px;'>
            <?= htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <div class="section" id="skills">
        <div class="section-header">
            <h2>Compétences</h2>
            <button class="add-btn" onclick="openSkillModal()">
                <i class="fas fa-plus"></i> Ajouter
            </button>
        </div>
        
        <?php
        $skills_by_category = [];
        foreach($skills as $skill){ $skills_by_category[$skill['category']][] = $skill; }
        ?>
        
        <div class="cards-grid">
            <?php foreach($skills_by_category as $category => $cat_skills): ?>
            <div class="card skill-group">
                <h3><?= htmlspecialchars($category); ?></h3>
                <?php foreach($cat_skills as $s): ?>
                <div class="card-item">
                    <div class="skill-details">
                        <div class="card-header">
                            <span><?= htmlspecialchars($s['skill_name']); ?></span>
                            <span><?= $s['level']; ?>%</span>
                        </div>
                        <div class="card-bar">
                            <div class="card-level" style="width:<?= $s['level']; ?>%;"></div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editSkill(<?= $s['id']; ?>)">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="delete-btn" onclick="deleteItem(<?= $s['id']; ?>, 'skill')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <hr>

    <div class="section" id="experience">
        <div class="section-header">
            <h2>Expériences</h2>
            <button class="add-btn" onclick="openExperienceModal()">
                <i class="fas fa-plus"></i> Ajouter
            </button>
        </div>
        <div class="admin-list">
            <?php foreach($experiences as $exp): ?>
            <div class="card">
                <div class="exp-item">
                    <div class="item-content">
                        <h3><?= htmlspecialchars($exp['title']); ?></h3>
                        <h5><?= htmlspecialchars($exp['company']); ?> - <?= htmlspecialchars($exp['location']); ?></h5>
                        <?php 
                            $start_date_display = date("M Y", strtotime($exp['start_date']));
                            $end_date_display = $exp['end_date'] ? date("M Y", strtotime($exp['end_date'])) : "Présent";
                        ?>
                        <p style="font-weight: 600; color: var(--primary-color); margin-bottom: 10px;">
                            <?= $start_date_display ?> - <?= $end_date_display; ?>
                        </p>
                        <p style="margin-bottom: 10px;"><?= nl2br(htmlspecialchars($exp['description'])); ?></p>
                        <p style="font-size: 14px;"><strong>Tech Stack:</strong> <?= htmlspecialchars($exp['tech_stack']); ?></p>
                    </div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editExperience(<?= $exp['id']; ?>)">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="delete-btn" onclick="deleteItem(<?= $exp['id']; ?>, 'experience')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <hr>
    
    <div class="section" id="projects">
        <div class="section-header">
            <h2>Projets</h2>
            <button class="add-btn" onclick="openProjectModal()">
                <i class="fas fa-plus"></i> Ajouter
            </button>
        </div>
        <div class="admin-list">
            <?php foreach($projects as $p): ?>
            <div class="card">
                <div class="proj-item">
                    <div class="item-content">
                        <h3><?= htmlspecialchars($p['title']); ?></h3>
                        <?php 
                            $proj_start_date_display = date("M Y", strtotime($p['start_date']));
                            $proj_end_date_display = $p['end_date'] ? date("M Y", strtotime($p['end_date'])) : "En cours";
                        ?>
                        <p style="font-weight: 600; color: var(--primary-color); margin-bottom: 10px;">
                            <?= $proj_start_date_display ?> - <?= $proj_end_date_display; ?>
                        </p>
                        <p style="margin-bottom: 10px;"><strong>Description:</strong> <?= nl2br(htmlspecialchars($p['description'])); ?></p>
                        
                        <?php if (!empty($p['features'])): ?>
                            <p><strong>Fonctionnalités:</strong></p>
                            <ul class="project-features-list">
                                <?php foreach(array_filter(explode("\n", $p['features'])) as $feature): ?>
                                    <li><?= htmlspecialchars(trim($feature)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <p style="font-size: 14px; margin-top: 10px;"><strong>Tech Stack:</strong> <?= htmlspecialchars($p['tech_stack']); ?></p>
                    </div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editProject(<?= $p['id']; ?>)">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="delete-btn" onclick="deleteItem(<?= $p['id']; ?>, 'project')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <hr>
    
    <div class="section" id="associations">
        <div class="section-header">
            <h2>Associations</h2>
            <button class="add-btn" onclick="openAssociationModal()">
                <i class="fas fa-plus"></i> Ajouter
            </button>
        </div>
        <div class="admin-list">
            <?php foreach($associations as $a): ?>
            <div class="card">
                <div class="assoc-item">
                    <?php if($a['logo_url']): ?>
                        <img src="<?= htmlspecialchars($a['logo_url']); ?>" alt="Logo Association" class="association-logo">
                    <?php else: ?>
                        <i class="fas fa-users" style="font-size: 40px; color: var(--primary-color); margin-right: 15px;"></i>
                    <?php endif; ?>

                    <div class="item-content">
                        <h3><?= htmlspecialchars($a['title']); ?></h3>
                        <h5><?= htmlspecialchars($a['organization']); ?></h5>
                        <?php 
                            $assoc_start_date_display = date("M Y", strtotime($a['start_date']));
                            $assoc_end_date_display = $a['end_date'] ? date("M Y", strtotime($a['end_date'])) : "Présent";
                        ?>
                        <p style="font-weight: 600; color: var(--accent-color); margin-bottom: 5px;">
                            <?= $assoc_start_date_display ?> - <?= $assoc_end_date_display; ?>
                        </p>
                        <p><?= nl2br(htmlspecialchars($a['description'])); ?></p>
                        <p style="font-size: 14px;">URL Logo: <?= htmlspecialchars($a['logo_url']); ?></p>
                    </div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editAssociation(<?= $a['id']; ?>)">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="delete-btn" onclick="deleteItem(<?= $a['id']; ?>, 'association')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div> <div id="skillModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('skillModal')">&times;</span>
        <h3 id="skillModalTitle">Ajouter une Compétence</h3>
        <form id="skillForm" method="POST" action="admin.php">
            <input type="hidden" name="id" id="skillId">
            <label for="skillCategory">Catégorie :</label>
            <input type="text" id="skillCategory" name="category" required>

            <label for="skillName">Nom de la Compétence :</label>
            <input type="text" id="skillName" name="skill_name" required>

            <label for="skillLevel">Niveau (0-100%) :</label>
            <input type="number" id="skillLevel" name="level" min="0" max="100" required>

            <button type="submit" name="add_skill" id="skillSubmitBtn">Ajouter</button>
        </form>
    </div>
</div>

<div id="experienceModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('experienceModal')">&times;</span>
        <h3 id="experienceModalTitle">Ajouter une Expérience</h3>
        <form id="experienceForm" method="POST" action="admin.php">
            <input type="hidden" name="id" id="experienceId">
            
            <label for="expTitle">Titre du Poste :</label>
            <input type="text" id="expTitle" name="title" required>

            <label for="expCompany">Entreprise :</label>
            <input type="text" id="expCompany" name="company" required>

            <label for="expLocation">Lieu :</label>
            <input type="text" id="expLocation" name="location">

            <label for="expStartDate">Date de Début :</label>
            <input type="date" id="expStartDate" name="start_date" required>

            <label for="expEndDate">Date de Fin (laisser vide si Présent) :</label>
            <input type="date" id="expEndDate" name="end_date">

            <label for="expDescription">Description des Tâches :</label>
            <textarea id="expDescription" name="description" required></textarea>
            
            <label for="expTechStack">Technologies utilisées :</label>
            <input type="text" id="expTechStack" name="tech_stack">

            <button type="submit" name="add_experience" id="experienceSubmitBtn">Ajouter</button>
        </form>
    </div>
</div>

<div id="projectModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('projectModal')">&times;</span>
        <h3 id="projectModalTitle">Ajouter un Projet</h3>
        <form id="projectForm" method="POST" action="admin.php">
            <input type="hidden" name="id" id="projectId">
            <label for="projectTitle">Titre :</label>
            <input type="text" id="projectTitle" name="title" required>

            <label for="projectDescription">Description :</label>
            <textarea id="projectDescription" name="description" required></textarea>

            <label for="projectFeatures">Fonctionnalités (une par ligne) :</label>
            <textarea id="projectFeatures" name="features"></textarea>

            <label for="projectStartDate">Date de Début :</label>
            <input type="date" id="projectStartDate" name="start_date" required>

            <label for="projectEndDate">Date de Fin (laisser vide si en cours) :</label>
            <input type="date" id="projectEndDate" name="end_date">
            
            <label for="projectTechStack">Technologies (séparées par une virgule) :</label>
            <input type="text" id="projectTechStack" name="tech_stack" required>

            <button type="submit" name="add_project" id="projectSubmitBtn">Ajouter</button>
        </form>
    </div>
</div>

<div id="associationModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('associationModal')">&times;</span>
        <h3 id="associationModalTitle">Ajouter une Association</h3>
        <form id="associationForm" method="POST" action="admin.php">
            <input type="hidden" name="id" id="associationId">
            <label for="associationTitle">Rôle/Poste :</label>
            <input type="text" id="associationTitle" name="title" required>

            <label for="associationOrganization">Organisation :</label>
            <input type="text" id="associationOrganization" name="organization" required>

            <label for="associationDescription">Description des actions :</label>
            <textarea id="associationDescription" name="description" required></textarea>

            <label for="associationStartDate">Date de Début :</label>
            <input type="date" id="associationStartDate" name="start_date" required>

            <label for="associationEndDate">Date de Fin (laisser vide si Présent) :</label>
            <input type="date" id="associationEndDate" name="end_date">

            <label for="associationLogoUrl">URL du Logo (Optionnel) :</label>
            <input type="url" id="associationLogoUrl" name="logo_url">

            <button type="submit" name="add_association" id="associationSubmitBtn">Ajouter</button>
        </form>
    </div>
</div>

<script>
    // Variables pour stocker les données (déclarées en PHP)
    let skills = <?php echo json_encode($skills); ?>;
    let experiences = <?php echo json_encode($experiences); ?>;
    let projects = <?php echo json_encode($projects); ?>;
    let associations = <?php echo json_encode($associations); ?>;

    // Défilement fluide pour la navbar
    document.querySelectorAll('.navbar-links a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            // Empêche le comportement de saut par défaut
            e.preventDefault(); 
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                // Utilise l'API du navigateur pour un défilement en douceur
                // -70px pour compenser la hauteur de la navbar fixe (60px + marge)
                window.scrollTo({
                    top: targetElement.offsetTop - 70, 
                    behavior: 'smooth'
                });
            }
        });
    });

    function openModal(id) {
        document.getElementById(id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    // Fermeture de la modale en cliquant en dehors
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }

    // --- GESTION DES COMPÉTENCES ---
    function openSkillModal() { 
        document.getElementById('skillModalTitle').innerText = "Ajouter une Compétence";
        document.getElementById('skillForm').reset();
        document.getElementById('skillId').value = '';
        document.getElementById('skillSubmitBtn').innerText = "Ajouter";
        document.getElementById('skillSubmitBtn').name = "add_skill";
        openModal('skillModal');
    }

    function editSkill(id) { 
        let item = skills.find(s => s.id == id);
        if (!item) return;

        document.getElementById('skillModalTitle').innerText = "Modifier la Compétence";
        document.getElementById('skillId').value = item.id;
        document.getElementById('skillCategory').value = item.category;
        document.getElementById('skillName').value = item.skill_name;
        document.getElementById('skillLevel').value = item.level;

        document.getElementById('skillSubmitBtn').innerText = "Modifier";
        document.getElementById('skillSubmitBtn').name = "update_skill";
        openModal('skillModal');
    }

    // --- GESTION DES EXPÉRIENCES ---
    function openExperienceModal() { 
        document.getElementById('experienceModalTitle').innerText = "Ajouter une Expérience";
        document.getElementById('experienceForm').reset();
        document.getElementById('experienceId').value = '';
        document.getElementById('experienceSubmitBtn').innerText = "Ajouter";
        document.getElementById('experienceSubmitBtn').name = "add_experience";
        openModal('experienceModal');
    }

    function editExperience(id) { 
        let item = experiences.find(e => e.id == id);
        if (!item) return;

        document.getElementById('experienceModalTitle').innerText = "Modifier l'Expérience";
        document.getElementById('experienceId').value = item.id;
        document.getElementById('expTitle').value = item.title;
        document.getElementById('expCompany').value = item.company;
        document.getElementById('expLocation').value = item.location;
        document.getElementById('expStartDate').value = item.start_date;
        document.getElementById('expEndDate').value = item.end_date;
        document.getElementById('expDescription').value = item.description.replace(/\\n/g, '\n'); 
        document.getElementById('expTechStack').value = item.tech_stack;

        document.getElementById('experienceSubmitBtn').innerText = "Modifier";
        document.getElementById('experienceSubmitBtn').name = "update_experience";
        openModal('experienceModal');
    }

    // --- GESTION DES PROJETS ---
    function openProjectModal() {
        document.getElementById('projectModalTitle').innerText = "Ajouter un Projet";
        document.getElementById('projectForm').reset();
        document.getElementById('projectId').value = '';
        document.getElementById('projectSubmitBtn').innerText = "Ajouter";
        document.getElementById('projectSubmitBtn').name = "add_project";
        openModal('projectModal');
    }

    function editProject(id) {
        let item = projects.find(p => p.id == id);
        if (!item) return;

        document.getElementById('projectModalTitle').innerText = "Modifier le Projet";
        document.getElementById('projectId').value = item.id;
        document.getElementById('projectTitle').value = item.title;
        document.getElementById('projectDescription').value = item.description.replace(/\\n/g, '\n');
        document.getElementById('projectFeatures').value = item.features.replace(/\\n/g, '\n'); 
        document.getElementById('projectStartDate').value = item.start_date;
        document.getElementById('projectEndDate').value = item.end_date;
        document.getElementById('projectTechStack').value = item.tech_stack;

        document.getElementById('projectSubmitBtn').innerText = "Modifier";
        document.getElementById('projectSubmitBtn').name = "update_project";
        openModal('projectModal');
    }

    // --- GESTION DES ASSOCIATIONS ---
    function openAssociationModal() {
        document.getElementById('associationModalTitle').innerText = "Ajouter une Association";
        document.getElementById('associationForm').reset();
        document.getElementById('associationId').value = '';
        document.getElementById('associationSubmitBtn').innerText = "Ajouter";
        document.getElementById('associationSubmitBtn').name = "add_association";
        openModal('associationModal');
    }

    function editAssociation(id) {
        let item = associations.find(a => a.id == id);
        if (!item) return;

        document.getElementById('associationModalTitle').innerText = "Modifier l'Association";
        document.getElementById('associationId').value = item.id;
        document.getElementById('associationTitle').value = item.title;
        document.getElementById('associationOrganization').value = item.organization;
        document.getElementById('associationDescription').value = item.description.replace(/\\n/g, '\n');
        document.getElementById('associationStartDate').value = item.start_date;
        document.getElementById('associationEndDate').value = item.end_date;
        document.getElementById('associationLogoUrl').value = item.logo_url;

        document.getElementById('associationSubmitBtn').innerText = "Modifier";
        document.getElementById('associationSubmitBtn').name = "update_association";
        openModal('associationModal');
    }

    // --- GESTION DE LA SUPPRESSION (Générique) ---
    function deleteItem(id, type) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer l'élément de ${type} (ID: ${id}) ?\nCeci est IRREVERSIBLE.`)) {
            window.location.href = `admin.php?action=delete&type=${type}&id=${id}`; 
        }
    }
</script>

</body>
</html>