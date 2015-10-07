<tr class="<?php if ($flag_required) echo 'required';?> <?php if ($flag_multiple) echo 'multiple'; ?>">
  <td class="first" valign="top"><?php echo $label; ?></td>
  <td>
    <?php if ($flag_multiple):
    foreach ($value as $option) {
      ?><div class="checkbox">
      <label><input <?php if ($option['selected']=='1') echo 'checked="checked"' ?> type="checkbox" name="<?php echo $name; ?>[]" id="<?php echo $name; ?>[<?php echo $option['value']?>]" value="<?php echo $option['value']?>">
      <strong><?php echo $option['label']?></strong> <?php echo $option['description'];?></label>
      </div><?php 
    }
    else:
    foreach ($value as $option) {
      ?><div class="checkbox">
      <label><input <?php if ($option['selected']=='1') echo 'checked="checked"' ?> type="radio" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $option['value']?>">
      <strong><?php echo $option['label']?></strong> <?php echo $option['description'];?></label>
      </div><?php 
    }
    endif; ?>
    <br />
    <?php if ($howto): ?>
    <span class="description"><?php echo $howto; ?></span>
    <?php endif; ?><br />
  </td>
</tr>