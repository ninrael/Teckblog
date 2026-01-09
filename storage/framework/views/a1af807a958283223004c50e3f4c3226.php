

<?php $__env->startSection('title', 'Пользователи'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление пользователями</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Статус</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($user->id); ?></td>
                            <td><?php echo e($user->name); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                                <form action="<?php echo e(route('admin.users.update-role', $user)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <select name="role_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; display: inline-block;">
                                        <option value="">Без роли</option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($role->id); ?>" <?php echo e($user->role_id == $role->id ? 'selected' : ''); ?>>
                                                <?php echo e($role->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <?php if($user->is_banned): ?>
                                    <span class="badge bg-danger">Забанен</span>
                                    <?php if($user->ban_reason): ?>
                                        <br><small class="text-muted"><?php echo e($user->ban_reason); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-success">Активен</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($user->created_at->format('d.m.Y')); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <?php if($user->is_banned): ?>
                                        <form action="<?php echo e(route('admin.users.unban', $user)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success btn-sm">Разбанить</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('admin.users.ban', $user)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-warning btn-sm">Забанить</button>
                                        </form>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#banReasonModal<?php echo e($user->id); ?>">
                                        Причина
                                    </button>
                                    <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Удалить пользователя?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                    </form>
                                </div>

                                <!-- Modal для причины бана -->
                                <div class="modal fade" id="banReasonModal<?php echo e($user->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="<?php echo e(route('admin.users.ban-reason', $user)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Причина бана: <?php echo e($user->name); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="ban_reason" class="form-label">Причина бана</label>
                                                        <textarea class="form-control" id="ban_reason" name="ban_reason" rows="3"><?php echo e($user->ban_reason); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            <?php echo e($users->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\blog\resources\views/admin/users/index.blade.php ENDPATH**/ ?>