<?php
$flag_required = (isset($flag_required) && $flag_required) || false;
?>
<tr class="<?php if ($flag_required) echo 'required';?>">
  <td class="first"><?php echo $label; ?>:</td>
  <td>&euro; <input class="input_price" id="<?php echo $name; ?>" <?php if ($flag_required) echo 'required'; ?> type="number" min="0.01" max="999999999" step="0.01" name="<?php echo $name; ?>" value="<?php echo $value; ?>" /></input><br />
  <?php if ($howto): ?>
  <p class="howto"><?php echo $howto; ?></p>
  <?php endif; ?>
  </td>
</tr>