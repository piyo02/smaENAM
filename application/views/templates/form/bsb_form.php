<?php $this->load->library(array('form_validation'));  ?>
<?php $this->load->helper(['form']);  ?>
<!-- - -->
<?php foreach ($form_data as $form_name => $attr) : ?>
    <?php
        if ($attr['type'] == 'hidden') {
            $form = array(
                'name' => $form_name,
                'type' => $attr['type'],
                'placeholder' => (isset($attr['label'])) ? $attr['label'] : '',


            );
            $value = ((isset($data) && ($data != NULL))   ? (isset($data->$form_name) ? $data->$form_name : '') : '');
            $form['value'] = (isset($attr['value'])) ? $attr['value'] : $value;
            echo form_input($form);
            continue;
        }
        ?>
    <?php

        $form = array(
            'name' => $form_name,
            'id' => $form_name,
            'type' => $attr['type'],
            'placeholder' => (isset($attr['label'])) ? $attr['label'] : '',
            'class' => 'form-control',

        );
        if (isset($attr['readonly']))  $form['readonly'] = '';

        switch ($attr['type']) {
            case 'date':
                $form['class'] = "form-control datepicker";
                $form['type'] = "text";
            case 'password':
            case 'email':
            case 'text':
            case 'number':
                $value = ((isset($data) && ($data != NULL))   ? (isset($data->$form_name) ? $data->$form_name : '') : '');
                $form['value'] = (isset($attr['value'])) ? $attr['value'] : $value;
                echo '<div class="form-group">
                        <label for="' . $form_name . '" class="control-label">' . $attr["label"] . '</label>';
                echo form_input($form);
                echo '</div>';
                break;
            case 'hidden':
                $value = ((isset($data) && ($data != NULL))   ? (isset($data->$form_name) ? $data->$form_name : '') : '');
                $form['value'] = (isset($attr['value'])) ? $attr['value'] : $value;
                echo form_input($form);
                break;
            case 'textarea':
                $value = ((isset($data) && ($data != NULL))   ? (isset($data->$form_name) ? $data->$form_name : '') : '');
                $form['rows'] = "5";
                $form['value'] = (isset($attr['value'])) ? $attr['value'] : $value;
                echo '<div class="form-group">
                        <label for="" class="control-label">' . $attr["label"] . '</label>';
                echo form_textarea($form);
                echo '</div>';
                break;
            case 'multiple_file':
                $form['multiple'] = "";
            case 'file':
                echo '<div class="form-group">
                        <label for="' . $form_name . '">' . $attr['label'] . '</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="' . $form_name . '" id="' . $form_name . '">
                            </div>
                        </div>
                    </div>';
                break;
            case 'ckeditor':
                $value = ((isset($data) && ($data != NULL))   ? (isset($data->$form_name) ? $data->$form_name : '') : '');
                $form['rows'] = "5";
                $form['value'] = (isset($attr['value'])) ? $attr['value'] : $value;
                $form['id'] = 'editor';
                echo '<div class="form-group">
                        <label for="" class="control-label">' . $attr["label"] . '</label>';
                echo form_textarea($form);
                echo '</div>';
                break;
            case 'select_search':
                $form['class'] = 'form-control show-tick';
                $form['data-live-search'] = 'true';
            case 'select':
                $form['options'] = (isset($attr['options'])) ? $attr['options'] : '';
                $value = ((isset($data) && ($data != NULL))   ? (isset($data->$form_name) ? $data->$form_name : '') : '');
                $form['selected'] = (isset($attr['selected'])) ? $attr['selected'] : $value;
                echo '<div class="form-group">
                        <label for="" class="control-label">' . $attr["label"] . '</label>';
                echo form_dropdown($form);
                echo '</div>';
                break;
            case 'date_range':
                echo '<div class="form-group">
                        <label class="control-label">' . $attr["label"] . '</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="date" class="form-control" data-inputmask="\'alias\': \'dd/mm/yyyy\'" data-mask name="' . $form_name . '" id="' . $form_name . '">
                        </div>
                    </div>';
                break;
        }
        ?>
    <?php endforeach; ?>

    <!--  -->