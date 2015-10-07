<tr class="<?php if ($flag_required) echo 'required';?>">
  <td class="first"><?php echo $label; ?>:</td>
  <td><input <?php if ($flag_required) echo 'required'; ?> type="url" placeholder="http://" name="<?php echo $name; ?>" value="<?php echo $value; ?>" /></input><br />
  <?php if ($howto): ?>
  <p class="howto"><?php echo $howto; ?></p>
  <?php endif; ?>
  </td>
</tr>