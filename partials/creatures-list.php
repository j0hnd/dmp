<?php if(count($creatures)): ?>
    <?php foreach($creatures as $creature): ?>
<tr class="collapse-details" data-id="<?php echo $creature['id'] ?>">
    <td><?php echo $creature['id'] ?></td>
    <td><?php echo $creature['creature_name'] ?></td>
    <td><?php echo $creature['gender'] ?></td>
    <td><?php echo $creature['race'] ?></td>
    <td><?php echo $creature['date_of_birth'] ?></td>
    <td><?php echo $creature['place_of_birth'] ?></td>
    <td class="text-center"><?php echo ($creature['ever_carried_the_ring'] == 1) ? "Y" :"N" ?></td>
    <td class="text-center"><?php echo ($creature['enslaved_by_sauron'] == 1) ? "Y" : "N" ?></td>
    <td><?php echo $creature['created_at'] ?></td>
    <td class="action-navigation">
        <input type="button" class="btn btn-warning toggle-crimes" data-id="<?php echo $creature['id'] ?>" value="+ Crimes" />
        <input type="button" class="btn btn-info toggle-notes" data-id="<?php echo $creature['id'] ?>" value="+ Notes" />
        <input type="button" class="btn btn-danger toggle-deceased" data-id="<?php echo $creature['id'] ?>" value="Deceased" />
    </td>
</tr>
<tr id="collapse-details-<?php echo $creature['id'] ?>" class="collapse-details-row hidden">
    <td class="collapse-crimes" colspan="10">
        <div class="row">
            <div class="crimes-container col-xs-7">
                <h4>Crimes</h4>
                <?php if (count($creature['crimes'])): ?>
                    <?php foreach ($creature['crimes'] as $crime): ?>
                    <div class="crime-details-wrapper row">
                        <label><?php echo date('m-d-Y H:i:s', strtotime($crime['created_at'])) ?></label>
                        <div><?php echo $crime['notes'] ?></div>
                        <?php if ($crime['is_punished']): ?>
                            <label class="punished-text">*** Punished! ***</label>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No crimes recorded</p>
                <?php endif; ?>
            </div>

            <div class="notes-container col-xs-5">
                <h4>Notes</h4>
                <?php if (count($creature['notes'])): ?>
                    <?php foreach ($creature['notes'] as $note): ?>
                    <div class="notes-details-wrapper row">
                        <label><?php echo date('m-d-Y H:i:s', strtotime($note['created_at'])) ?></label>
                        <div><?php echo $note['notes'] ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No notes recorded</p>
                <?php endif; ?>
            </div>
        </div>
    </td>
</tr>
    <?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="10">No creatures found!</td>
</tr>
<?php endif; ?>
