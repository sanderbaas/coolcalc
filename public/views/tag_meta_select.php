<tr class="form-field <?php if ($flag_required) echo 'required';?> <?php if ($flag_multiple) echo 'multiple'; ?>">
  <th scope="row" valign="top">
    <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
  </th>
  <td>
    <select <?php if ($flag_multiple) echo 'multiple'; ?> <?php if ($flag_required) echo 'required'; ?> id="<?php echo $name; ?>" <?php if ($flag_required) echo 'required'; ?> type="text" name="<?php echo $name; ?>">
      <?php
      $selected = false;
      foreach ($value as $option) {
        if ($option['selected']=='1') {
          $selected = true;
        }
      }
      if (!$selected && !$flag_multiple) {
        ?><option class="level-0" value=""><?php _e( '-- Select --' ); ?></option><?php
      }

    if (!is_array($value)) {
      ?><option selected="SELECTED" class="level-0" value="<?php echo $value; ?>"><?php echo $value; ?></option>
      <?php
    }else {
      foreach ($value as $option) {
        ?><option <?php if ($option['selected']=='1') echo 'selected="SELECTED"'; ?> class="level-0" value="<?php echo $option['value']; ?>"><?php echo $option['label']; ?></option>
        <?php
      }
    }
    ?>
    </select><br />
    <?php if ($howto): ?>
    <span class="description"><?php echo $howto; ?></span>
    <?php endif; ?><br />
  </td>
</tr>