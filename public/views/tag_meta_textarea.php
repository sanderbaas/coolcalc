<tr class="form-field <?php if ($flag_required) echo 'required';?>">
  <th scope="row" valign="top">
    <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
  </th>
  <td>
    <textarea class="large-text" cols="50" rows="5" id="<?php echo $name; ?>" <?php if ($flag_required) echo 'required'; ?> name="<?php echo $name; ?>"><?php echo $value; ?></textarea><br />
    <?php if ($howto): ?>
    <span class="description"><?php echo $howto; ?></span>
    <?php endif; ?>
  </td>
</tr>