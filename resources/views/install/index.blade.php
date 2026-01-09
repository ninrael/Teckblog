<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Установка TechBlog</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .install-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 0;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
        }
        .step.active .step-number {
            background: #667eea;
            color: white;
        }
        .step.completed .step-number {
            background: #4caf50;
            color: white;
        }
        .step-title {
            font-size: 14px;
            color: #666;
        }
        .step.active .step-title {
            color: #667eea;
            font-weight: bold;
        }
        .install-step {
            display: none;
        }
        .install-step.active {
            display: block;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <h1 class="text-center mb-4">Установка TechBlog</h1>
        <p class="text-center text-muted mb-4">Добро пожаловать! Давайте настроим ваш блог за несколько простых шагов.</p>

        <!-- Индикатор шагов -->
        <div class="step-indicator">
            <div class="step active" id="step1-indicator">
                <div class="step-number">1</div>
                <div class="step-title">База данных</div>
            </div>
            <div class="step" id="step2-indicator">
                <div class="step-number">2</div>
                <div class="step-title">Настройки</div>
            </div>
            <div class="step" id="step3-indicator">
                <div class="step-number">3</div>
                <div class="step-title">Администратор</div>
            </div>
        </div>

        <!-- Шаг 1: База данных -->
        <div class="install-step active" id="step1">
            <h3 class="mb-4">Настройка базы данных</h3>
            <form id="db-form">
                <div class="mb-3">
                    <label for="db_host" class="form-label">Хост БД *</label>
                    <input type="text" class="form-control" id="db_host" name="db_host" value="127.0.0.1" required>
                </div>
                <div class="mb-3">
                    <label for="db_port" class="form-label">Порт *</label>
                    <input type="number" class="form-control" id="db_port" name="db_port" value="3306" required>
                </div>
                <div class="mb-3">
                    <label for="db_database" class="form-label">Имя базы данных *</label>
                    <input type="text" class="form-control" id="db_database" name="db_database" required>
                    <small class="form-text text-muted">База данных должна быть создана заранее</small>
                </div>
                <div class="mb-3">
                    <label for="db_username" class="form-label">Имя пользователя *</label>
                    <input type="text" class="form-control" id="db_username" name="db_username" value="root" required>
                </div>
                <div class="mb-3">
                    <label for="db_password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="db_password" name="db_password">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Проверить подключение</button>
                </div>
            </form>
            <div id="db-message"></div>
        </div>

        <!-- Шаг 2: Настройки приложения -->
        <div class="install-step" id="step2">
            <h3 class="mb-4">Настройки приложения</h3>
            <form id="app-form">
                <div class="mb-3">
                    <label for="app_name" class="form-label">Название блога *</label>
                    <input type="text" class="form-control" id="app_name" name="app_name" value="TechBlog" required>
                </div>
                <div class="mb-3">
                    <label for="app_url" class="form-label">URL сайта *</label>
                    <input type="url" class="form-control" id="app_url" name="app_url" value="http://localhost" required>
                    <small class="form-text text-muted">Полный URL вашего сайта (например: https://example.com)</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(1)">Назад</button>
                    <button type="button" class="btn btn-primary ms-auto" onclick="goToStep(3)">Далее</button>
                </div>
            </form>
        </div>

        <!-- Шаг 3: Администратор -->
        <div class="install-step" id="step3">
            <h3 class="mb-4">Создание администратора</h3>
            <form id="admin-form">
                <div class="mb-3">
                    <label for="admin_name" class="form-label">Имя администратора *</label>
                    <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                </div>
                <div class="mb-3">
                    <label for="admin_email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                </div>
                <div class="mb-3">
                    <label for="admin_password" class="form-label">Пароль *</label>
                    <input type="password" class="form-control" id="admin_password" name="admin_password" minlength="8" required>
                    <small class="form-text text-muted">Минимум 8 символов</small>
                </div>
                <div class="mb-3">
                    <label for="admin_password_confirmation" class="form-label">Подтверждение пароля *</label>
                    <input type="password" class="form-control" id="admin_password_confirmation" name="admin_password_confirmation" minlength="8" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary" onclick="goToStep(2)">Назад</button>
                    <button type="submit" class="btn btn-success ms-auto">
                        <span class="spinner-border spinner-border-sm d-none" id="install-spinner"></span>
                        Завершить установку
                    </button>
                </div>
            </form>
            <div id="install-message"></div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const dbData = {};

        // Обработка формы базы данных
        document.getElementById('db-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('db-message');
            messageDiv.innerHTML = '<div class="alert alert-info">Проверка подключения...</div>';

            try {
                const response = await fetch('{{ route("install.check-db") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const data = await response.json();

                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    Object.assign(dbData, Object.fromEntries(formData));
                    setTimeout(() => goToStep(2), 1000);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            } catch (error) {
                messageDiv.innerHTML = '<div class="alert alert-danger">Ошибка: ' + error.message + '</div>';
            }
        });

        // Обработка формы установки
        document.getElementById('admin-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('admin_password').value;
            const passwordConfirmation = document.getElementById('admin_password_confirmation').value;
            
            if (password !== passwordConfirmation) {
                document.getElementById('install-message').innerHTML = 
                    '<div class="alert alert-danger">Пароли не совпадают!</div>';
                return;
            }

            const formData = new FormData(this);
            const appFormData = new FormData(document.getElementById('app-form'));
            
            // Объединяем все данные
            const allData = {
                ...Object.fromEntries(formData),
                ...Object.fromEntries(appFormData),
                ...dbData
            };

            const spinner = document.getElementById('install-spinner');
            const messageDiv = document.getElementById('install-message');
            spinner.classList.remove('d-none');
            messageDiv.innerHTML = '<div class="alert alert-info">Установка в процессе, пожалуйста подождите...</div>';

            try {
                const response = await fetch('{{ route("install.install") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(allData)
                });

                const data = await response.json();
                spinner.classList.add('d-none');

                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            } catch (error) {
                spinner.classList.add('d-none');
                messageDiv.innerHTML = '<div class="alert alert-danger">Ошибка: ' + error.message + '</div>';
            }
        });

        function goToStep(step) {
            // Скрываем все шаги
            document.querySelectorAll('.install-step').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.step').forEach(s => {
                s.classList.remove('active', 'completed');
            });

            // Показываем нужный шаг
            document.getElementById('step' + step).classList.add('active');
            document.getElementById('step' + step + '-indicator').classList.add('active');

            // Отмечаем предыдущие шаги как завершенные
            for (let i = 1; i < step; i++) {
                document.getElementById('step' + i + '-indicator').classList.add('completed');
            }

            currentStep = step;
        }
    </script>
</body>
</html>

