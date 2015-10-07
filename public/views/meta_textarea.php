<tr class="<?php if ($flag_required) echo 'required';?>">
  <td class="first"><?php echo $label; ?>:</td>
  <td><textarea style="width:100%" id="<?php echo $name; ?>" <?php if ($flag_required) echo 'required'; ?> name="<?php echo $name; ?>"><?php echo $value; ?></textarea><br />
  <?php if ($howto): ?>
  <p class="howto"><?php echo $howto; ?></p>
  <?php endif; ?>
  </td>
</tr>