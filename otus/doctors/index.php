<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle("Врачи и процедуры");

\Bitrix\Main\Loader::includeModule('iblock');


$abstractClassPath = $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/abstractelementtable.php';
if (file_exists($abstractClassPath)) {
    require_once $abstractClassPath;
}

// Подключаем наши классы
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/doctortable.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/proceduretable.php';

$message = '';
$error = '';

// Обработка добавления врача
if ($_POST['action'] == 'add_doctor' && !empty($_POST['doctor_name'])) {
    $procedureIds = is_array($_POST['procedures']) ? $_POST['procedures'] : [];


    $validProcedureIds = [];
    if (!empty($procedureIds)) {
        $existingProcedures = Models\ProcedureTable::getAllProcedures();
        $existingProcedureIds = array_column($existingProcedures, 'ID');
        foreach ($procedureIds as $id) {
            if (in_array($id, $existingProcedureIds)) {
                $validProcedureIds[] = $id;
            }
        }
    }

    $result = Models\DoctorTable::addDoctor($_POST['doctor_name'], $validProcedureIds);

    if (is_numeric($result)) {
        $doctorId = $result;
        $message = "Врач успешно добавлен! ID: " . $doctorId;

        // Проверяем привязку процедур
        $attachedProcedures = Models\DoctorTable::getDoctorProcedures($doctorId);
        $message .= " (привязано процедур: " . count($attachedProcedures) . ")";

        unset($_POST['doctor_name']);
        unset($_POST['procedures']);
    } else {
        $error = $result;
    }
}

// Обработка добавления процедуры
if ($_POST['action'] == 'add_procedure' && !empty($_POST['procedure_name'])) {
    $result = Models\ProcedureTable::addProcedure($_POST['procedure_name']);
    if (is_numeric($result)) {
        $message = "Процедура успешно добавлена! ID: " . $result;
        unset($_POST['procedure_name']);
    } else {
        $error = $result;
    }
}

// Получаем данные
$doctors = Models\DoctorTable::getAllDoctors();
$allProcedures = Models\ProcedureTable::getAllProcedures();

// Получаем процедуры выбранного врача
$selectedDoctorId = $_GET['doctor_id'] ?? 0;
$doctorProcedures = [];

if ($selectedDoctorId) {
    $doctorProcedures = Models\DoctorTable::getDoctorProcedures($selectedDoctorId);

    $selectedDoctorName = '';
    foreach ($doctors as $doctor) {
        if ($doctor['ID'] == $selectedDoctorId) {
            $selectedDoctorName = $doctor['NAME'];
            break;
        }
    }
}
?>

    <div class="container">


        <?php if ($message): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>


        <!-- Список врачей -->
        <section class="doctors">
            <h2>Список врачей </h2>
            <?php
            if (empty($doctors)): ?>
                <p>Врачи не найдены</p>
            <?php
            else: ?>
                <div class="cards-list ">
                    <?php
                    foreach ($doctors as $doctor): ?>
                        <div class="card">
                            <a href="?doctor_id=<?= $doctor['ID'] ?>">
                                <?= htmlspecialchars($doctor['NAME']) ?>
                            </a>
                        </div>
                    <?php
                    endforeach; ?>
                </div>
            <?php
            endif; ?></section>


        <!-- Процедуры выбранного врача -->
        <?php if ($selectedDoctorId): ?>
            <div class="section">
                <h2>Процедуры врача: <?= htmlspecialchars($selectedDoctorName) ?> </h2>
                <?php if (empty($doctorProcedures)): ?>
                    <p>У врача нет назначенных процедур</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($doctorProcedures as $procedure): ?>
                            <li class="list-group-item"><?= htmlspecialchars($procedure['NAME']) ?> </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <!-- Форма добавления врача -->

            <h2>Добавить врача</h2>
            <form method="POST" class="doctor-add-form">
                <input type="hidden" name="action" value="add_doctor">
                <div class="form-group">
                    <label for="doctor_name">Имя врача:</label>
                    <input type="text" id="doctor_name" name="doctor_name" required class="form-control" value="<?= htmlspecialchars($_POST['doctor_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="procedures">Процедуры:</label>
                    <select id="procedures" name="procedures[]" multiple class="form-control">
                        <?php foreach ($allProcedures as $procedure): ?>
                            <option value="<?= $procedure['ID'] ?>"
                                <?= (isset($_POST['procedures']) && in_array($procedure['ID'], $_POST['procedures'])) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($procedure['NAME']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <button type="submit" class="btn btn-primary">Добавить врача</button>
            </form>


        <!-- Форма добавления процедуры -->

            <h2>Добавить процедуру</h2>
            <form method="POST" class="doctor-add-form">
                <input type="hidden" name="action" value="add_procedure">
                <div class="form-group">
                    <label for="procedure_name">Название процедуры:</label>
                    <input type="text" id="procedure_name" name="procedure_name" required class="form-control" style="max-width: 400px;" value="<?= htmlspecialchars($_POST['procedure_name'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-primary">Добавить процедуру</button>
            </form>


    </div>

    <style>
        .cards-list {
            display:flex;
            flex-wrap:wrap;
            width:100%;
            max-width:1024px;
            margin:40px auto;
            justify-content:flex-start;
            align-items:center;
        }
        .card {
            background: #f2f6f7;
            border-radius: 6px;
            height: 80px;
            width:200px;
            padding:12px;
            margin:12px;
            filter: drop-shadow(6px 6px 3px #4444dd);
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            text-align:center;
            font-size:16px;
        }
        .card:hover {
            filter: drop-shadow(6px 6px 3px #6666ff);
        }
        h1 {
            font: 26px/26px Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-weight: 300;
        }
        section.doctors {
            padding:40px;
            display:flex;
            flex-direction:column;
            align-items:center;
            font-size:18px;
        }
        .doctor-page {
            display:block;
            width:100%;
        }
        .doctor-add-form {
            display:flex;
            flex-direction:column;
        }
        .doctor-add-form * {
            width:400px;
            margin:12px;
            padding:6px;
            border-radius:6px;
            min-height:40px;
            font-size:16px;
        }
        .doctor-add-form>select,.doctor-add-form>input[type=submit] {
            width:416px;
        }
        .add-buttons {
            display:flex;
            justify-content:flex-start;
            width:100%;
            margin-top:40px;
        }
        .add-buttons button {
            padding:6px 12px;
            margin-right:16px;
            height:32px;
            border:1px solid #D2CBBD;
            border-radius:6px;
        }
        .add-buttons button:hover {
            filter: drop-shadow(6px 6px 3px #4444dd);

        }
    </style>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');