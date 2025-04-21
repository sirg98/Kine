<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio - Kinesiología</title>
  <link rel="icon" type="image/jpeg" href="img/favicon.jpg" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles/root.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #1c1c1c;
      color: #f7f6ef;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .contact-hero {
      position: relative;
      background: url('assets/img/fondo.jpg') center/cover no-repeat;
      height: 60vh;
      min-height: 50vh;
    }

    .contact-hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .hero-content {
      position: relative;
      z-index: 1;
      color: #fff;
      height: 100%;
    }

    .list-group-item {
      background-color: transparent;
      border: none;
      padding-left: 0;
      color: #f7f6ef;
    }

    .bg-dark-section {
      background: rgba(35,39,43,0.92);
      border-radius: 1.5rem;
      box-shadow: 0 2px 16px rgba(0,0,0,0.5);
      backdrop-filter: blur(8px);
      padding: 2rem;
      margin-bottom: 3rem;
    }

    .newsletter input,
    .newsletter input::placeholder {
      color: #f7f6ef;
    }

    .newsletter input {
      background: #23272b;
      border-color: #444;
    }

    .newsletter input:focus {
      background: #23272b;
      color: #fff;
      border-color: #4CAF50;
      box-shadow: none;
    }

    .btn-custom {
      background: #4CAF50 !important;
      color: #fff !important;
      border: none;
      font-size: 1.1rem;
      letter-spacing: 1px;
      padding: 0.75rem;
      border-radius: 0.5rem;
    }

    .btn-custom:hover {
      background: #45a049 !important;
    }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <main class="pt-5">
    <!-- Hero Section -->
    <section class="contact-hero mb-5">
      <div class="container hero-content d-flex flex-column justify-content-center align-items-center text-center">
        <h1 class="display-3 fw-bold mb-3">Kinesiología</h1>
        <p class="lead mb-3">Recupera tu bienestar físico y mejora tu calidad de vida con atención profesional y personalizada.</p>
        <a href="contacto.php" class="btn btn-custom btn-lg shadow">Pide cita</a>
      </div>
    </section>

    <!-- Info Section -->
    <section id="servicios" class="container bg-dark-section">
      <div class="row align-items-center g-5">
        <div class="col-md-6 text-center">
          <img src="assets/img/quehacemos.webp" alt="Kinesiología en acción" class="img-fluid rounded-4 shadow">
        </div>
        <div class="col-md-6">
          <h2 class="mb-4 text-success fw-bold">¿Qué hacemos?</h2>
          <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item">Evaluación y diagnóstico funcional personalizado</li>
            <li class="list-group-item">Rehabilitación física para lesiones deportivas y laborales</li>
            <li class="list-group-item">Terapias manuales y ejercicios terapéuticos</li>
            <li class="list-group-item">Programas de prevención y promoción del bienestar</li>
          </ul>
          <p class="text-secondary">En nuestra clínica, combinamos ciencia, experiencia y cercanía para ayudarte a recuperar tu movilidad y calidad de vida. Nos adaptamos a tus necesidades y te acompañamos en cada paso de tu recuperación.</p>
        </div>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section id="newsletter" class="py-5 bg-success bg-opacity-10">
      <div class="container text-center">
        <h2 class="mb-3 text-success fw-bold">Suscríbete a la Newsletter</h2>
        <p class="mb-4 text-secondary">Recibe consejos de salud, ejercicios y novedades de la clínica directamente en tu correo.</p>
        <form class="row g-3 justify-content-center align-items-center newsletter">
          <div class="col-12 col-md-6">
            <input type="email" required placeholder="Tu correo electrónico" class="form-control form-control-lg">
          </div>
          <div class="col-12 col-md-auto">
            <button type="submit" class="btn btn-custom btn-lg px-4">Apuntarme</button>
          </div>
        </form>
      </div>
    </section>
  </main>

  <?php include 'partials/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
