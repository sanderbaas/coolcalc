<?php
$flag_required = (isset($flag_required) && $flag_required) || false;
?>
<tr class="<?php if ($flag_required) echo 'required';?>">
  <td class="first"><?php echo $label; ?>:</td>
  <td><input <?php if ($value=='1'){ ?>checked="checked"<?php } ?> id="<?php echo $name; ?>" <?php if ($flag_required) echo 'required'; ?> type="checkbox" name="<?php echo $name; ?>" value="1" /></input><br />
  <?php if ($howto): ?>
  <p class="howto"><?php echo $howto; ?></p>
  <?php endif; ?>
  </td>
</tr>