<!-- Modal de inicio de sesión -->
<div class="modal fade" id="inicioSesion" tabindex="-1" aria-labelledby="inicioSesion" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="fw-5 text-center m-3">Iniciar sesión</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" name="login" id="login" novalidate>
                    <div class="row">
                        <div class="col-12 col-md-9 mx-auto">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </span>
                                <input type="text" name="username" id="username" class="form-control rounded-end" placeholder="Usuario" />
                                <div class="invalid-feedback" id="feedback-username"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-9 mx-auto">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                                    </svg>
                                </span>
                                <input type="password" name="password" id="password" placeholder="Contraseña" class="form-control rounded-end" />
                                <div class="invalid-feedback" id="feedback-password"></div>
                            </div>
                        </div>
                    </div>
                    Registrate para realizar pedidos <a data-bs-toggle="modal" href="#registro" role="button" aria-controls="modal">Regístrese</a>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary mx-auto" id="login-submit">Enviar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de registro nuevo usuario -->
<div class="modal fade" id="registro" tabindex="-1" aria-labelledby="registro" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="fw-5 text-center m-3">Registro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div id="errores"></div>
                    <form method="POST" id="form-abm">
                        <div class="col-12 mb-2">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre">
                            <div class="invalid-feedback" id="feedback-nombre"></div>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="mail" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" name="mail" id="mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                            <div class="invalid-feedback" id="feedback-mail"> Por favor, introduce un correo electrónico válido.</div>
                        </div>
                        <div class="col-12 mb-2" id="password-field">
                            <label for="pass" class="form-label">Contraseña</label>
                            <input type="password" class="form-control passwords" name="pass" id="pass">
                            <div class="invalid-feedback" id="feedback-pass"></div>
                        </div>
                        <div class="col-12 mb-3" id="validate-password-field">
                            <label for="validarPass" class="form-label">Confirmar contraseña</label>
                            <input type="password" class="form-control passwords" name="validarPass" id="validarPass">
                            <div class="invalid-feedback" id="feedback-validarPass"></div>
                        </div>
                        ¿Ya tiene cuenta? <a data-bs-toggle="modal" href="#inicioSesion" role="button" aria-controls="modal">Inicie sesión</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary mx-auto" id="btn-submit">Enviar</button>
            </div>
            </form>
        </div>
    </div>
</div>
