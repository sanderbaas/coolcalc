<tr class="form-field <?php if ($flag_required) echo 'required';?>">
  <th scope="row" valign="top">
    <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
  </th>
  <td>
    <input id="<?php echo $name; ?>" <?php if ($flag_required) echo 'required'; ?> type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" /></input><br />
    <?php if ($howto): ?>
    <span class="description"><?php echo $howto; ?></span>
    <?php endif; ?><br /><br />
  </td>
</tr>