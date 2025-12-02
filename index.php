<?php

include 'database/db.php';


$about = $pdo->query("SELECT * FROM about LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$skills = $pdo->query("SELECT * FROM skills ORDER BY category")->fetchAll(PDO::FETCH_ASSOC);
$experiences = $pdo->query("SELECT * FROM experience ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$projects = $pdo->query("SELECT * FROM projects ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$education = $pdo->query("SELECT * FROM education ORDER BY start_year DESC")->fetchAll(PDO::FETCH_ASSOC);
$associations = $pdo->query("SELECT * FROM associations ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$contacts = $pdo->query("SELECT * FROM contact")->fetchAll(PDO::FETCH_ASSOC);

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;


if (isset($_GET['download']) && $_GET['download'] === 'pdf') {

    $options = new Options();
    $options->setIsRemoteEnabled(true);
    ini_set('max_execution_time', 300);
    ini_set('memory_limit', '256M'); 

    $dompdf = new Dompdf($options);
    ob_start();
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CV - Maissa ELLOUZE (PDF)</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Styles spécifiques pour le PDF : masque les éléments interactifs/non-imprimables */
        body { margin: 0; padding: 0; font-family: sans-serif; font-size: 10pt; }
        .no-print, .navigation, .scroll-indicator, .footer, .print-button { display: none !important; }
        .section-container { padding: 10mm; }
        .skills-section .skill-bar .skill-level { 
            /* Fixe les barres de compétence, Dompdf ne gère pas les animations JS */
            transition: none !important;
        } 
    </style>
</head>
<body>
    
<section id="home" class="section hero-section">
    <div class="hero-content">
        <div class="hero-image-wrapper">
            <div class="hero-image">
                <img src="maissa.jpeg" alt="Photo de profil" style="width:150px; height:150px; border-radius:50%; margin: 20px auto; object-fit: cover;">
            </div>
        </div>
        <h1 class="hero-title" style="text-align:center;">Maissa ELLOUZE</h1>
        <p class="hero-subtitle" style="text-align:center;">Étudiante en Génie Logiciel</p>
        <p class="hero-tagline" style="text-align:center;">Passionnée par le développement Full Stack et les nouvelles technologies</p>
    </div>
</section>

<section id="about" class="section about-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-user"></i> À Propos</h2>
        <div class="about-content">
            <div class="about-text">
                <p class="about-intro"><?= htmlspecialchars($about['intro']); ?></p>
            </div>
        </div>
    </div>
</section>

<section id="skills" class="section skills-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-code"></i> Compétences</h2>
        <div class="skills-grid">
            <?php
            $current_category = '';
            foreach($skills as $skill) {
                if($current_category != $skill['category']) {
                    if($current_category != '') echo '</div></div>';
                    echo '<div class="skill-card"><h3><i class="fas fa-laptop-code"></i> '.htmlspecialchars($skill['category']).'</h3><div class="skill-list">';
                    $current_category = $skill['category'];
                }
                echo '<div class="skill-item">
                        <div class="skill-header">
                            <span class="skill-name">'.htmlspecialchars($skill['skill_name']).'</span>
                            <span class="skill-percent">'.$skill['level'].'%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width:'.$skill['level'].'%;"></div>
                        </div>
                    </div>';
            }
            echo '</div></div>';
            ?>
        </div>
    </div>
</section>

<section id="experience" class="section experience-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-briefcase"></i> Expérience Professionnelle</h2>
        <div class="timeline">
            <?php foreach($experiences as $exp): ?>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-date"><?= date("M Y", strtotime($exp['start_date'])) ?> - <?= $exp['end_date'] ? date("M Y", strtotime($exp['end_date'])) : "Présent"; ?></div>
                        <h3><?= htmlspecialchars($exp['title']); ?></h3>
                        <h4><?= htmlspecialchars($exp['company']) ?> - <?= htmlspecialchars($exp['location']) ?></h4>
                        <p><?= nl2br(htmlspecialchars($exp['description'])); ?></p>
                        <div class="tech-stack">
                            <?php foreach(explode(',', $exp['tech_stack']) as $tech) echo '<span>'.htmlspecialchars(trim($tech)).'</span>'; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="projects" class="section projects-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-laptop-code"></i> Projets Académiques</h2>
        <div class="projects-grid">
            <?php foreach($projects as $proj): ?>
            <div class="project-card">
                <div class="project-header">
                    <i class="fas fa-users-cog"></i>
                    <span class="project-date"><?= date("M Y", strtotime($proj['start_date'])) ?> - <?= date("M Y", strtotime($proj['end_date'])) ?></span>
                </div>
                <h3><?= htmlspecialchars($proj['title']); ?></h3>
                <p class="project-desc"><?= htmlspecialchars($proj['description']); ?></p>
                <ul class="project-features">
                    <?php foreach(explode("\n", $proj['features']) as $feature) echo '<li>'.htmlspecialchars($feature).'</li>'; ?>
                </ul>
                <div class="tech-stack">
                    <?php foreach(explode(',', $proj['tech_stack']) as $tech) echo '<span>'.htmlspecialchars(trim($tech)).'</span>'; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="education" class="section education-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-graduation-cap"></i> Formation</h2>
        <div class="education-grid">
            <?php foreach($education as $edu): ?>
            <div class="education-item">
                <div class="education-year"><?= $edu['start_year']; ?> - <?= $edu['end_year']; ?></div>
                <div class="education-content">
                    <h4><?= htmlspecialchars($edu['degree']); ?></h4>
                    <h5><?= htmlspecialchars($edu['institution']); ?></h5>
                    <p><?= htmlspecialchars($edu['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="associations" class="section associations-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-users"></i> Engagement Associatif</h2>
        <div class="associations-grid">
            <?php foreach($associations as $assoc): ?>
            <div class="association-item">
                <div class="association-icon">
                    <?php if($assoc['logo_url']): ?>
                        <img src="<?= htmlspecialchars($assoc['logo_url']); ?>" style="width:50px; height:auto;" alt="<?= htmlspecialchars($assoc['organization']); ?> Logo">
                    <?php else: ?>
                        <i class="fas fa-users"></i>
                    <?php endif; ?>
                </div>
                <div class="association-content">
                    <h4><?= htmlspecialchars($assoc['title']); ?></h4>
                    <h5><?= htmlspecialchars($assoc['organization']); ?></h5>
                    <p class="association-period"><?= date("M Y", strtotime($assoc['start_date'])) ?> - <?= $assoc['end_date'] ? date("M Y", strtotime($assoc['end_date'])) : "Présent"; ?></p>
                    <p><?= nl2br(htmlspecialchars($assoc['description'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="contact" class="section contact-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-envelope"></i> Contact</h2>
        <div class="contact-content">
            <div class="contact-info">
                <ul class="contact-list">
                    <?php foreach($contacts as $contact): ?>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong><?= htmlspecialchars($contact['type']); ?></strong>
                            <?php if($contact['type'] == "Email"): ?>
                                <a href="mailto:<?= htmlspecialchars($contact['value']); ?>"><?= htmlspecialchars($contact['value']); ?></a>
                            <?php elseif($contact['type'] == "Téléphone"): ?>
                                <a href="tel:<?= htmlspecialchars($contact['value']); ?>"><?= htmlspecialchars($contact['value']); ?></a>
                            <?php else: ?>
                                <span><?= htmlspecialchars($contact['value']); ?></span>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

</body>
</html>

<?php
    // 5. Arrêter la mise en tampon et récupérer le contenu HTML
    $html = ob_get_clean(); 

    // 6. Charger le HTML dans Dompdf
    $dompdf->loadHtml($html);

    // 7. Définir le format (A4) et l'orientation
    $dompdf->setPaper('A4', 'portrait');

    // 8. Rendu du HTML en PDF
    $dompdf->render();

    // 9. Sortie du PDF vers le navigateur (téléchargement forcé)
    $filename = 'CV_Maissa_ELLOUZE_' . date('Ymd') . '.pdf';
    // 'D' pour Download (téléchargement)
    $dompdf->stream($filename, ["Attachment" => true]); 
    
    // 10. Terminer l'exécution du script après l'envoi du PDF
    exit(); 
}

// ----------------------------------------------------
// 4. DÉBUT DU RENDU HTML NORMAL (si le PDF n'est PAS demandé)
// ----------------------------------------------------

// Les variables $about, $skills, etc. SONT DÉJÀ DÉFINIES au début du script.

// Fin du bloc PHP pour commencer le HTML du CV normal
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="CV interactif en ligne - Portfolio professionnel de Maissa ELLOUZE">
<title>CV - Maissa ELLOUZE</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="styles.css">
<script src="script.js" defer></script> 
</head>
<body>

<nav class="navigation no-print">
    <div class="nav-brand">ME</div>
    <ul class="nav-menu">
        <li><a href="#home" class="nav-link active" data-section="home"><i class="fas fa-home"></i><span>Accueil</span></a></li>
        <li><a href="#about" class="nav-link" data-section="about"><i class="fas fa-user"></i><span>Profil</span></a></li>
        <li><a href="#skills" class="nav-link" data-section="skills"><i class="fas fa-code"></i><span>Compétences</span></a></li>
        <li><a href="#experience" class="nav-link" data-section="experience"><i class="fas fa-briefcase"></i><span>Expérience</span></a></li>
        <li><a href="#projects" class="nav-link" data-section="projects"><i class="fas fa-laptop-code"></i><span>Projets</span></a></li>
        <li><a href="#education" class="nav-link" data-section="education"><i class="fas fa-graduation-cap"></i><span>Formation</span></a></li>
        <li><a href="#associations" class="nav-link" data-section="associations"><i class="fas fa-users"></i><span>Associations</span></a></li>
        <li><a href="#contact" class="nav-link" data-section="contact"><i class="fas fa-envelope"></i><span>Contact</span></a></li>
    </ul>
 <div class="nav-actions no-print">
    <button class="dark-mode-toggle" aria-label="Basculer le mode sombre" title="Mode sombre">
        <i class="fas fa-moon"></i>
    </button>
    
    <a href="?download=pdf" class="pdf-download-btn" aria-label="Télécharger PDF" title="Télécharger CV en PDF">
        <i class="fas fa-file-pdf"></i>
    </a>
    
    <a href="admin.php" class="pdf-download-btn" aria-label="Administration" title="Accès Administration">
        <i class="fas fa-user-shield"></i>
    </a>
</div>
    <button class="nav-toggle" aria-label="Toggle navigation">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

<div class="scroll-indicator no-print">
    <div class="scroll-progress"></div>
</div>

<section id="home" class="section hero-section">
    <div class="hero-content">
        <div class="hero-image-wrapper">
            <div class="hero-image">
                <img src="maissa.jpeg" alt="Photo de profil">
            </div>
        </div>
        <h1 class="hero-title">Maissa ELLOUZE</h1>
        <p class="hero-subtitle">Étudiante en Génie Logiciel</p>
        <p class="hero-tagline">Passionnée par le développement Full Stack et les nouvelles technologies</p>
        <div class="hero-socials">
            <a href="mailto:maissaellouze02@gmail.com" class="social-link" aria-label="Email"><i class="fas fa-envelope"></i></a>
            <a href="https://linkedin.com/in/maissa-ellouze" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="https://github.com/maissaellouze" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="GitHub"><i class="fab fa-github"></i></a>
        </div>
        <a href="#about" class="hero-cta">Découvrir mon profil</a>
    </div>
    <div class="hero-scroll-indicator">
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<section id="about" class="section about-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-user"></i> À Propos</h2>
        <div class="about-content">
            <div class="about-text">
                <p class="about-intro"><?= htmlspecialchars($about['intro']); ?></p>
            </div>
            <div class="about-stats">
                <div class="stat-item">
                    <div class="stat-number" data-target="<?= $about['experience_years']; ?>">0</div>
                    <div class="stat-label">Stages complétés</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="<?= $about['projects_count']; ?>">0</div>
                    <div class="stat-label">Projets réalisés</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="<?= $about['technologies_count']; ?>">0</div>
                    <div class="stat-label">Technologies maîtrisées</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="skills" class="section skills-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-code"></i> Compétences</h2>
        <div class="skills-grid">
            <?php
            $current_category = '';
            foreach($skills as $skill) {
                if($current_category != $skill['category']) {
                    if($current_category != '') echo '</div></div>';
                    echo '<div class="skill-card"><h3><i class="fas fa-laptop-code"></i> '.htmlspecialchars($skill['category']).'</h3><div class="skill-list">';
                    $current_category = $skill['category'];
                }
                echo '<div class="skill-item">
                        <div class="skill-header">
                            <span class="skill-name">'.htmlspecialchars($skill['skill_name']).'</span>
                            <span class="skill-percent">'.$skill['level'].'%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-level" data-level="'.$skill['level'].'"></div>
                        </div>
                    </div>';
            }
            echo '</div></div>';
            ?>
        </div>
    </div>
</section>

<section id="experience" class="section experience-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-briefcase"></i> Expérience Professionnelle</h2>
        <div class="timeline">
            <?php foreach($experiences as $exp): ?>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-date"><?= date("M Y", strtotime($exp['start_date'])) ?> - <?= $exp['end_date'] ? date("M Y", strtotime($exp['end_date'])) : "Présent"; ?></div>
                        <h3><?= htmlspecialchars($exp['title']); ?></h3>
                        <h4><?= htmlspecialchars($exp['company']) ?> - <?= htmlspecialchars($exp['location']) ?></h4>
                        <p><?= nl2br(htmlspecialchars($exp['description'])); ?></p>
                        <div class="tech-stack">
                            <?php foreach(explode(',', $exp['tech_stack']) as $tech) echo '<span>'.htmlspecialchars(trim($tech)).'</span>'; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="projects" class="section projects-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-laptop-code"></i> Projets Académiques</h2>
        <div class="projects-grid">
            <?php foreach($projects as $proj): ?>
            <div class="project-card">
                <div class="project-header">
                    <i class="fas fa-users-cog"></i>
                    <span class="project-date"><?= date("M Y", strtotime($proj['start_date'])) ?> - <?= date("M Y", strtotime($proj['end_date'])) ?></span>
                </div>
                <h3><?= htmlspecialchars($proj['title']); ?></h3>
                <p class="project-desc"><?= htmlspecialchars($proj['description']); ?></p>
                <ul class="project-features">
                    <?php foreach(explode("\n", $proj['features']) as $feature) echo '<li>'.htmlspecialchars($feature).'</li>'; ?>
                </ul>
                <div class="tech-stack">
                    <?php foreach(explode(',', $proj['tech_stack']) as $tech) echo '<span>'.htmlspecialchars(trim($tech)).'</span>'; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="education" class="section education-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-graduation-cap"></i> Formation</h2>
        <div class="education-grid">
            <?php foreach($education as $edu): ?>
            <div class="education-item">
                <div class="education-year"><?= $edu['start_year']; ?> - <?= $edu['end_year']; ?></div>
                <div class="education-content">
                    <h4><?= htmlspecialchars($edu['degree']); ?></h4>
                    <h5><?= htmlspecialchars($edu['institution']); ?></h5>
                    <p><?= htmlspecialchars($edu['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="associations" class="section associations-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-users"></i> Engagement Associatif</h2>
        <div class="associations-grid">
            <?php foreach($associations as $assoc): ?>
            <div class="association-item">
                <div class="association-icon">
                    <?php if($assoc['logo_url']): ?>
                        <img src="<?= htmlspecialchars($assoc['logo_url']); ?>" style="width:50px; height:auto;" alt="<?= htmlspecialchars($assoc['organization']); ?> Logo">
                    <?php else: ?>
                        <i class="fas fa-users"></i>
                    <?php endif; ?>
                </div>
                <div class="association-content">
                    <h4><?= htmlspecialchars($assoc['title']); ?></h4>
                    <h5><?= htmlspecialchars($assoc['organization']); ?></h5>
                    <p class="association-period"><?= date("M Y", strtotime($assoc['start_date'])) ?> - <?= $assoc['end_date'] ? date("M Y", strtotime($assoc['end_date'])) : "Présent"; ?></p>
                    <p><?= nl2br(htmlspecialchars($assoc['description'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="contact" class="section contact-section">
    <div class="section-container">
        <h2 class="section-title"><i class="fas fa-envelope"></i> Contact</h2>
        <div class="contact-content">
            <div class="contact-info">
                <ul class="contact-list">
                    <?php foreach($contacts as $contact): ?>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong><?= htmlspecialchars($contact['type']); ?></strong>
                            <?php if($contact['type'] == "Email"): ?>
                                <a href="mailto:<?= htmlspecialchars($contact['value']); ?>"><?= htmlspecialchars($contact['value']); ?></a>
                            <?php elseif($contact['type'] == "Téléphone"): ?>
                                <a href="tel:<?= htmlspecialchars($contact['value']); ?>"><?= htmlspecialchars($contact['value']); ?></a>
                            <?php else: ?>
                                <span><?= htmlspecialchars($contact['value']); ?></span>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<footer class="footer no-print">
    <p>&copy; <?= date("Y"); ?> Maissa ELLOUZE. Tous droits réservés.</p>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Imprimer le CV
    </button>
</footer>

<a href="admin.php" class="admin-float-btn no-print" title="Accès Administration">
    <i class="fas fa-user-shield"></i>
</a>
</footer>


</body>
</html>