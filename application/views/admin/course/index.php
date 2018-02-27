<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

            <div class="content-wrapper">
                <section class="content-header">
                    <?php echo $pagetitle; ?>
                    <?php echo $breadcrumb; ?>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                             <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo anchor('admin/course/create', '<i class="fa fa-plus"></i> '. lang('course_add'), array('class' => 'btn btn-block btn-primary btn-flat')); ?></h3>
                                </div>
    
                                <div class="box-body">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('course_code');?></th>
                                                <th><?php echo lang('course_name');?></th>
                                                <th><?php echo lang('course_action');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
<?php foreach ($courses as $values):?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($values->course_code, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($values->course_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo anchor("admin/course/edit/".$values->id, lang('course_edit')); ?></td>
                                            </tr>
<?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                         </div>
                    </div>
                </section>
            </div>
