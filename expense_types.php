<?php
require 'config/db.php';
include 'header.php';
$types = $pdo->query("SELECT * FROM expense_types ORDER BY id ASC")->fetchAll();
?>

<div class="container mt-4">
  <h2 class="mb-4">Gider Türleri</h2>

  <form id="addTypeForm" class="d-flex mb-3">
    <input type="text" name="name" id="newTypeName" class="form-control me-2" placeholder="Yeni gider türü" required />
    <button type="submit" class="btn btn-primary">Ekle</button>
  </form>

  <ul class="list-group" id="typeList">
    <?php foreach ($types as $type): ?>
      <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $type['id'] ?>">
        <span class="type-label"><?= htmlspecialchars($type['name']) ?></span>
        <div>
          <button class="btn btn-sm btn-outline-secondary edit-btn">✏️</button>
          <button class="btn btn-sm btn-outline-danger delete-btn">🗑️</button>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php include 'footer.php'; ?>

<script>
$(document).ready(function () {
  // Ekleme işlemi
  $('#addTypeForm').on('submit', function (e) {
    e.preventDefault();
    const name = $('#newTypeName').val().trim();
    if (name) {
      $.post('save_expense_type.php', { name }, function () {
        location.reload();
      });
    }
  });

  // Silme işlemi
  $('.delete-btn').click(function () {
    const li = $(this).closest('li');
    const id = li.data('id');
    if (confirm("Bu gider türünü silmek istediğinizden emin misiniz?")) {
      $.post('delete_expense_type.php', { id }, function () {
        li.remove();
      });
    }
  });

  // Güncelleme işlemi
  $('.edit-btn').click(function () {
    const li = $(this).closest('li');
    const span = li.find('.type-label');
    const id = li.data('id');
    const oldValue = span.text();

    const input = $('<input type="text" class="form-control form-control-sm">').val(oldValue);
    span.replaceWith(input);
    input.focus();

    input.blur(function () {
      const newValue = input.val().trim();
      if (newValue && newValue !== oldValue) {
        $.post('update_expense_type.php', { id, name: newValue }, function () {
          input.replaceWith(`<span class="type-label">${newValue}</span>`);
        });
      } else {
        input.replaceWith(`<span class="type-label">${oldValue}</span>`);
      }
    });
  });
});
</script>
