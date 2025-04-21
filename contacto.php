<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto | Kinesiología</title>
    <link rel="icon" type="image/jpeg" href="img/favicon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/root.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
      body {
        color: #f7f6ef !important;
        min-height: 100vh;
        background-color: #1c1c1c;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      .contact-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: #fff;
        padding: 3rem 1rem 2rem 1rem;
      }

      .contact-hero-content h1 {
        font-size: 3rem;
        font-weight: bold;
        text-shadow: 0px 4px 10px rgba(0, 0, 0, 0.7);
      }

      .contact-section {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
      }

      .contact-card {
        background: rgba(35,39,43,0.92);
        border-radius: 1.5rem;
        box-shadow: 0 2px 16px 0 #0005;
        color: #f7f6ef;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        max-width: 480px;
        width: 100%;
        margin: 0 auto;
        padding: 3rem;
      }

      .contact-info {
        background: rgba(35,39,43,0.82);
        border-radius: 1.5rem;
        box-shadow: 0 2px 16px 0 #0002;
        color: #f7f6ef;
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
        text-align: left;
      }

      .contact-info i {
        color: #4CAF50;
        font-size: 1.4rem;
        margin-right: 10px;
      }

      .form-control, .form-label {
        color: #f7f6ef !important;
        background: #23272b !important;
        border-color: #444 !important;
      }

      .form-control:focus {
        background: #23272b !important;
        color: #fff !important;
        border-color: #4CAF50;
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

      @media (max-width: 900px) {
        .contact-section {
          flex-direction: column;
          gap: 2rem;
        }
      }

      /* Responsive enhancements */
      @media (max-width: 768px) {
        .contact-hero-content h1 {
          font-size: 2.5rem;
        }
      }
    </style>
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<!-- Hero -->
<section class="py-0 mb-5 position-relative" style="background: url('assets/img/fondo.jpg') center/cover no-repeat; min-height: 50vh; height: 60vh;">
  <div style="background:rgba(0,0,0,0.5); position:absolute; inset:0;"></div>
  <div class="container position-relative d-flex flex-column justify-content-center align-items-center text-center text-white h-100" style="z-index:2; min-height:50vh;">
    <h1 class="display-3 fw-bold mb-3">Contacto</h1>
    <p class="lead mb-0">¿Tienes alguna duda? O si quieres pedir tu cita ya.</p>
  </div>
</section>

<!-- Formulario de contacto y datos -->
<section class="container contact-section">
    <div class="contact-info">
      <h3 class="mb-3 text-success fw-bold"><i class="bi bi-chat-text"></i>¿Prefieres contactarnos directamente?</h3>
      <p class="mb-2"><i class="bi bi-envelope-at"></i> info@kinesiologia.com</p>
      <p class="mb-2"><i class="bi bi-telephone"></i> 123 456 789</p>
      <p class="mb-2"><i class="bi bi-geo-alt"></i> C/ Salud y Vida, 123</p>
      <p class="mt-4 small text-secondary">Horario: L-V 9:00-14:00 y 16:00-20:00</p>
    </div>

    <div class="contact-card">
      <h2 class="text-center mb-4 fs-3">Pide tu cita</h2>
      <form method="POST" autocomplete="off">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <!-- Campo fecha -->
        <div class="mb-3">
          <label for="fecha" class="form-label">Día de la cita</label>
          <input type="date"
                 id="fecha"
                 name="fecha"
                 class="form-control"
                 required
                 min="<?= date('Y-m-d') ?>">
        </div>

        <!-- Campo hora -->
        <div class="mb-3">
          <label for="hora" class="form-label ">Hora de la cita</label>
          <select id="hora"
                  name="hora"
                  class="form-select form-control"
                  required>
            <option value="" disabled selected>Selecciona hora</option>
            <option value="10:00">10:00</option>
            <option value="11:30">11:30</option>
            <option value="13:00">13:00</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="mensaje" class="form-label">Mensaje</label>
          <textarea id="mensaje"
                    name="mensaje"
                    class="form-control"
                    rows="4"
                    placeholder="¿En qué podemos ayudarte?"
                    required></textarea>
        </div>

        <button type="submit" class="btn btn-custom w-100 py-2">Enviar</button>
      </form>
    </div>
  </section>

<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
