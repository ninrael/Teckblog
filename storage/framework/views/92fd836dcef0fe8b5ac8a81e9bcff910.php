

<?php $__env->startSection('title', 'Комментарии'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление комментариями</h2>
    <div class="btn-group">
        <a href="<?php echo e(route('admin.comments.index', ['status' => 'all'])); ?>" class="btn btn-outline-primary <?php echo e(!request('status') ? 'active' : ''); ?>">Все</a>
        <a href="<?php echo e(route('admin.comments.index', ['status' => 'approved'])); ?>" class="btn btn-outline-success <?php echo e(request('status') === 'approved' ? 'active' : ''); ?>">Одобренные</a>
        <a href="<?php echo e(route('admin.comments.index', ['status' => 'pending'])); ?>" class="btn btn-outline-warning <?php echo e(request('status') === 'pending' ? 'active' : ''); ?>">На модерации</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Автор</th>
                        <th>Статья</th>
                        <th>Комментарий</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($comment->id); ?></td>
                            <td>
                                <?php if($comment->user): ?>
                                    <strong><?php echo e($comment->user->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($comment->user->email); ?></small>
                                <?php else: ?>
                                    <strong><?php echo e($comment->author_name ?? 'Аноним'); ?></strong><br>
                                    <?php if($comment->author_email): ?>
                                        <small class="text-muted"><?php echo e($comment->author_email); ?></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('posts.show', $comment->post->slug)); ?>" target="_blank" class="text-decoration-none">
                                    <?php echo e(\Illuminate\Support\Str::limit($comment->post->title, 40)); ?>

                                </a>
                            </td>
                            <td><?php echo e(\Illuminate\Support\Str::limit($comment->content, 100)); ?></td>
                            <td>
                                <?php if($comment->is_approved): ?>
                                    <span class="badge bg-success">Одобрен</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">На модерации</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($comment->created_at->format('d.m.Y H:i')); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('admin.comments.edit', $comment)); ?>" class="btn btn-primary">Редактировать</a>
                                    <?php if($comment->is_approved): ?>
                                        <form action="<?php echo e(route('admin.comments.reject', $comment)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-warning">Отклонить</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('admin.comments.approve', $comment)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success">Одобрить</button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('admin.comments.destroy', $comment)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Удалить комментарий?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            <?php echo e($comments->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\blog\resources\views/admin/comments/index.blade.php ENDPATH**/ ?>