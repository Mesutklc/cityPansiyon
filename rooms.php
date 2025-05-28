<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require 'config/db.php';

// Oda ekleme işlemi
$add_error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $room_number = trim($_POST['room_number']);
    $capacity = (int)$_POST['capacity'];
    $status = $_POST['status'];

    if ($room_number === "" || $capacity <= 0) {
        $add_error = "Oda numarası ve kapasite geçerli olmalı.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_number, capacity, status) VALUES (?, ?, ?)");
        $stmt->execute([$room_number, $capacity, $status]);
        header("Location: rooms.php");
        exit;
    }
}

// Odaları çek
$stmt = $pdo->query("SELECT * FROM rooms ORDER BY id ASC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Oda Ekle</h2>
    <?php if ($add_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($add_error) ?></div>
    <?php endif; ?>
    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="add_room" value="1">
        <div class="col-md-4">
            <input type="text" name="room_number" class="form-control" placeholder="Oda Numarası" required>
        </div>
        <div class="col-md-4">
            <input type="number" name="capacity" class="form-control" min="1" placeholder="Kapasite" required>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select" required>
                <option value="boş">Boş</option>
                <option value="dolu">Dolu</option>
                <option value="rezerve">Rezerve</option>
            </select>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Oda Ekle</button>
        </div>
    </form>

    <h2>Odalar Listesi</h2>
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Oda Numarası</th>
                <th>Kapasite</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rooms as $room): ?>
            <tr data-id="<?= $room['id'] ?>">
                <td><?= $room['id'] ?></td>
                <td class="editable" data-field="room_number"><?= htmlspecialchars($room['room_number']) ?></td>
                <td class="editable" data-field="capacity"><?= $room['capacity'] ?></td>
                <td class="editable" data-field="status"><?= $room['status'] ?></td>
                <td><button class="btn btn-sm btn-danger btn-delete">Sil</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function(){
    let originalValue;

    // Inline edit işlemi
    $('.editable').on('click', function(){
        if ($(this).find('input, select').length) return; // Zaten düzenleniyor

        originalValue = $(this).text().trim();
        let field = $(this).data('field');
        let input;

        if(field === 'status') {
            input = $('<select class="form-select form-select-sm"></select>');
            ['boş', 'dolu', 'rezerve'].forEach(function(opt){
                let selected = (opt === originalValue) ? 'selected' : '';
                input.append(`<option value="${opt}" ${selected}>${opt}</option>`);
            });
        } else if(field === 'capacity') {
            input = $('<input type="number" min="1" class="form-control form-control-sm" />').val(originalValue);
        } else {
            input = $('<input type="text" class="form-control form-control-sm" />').val(originalValue);
        }

        $(this).html(input);
        input.focus();

        // Güncelleme işlemi - focus out
        input.on('blur', function(){
            let newValue = $(this).val().trim();
            let $td = $(this).parent();
            let id = $td.closest('tr').data('id');

            if(newValue === ""){
                $td.text(originalValue);
                return;
            }
            if(newValue === originalValue){
                $td.text(originalValue);
                return;
            }

            // Ajax ile güncelle
            $.ajax({
                url: 'update_room.php',
                method: 'POST',
                data: {
                    id: id,
                    field: field,
                    value: newValue
                },
                success: function(res){
                    if(res.success){
                        $td.text(newValue);
                    } else {
                        alert('Güncelleme başarısız: ' + res.error);
                        $td.text(originalValue);
                    }
                },
                error: function(){
                    alert('Sunucu hatası');
                    $td.text(originalValue);
                }
            });
        });
    });

    // Silme işlemi
    $('.btn-delete').on('click', function(){
        if(!confirm('Bu oda silinsin mi?')) return;

        let $tr = $(this).closest('tr');
        let id = $tr.data('id');

        $.ajax({
            url: 'delete_room.php',
            method: 'POST',
            data: { id: id },
            success: function(res){
                if(res.success){
                    $tr.remove();
                } else {
                    alert('Silme başarısız: ' + res.error);
                }
            },
            error: function(){
                alert('Sunucu hatası');
            }
        });
    });
});
</script>

</body>
</html>
