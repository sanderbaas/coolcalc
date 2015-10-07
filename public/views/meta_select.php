<?php
$flag_required = (isset($flag_required) && $flag_required) || false;
$flag_multiple = (isset($flag_multiple) && $flag_multiple) || false;
$flag_optional = (isset($flag_optional) && $flag_optional) || false;
?>
<tr class="<?php if ($flag_required) echo 'required';?>">
  <td class="first" valign="top"><?php echo $label; ?></td>
  <td>
    <select <?php if ($flag_multiple) echo "multiple" ?> <?php if ($flag_required) echo 'required'; ?> id="<?php echo $name; ?>" <?php if ($flag_required) echo 'required'; ?> type="text" name="<?php echo $name; ?><?php if ($flag_multiple) echo "[]" ?>">
      <?php
      $selected = false;
      foreach ($value as $option) {
        if ($option['selected']=='1') {
          $selected = true;
        }
      }
      if ((!$selected && !$flag_multiple) || $flag_optional) {
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
    </select>
    <?php if ($howto): ?><br />
    <span class="description"><?php echo $howto; ?></span>
    <?php endif; ?><br />
  </td>
</tr>