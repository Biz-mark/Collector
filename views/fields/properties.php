<?php
    if (is_array($value)) {
        echo <<<HTML
<div class="control-list">
    <table class="table data">
        <thead>
            <tr>
                <th><span>Название</span></th>
                <th><span>Значение</span></th>
            </tr>
        </thead>
        <tbody>
HTML;

        foreach ($value as $property_name => $property_value) {
            echo '<tr>';
            echo '<td>' . $property_name . '</td>';
            echo '<td>' . $property_value . '</td>';
            echo '</tr>';
        }

        echo <<<HTML
        </tbody>
    </table>
</div>
HTML;
    } else {
        echo $value;
    }
?>
