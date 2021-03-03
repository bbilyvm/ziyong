<?php

namespace Nexus\Field;

use Nexus\Database\DB;

class Field
{
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_SELECT = 'select';
    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';

    public static $types = [
        self::TYPE_TEXT => '短文本(text)',
        self::TYPE_TEXTAREA => '长文本(textarea)',
        self::TYPE_RADIO => '横向单选(radio)',
        self::TYPE_CHECKBOX => '横向多选(checkbox)',
        self::TYPE_SELECT => '下拉单选(select)',
        self::TYPE_IMAGE => '图片(image)',
        self::TYPE_FILE => '文件(file)',
    ];


    public function radio($name, $options, $current = null)
    {
        $arr = [];
        foreach ($options as $value => $label) {
            $arr[] = sprintf(
                '<label style="margin-right: 4px;"><input type="radio" name="%s" value="%s"%s />%s</label>',
                $name, $value, (string)$current === (string)$value ? ' checked' : '', $label
            );
        }
        return implode('', $arr);
    }

    function buildFieldForm(array $row = [])
    {
        global $lang_fields;
        $trName = tr($lang_fields['col_name'] . '<font color="red">*</font>', '<input type="text" name="name" value="' . ($row['name'] ?? '') . '" style="width: 300px" />&nbsp;&nbsp;仅允许数字、字母、下划线', 1, '', true);
        $trLabel = tr($lang_fields['col_label'] . '<font color="red">*</font>', '<input type="text" name="label" value="' . ($row['label'] ?? '') . '"  style="width: 300px" />', 1, '', true);
        $trType = tr($lang_fields['col_type'] . '<font color="red">*</font>', $this->radio('type', self::$types, $row['type'] ?? null), 1, '', true);
        $trRequired = tr($lang_fields['col_required'] . '<font color="red">*</font>', $this->radio('required', ['0' => '否', '1' => '是'], $row['required'] ?? null), 1, '', true);
        $trHelp = tr($lang_fields['col_help'], '<textarea name="help" rows="4" cols="80">' . ($row['help'] ?? '') . '</textarea>', 1, '', true);
        $trOptions = tr($lang_fields['col_options'], '<textarea name="options" rows="6" cols="80">' . ($row['options'] ?? '') . '</textarea><br/>类型为单选、多选、下拉时必填，一行一个，格式：选项值|选项描述文本', 1, '', true);
        $id = $row['id'] ?? 0;
        $form = <<<HTML
<div>
<h1 align="center"><a class="faqlink" href="?action=view&type=">{$lang_fields['text_field']}</a></h1>
<form method="post" action="fields.php?action=submit&type=">
<div>
    <table border="1" cellspacing="0" cellpadding="10" width="100%">
            <input type="hidden" name="id" value="{$id}"/>
            {$trName}
            {$trLabel}
            {$trType}
            {$trRequired}
            {$trHelp}
            {$trOptions}
    </table>
</div>
<div style="text-align: center; margin-top: 10px;">
    <input type="submit" value="{$lang_fields['submit_submit']}" />
</div>
</form>
</div>
HTML;
        return $form;
    }

    function buildFieldTable()
    {
        global $lang_fields;
        $perPage = 10;
        $total = get_row_count('torrents_custom_fields');
        list($paginationTop, $paginationBottom, $limit) = pager($perPage, $total, "?");
        $sql = "select * from torrents_custom_fields order by id asc $limit";
        $res = sql_query($sql);
        $header = [
            'id' => $lang_fields['col_id'],
            'name' => $lang_fields['col_name'],
            'label' => $lang_fields['col_label'],
            'type_text' => $lang_fields['col_type'],
            'required_text' => $lang_fields['col_required'],
            'action' => $lang_fields['col_action'],
        ];
        $rows = [];
        while ($row = mysql_fetch_assoc($res)) {
            $row['required_text'] = $row['required'] ? '是' : '否';
            $row['type_text'] = self::$types[$row['type']] ?? '';
            $row['action'] = sprintf(
                "<a href=\"javascript:confirm_delete('%s', '%s', '');\">%s</a> | <a href=\"?action=edit&type=&id=%s\">%s</a>",
                $row['id'], $lang_fields['js_sure_to_delete_this'], $lang_fields['text_delete'], $row['id'], $lang_fields['text_edit']
            );
            $rows[] = $row;
        }
        $head = <<<HEAD
<h1 align="center">{$lang_fields['field_management']} - </h1>
<div style="margin-bottom: 8px;">
    <span id="item" onclick="dropmenu(this);">
        <span style="cursor: pointer;" class="big"><b>{$lang_fields['text_manage']}</b></span>
        <div id="itemlist" class="dropmenu" style="display: none">
            <ul>
                <li><a href="?action=view&type=field">{$lang_fields['text_field']}</a></li>
            </ul>
        </div>
    </span>
    <span id="add">
        <a href="?action=add&type=" class="big"><b>{$lang_fields['text_add']}</b></a>
    </span>
</div>
HEAD;
        $table = $this->buildTable($header, $rows);
        return $head . $table . $paginationBottom;
    }

    public function save($data)
    {
        $attributes = [];
        if (empty($data['name'])) {
            throw new \InvalidArgumentException("Name 必须");
        }
        if (!preg_match('/^\w+$/', $data['name'])) {
            throw new \InvalidArgumentException("Name 非法");
        }
        $attributes['name'] = $data['name'];

        if (empty($data['label'])) {
            throw new \InvalidArgumentException("显示标签 必须");
        }
        $attributes['label'] = $data['label'];

        if (empty($data['type'])) {
            throw new \InvalidArgumentException("类型 必须");
        }
        if (!isset(self::$types[$data['type']])) {
            throw new \InvalidArgumentException("类型 非法");
        }
        $attributes['type'] = $data['type'];

        if (!isset($data['required'])) {
            throw new \InvalidArgumentException("不能为空 必须");
        }
        if (!in_array($data['required'], ["0", "1"], true)) {
            throw new \InvalidArgumentException("不能为空 非法");
        }
        $attributes['required'] = $data['required'];

        $attributes['help'] = $data['help'] ?? '';
        $attributes['options'] = trim($data['options'] ?? '');
        $now = date('Y-m-d H:i:s');
        $attributes['updated_at'] = $now;
        $table = 'torrents_custom_fields';
        if (!empty($data['id'])) {
            $result = DB::update($table, $attributes, "id = " . sqlesc($data['id']));
        } else {
            $attributes['created_at'] = $now;
            $result = DB::insert($table, $attributes);
        }
        return $result;
    }

    public function buildTable(array $header, array $rows)
    {
        $table = '<table border="1" cellspacing="0" cellpadding="5" width="100%"><thead><tr>';
        foreach ($header as $key => $value) {
            $table .= sprintf('<td class="colhead">%s</td>', $value);
        }
        $table .= '</tr></thead><tbody>';
        foreach ($rows as $row) {
            $table .= '<tr>';
            foreach ($header as $headerKey => $headerValue) {
                $table .= sprintf('<td class="colfollow">%s</td>', $row[$headerKey] ?? '');
            }
            $table .= '</tr>';
        }
        $table .= '</tbody></table>';
        return $table;
    }

    public function buildFieldCheckbox($name, $current = [])
    {
        $sql = 'select * from torrents_custom_fields';
        $res = sql_query($sql);
        $checkbox = [];
        if (!is_array($current)) {
            $current = explode(',', $current);
        }
        while ($row = mysql_fetch_assoc($res)) {
            $checkbox[] = sprintf(
                '<label style="margin-right: 4px;"><input type="checkbox" name="%s" value="%s"%s>%s</label>',
                $name, $row['id'], in_array($row['id'], $current) ? ' checked' : '', "{$row['label']}({$row['id']})"
            );
        }
        return implode('', $checkbox);

    }

    public function renderUploadPage(array $customValues = [])
    {
        $searchBoxId = get_setting('main.browsecat');
        $searchBox = DB::getOne('searchbox', "id = $searchBoxId");
        if (empty($searchBox)) {
            throw new \RuntimeException("Invalid search box: $searchBoxId");
        }
        $sql = sprintf('select * from torrents_custom_fields where id in (%s)', $searchBox['custom_fields']);
        $res = sql_query($sql);
        $html = '';
        while ($row = mysql_fetch_assoc($res)) {
            $name = "custom_fields[{$row['id']}]";
            $currentValue = $customValues[$row['id']] ?? '';
            if ($row['type'] == self::TYPE_TEXT) {
                $html .= tr($row['label'], sprintf('<input type="text" name="%s" value="%s" style="width: 650px"/>', $name, $currentValue), 1);
            } elseif ($row['type'] == self::TYPE_TEXTAREA) {
                $html .= tr($row['label'], sprintf('<textarea name="%s" rows="4" style="width: 650px">%s</textarea>', $name, $currentValue), 1);
            } elseif ($row['type'] == self::TYPE_RADIO || $row['type'] == self::TYPE_CHECKBOX) {
                $part = "";
                foreach (preg_split('/[\r\n]+/', trim($row['options'])) as $option) {
                    if (empty($option) || ($pos = strpos($option, '|')) === false) {
                        continue;
                    }
                    $key = substr($option, 0, $pos);
                    $value = substr($option, $pos + 1);
                    $checked = "";
                    if ($row['type'] == self::TYPE_RADIO && (string)$currentValue === (string)$value) {
                        $checked = " checked";
                    }
                    if ($row['type'] == self::TYPE_CHECKBOX && in_array($value, (array)$currentValue)) {
                        $checked = " checked";
                    }
                    $part .= sprintf(
                        '<label style="margin-right: 4px"><input type="%s" name="%s" value="%s"%s />%s</label>',
                        $row['type'], $name, $key, $checked, $value
                    );
                }
                $html .= tr($row['label'], $part, 1);
            } elseif ($row['type'] == self::TYPE_SELECT) {
                $part = '<select name="' . $name . '">';
                foreach (preg_split('/[\r\n]+/', trim($row['options'])) as $option) {
                    if (empty($option) || ($pos = strpos($option, '|')) === false) {
                        continue;
                    }
                    $key = substr($option, 0, $pos);
                    $value = substr($option, $pos + 1);
                    $selected = "";
                    if (in_array($value, (array)$currentValue)) {
                        $selected = " selected";
                    }
                    $part .= sprintf(
                        '<option value="%s"%s>%s</option>',
                        $key, $selected, $value
                    );
                }
                $part .= '</select>';
                $html .= tr($row['label'], $part, 1);
            } elseif ($row['type'] == self::TYPE_IMAGE) {
                $callbackFunc = "preview_custom_field_image_" . $row['id'];
                $iframeId = "iframe_$callbackFunc";
                $inputId = "input_$callbackFunc";
                $imgId = "attach" . $row['id'];
                $previewBoxId = "preview_$callbackFunc";
                $y = '<iframe id="' . $iframeId . '" src="' . getSchemeAndHttpHost() . '/attachment.php?callback_func=' . $callbackFunc . '" width="100%" height="24" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>';
                $y .= sprintf('<input id="%s" type="text" name="%s" value="%s" style="width: 650px;margin: 10px 0">', $inputId, $name, $currentValue);
                $y .= '<div id="' . $previewBoxId . '">';
                if (!empty($currentValue)) {
                    if (substr($currentValue, 0, 4) == 'http') {
                        $y .= formatImg($currentValue, true, 700, 0, $imgId);
                    } else {
                        $y .= format_comment($currentValue);
                    }
                }
                $y .= '</div>';
                $y .= <<<JS
<script>
    function {$callbackFunc}(delkey, url)
    {
        var previewBox = $('$previewBoxId')
        var existsImg = $('$imgId')
        var input = $('$inputId')
        if (existsImg) {
            previewBox.removeChild(existsImg)
            input.value = ''
        }
        var img = document.createElement('img')
        img.src=url
        img.setAttribute('onload', 'Scale(this, 700, 0);')
        img.setAttribute('onclick', 'Preview(this);')
        input.value = '[attach]' + delkey + '[/attach]'
        img.id='$imgId'
        previewBox.appendChild(img)
    }
</script>
JS;
                $html .= tr($row['label'], $y, 1);
            } elseif ($row['type'] == self::TYPE_FILE) {
                $html .= tr($row['label'], sprintf('<input type="file" name="%s" />', $name), 1);
            }
        }
        return $html;
    }

}