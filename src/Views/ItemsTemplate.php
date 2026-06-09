<?php
namespace App\Views;

class ItemsTemplate extends BaseTemplate {
    public function getListTemplate($rows) {
        global $user_role;
        $template = parent::getBaseTemplate();
        $str = '<h3>Список записей</h3>';
        // Поиск и фильтр
        $str .= '<form method="get" action="/items" class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Поиск по названию..." value="'.(isset($_GET['search']) ? $_GET['search'] : '').'">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Все статусы</option>
                    <option value="новая" '.( (isset($_GET['status']) && $_GET['status']=='новая') ? 'selected' : '' ).'>Новая</option>
                    <option value="в работе" '.( (isset($_GET['status']) && $_GET['status']=='в работе') ? 'selected' : '' ).'>В работе</option>
                    <option value="завершена" '.( (isset($_GET['status']) && $_GET['status']=='завершена') ? 'selected' : '' ).'>Завершена</option>
                    <option value="отказ" '.( (isset($_GET['status']) && $_GET['status']=='отказ') ? 'selected' : '' ).'>Отказ</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Искать</button>
            </div>
        </form>';
        $str .= '<table class="table"><thead><tr>
            <th>#</th>
            <th>Фото</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>Статус</th>
            <th>Клиент</th>
            <th>Менеджер</th>
            <th>Дата</th>';
        if ($user_role == 'editor') {
            $str .= '<th>Действия</th>';
        }
        $str .= '</tr></thead><tbody>';

        foreach ($rows as $row) {
            $str .= '<tr>
                <td>'.$row['id_item'].'</td>';
            if (!empty($row['image'])) {
                $str .= '<td><img src="/uploads/'.$row['image'].'" width="50"></td>';
            } else {
                $str .= '<td></td>';
            }
            $str .= '<td>'.$row['title'].'</td>
                <td>'.$row['category'].'</td>
                <td>'.$row['price'].'</td>
                <td>'.$row['status'].'</td>
                <td>'.$row['user_fio'].'</td>
                <td>'.$row['manager_fio'].'</td>
                <td>'.mb_substr($row['date_item'],0,10).'</td>';
            if ($user_role == 'editor') {
                $str .= '<td>
                    <form action="/edit_item" method="POST" style="display:inline">
                        <input type="hidden" name="id_item" value="'.$row['id_item'].'">
                        <button class="btn btn-sm btn-primary">Изм.</button>
                    </form>
                    <form action="/delete_item" method="POST" style="display:inline">
                        <input type="hidden" name="id_item" value="'.$row['id_item'].'">
                        <button class="btn btn-sm btn-danger" onclick="return confirm(\'Удалить?\')">Уд.</button>
                    </form>
                </td>';
            }
            $str .= '</tr>';
        }
        $str .= '</tbody></table>';
        if ($user_role == 'editor') {
            $str .= '<form action="/add_item" method="POST"><button class="btn btn-success">Добавить запись</button></form>';
        }
        return sprintf($template, 'Записи', $str);
    }

    public function getFormTemplate($row, $users, $managers) {
        $template = parent::getBaseTemplate();
        $isEdit = !empty($row);
        $title = $isEdit ? 'Редактирование' : 'Добавление';
        $action = $isEdit ? '/edit_item' : '/add_item';
        $str = "<h3>{$title} записи</h3>
            <form method='post' action='{$action}' enctype='multipart/form-data'>";
        if ($isEdit) {
            $str .= '<input type="hidden" name="id_item" value="'.$row['id_item'].'">
                     <input type="hidden" name="existing_image" value="'.$row['image'].'">';
        }
        $str .= '<div class="mb-3"><label>Название</label>
            <input name="title" class="form-control" value="'.($row['title']??'').'" required></div>';
        $str .= '<div class="mb-3"><label>Категория</label>
            <input name="category" class="form-control" value="'.($row['category']??'').'"></div>';
        $str .= '<div class="mb-3"><label>Описание</label>
            <textarea name="description" class="form-control">'.($row['description']??'').'</textarea></div>';
        $str .= '<div class="mb-3"><label>Цена</label>
            <input name="price" type="number" step="0.01" class="form-control" value="'.($row['price']??'0').'"></div>';
        $str .= '<div class="mb-3"><label>Дата</label>
            <input name="date_item" type="date" class="form-control" value="'.($isEdit ? mb_substr($row['date_item'],0,10) : date('Y-m-d')).'"></div>';
        $str .= '<div class="mb-3"><label>Клиент</label><select name="user_id" class="form-select">';
        foreach ($users as $u) {
            $sel = ($isEdit && $row['user_id']==$u['id_user']) ? 'selected' : '';
            $str .= "<option value='{$u['id_user']}' {$sel}>{$u['fio']}</option>";
        }
        $str .= '</select></div>';
        $str .= '<div class="mb-3"><label>Менеджер</label><select name="manager_id" class="form-select">';
        $str .= '<option value="">-- Не назначен --</option>';
        foreach ($managers as $m) {
            $sel = ($isEdit && $row['manager_id']==$m['id_user']) ? 'selected' : '';
            $str .= "<option value='{$m['id_user']}' {$sel}>{$m['fio']}</option>";
        }
        $str .= '</select></div>';
        $statuses = ['новая', 'в работе', 'завершена', 'отказ'];
        $str .= '<div class="mb-3"><label>Статус</label><select name="status" class="form-select">';
        foreach ($statuses as $st) {
            $sel = ($isEdit && $row['status']==$st) ? 'selected' : '';
            $str .= "<option value='{$st}' {$sel}>{$st}</option>";
        }
        $str .= '</select></div>';
        $str .= '<div class="mb-3"><label>Изображение</label><input type="file" name="image" class="form-control">';
        if ($isEdit && $row['image']) {
            $str .= '<img src="/uploads/'.$row['image'].'" width="100"><br>';
        }
        $str .= '</div>';
        $str .= '<button type="submit" class="btn btn-primary">Сохранить</button></form>';
        return sprintf($template, $title, $str);
    }
}