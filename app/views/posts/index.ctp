<!-- File: /app/views/posts/index.ctp -->

<?php echo $html->link('Add '.Inflector::singularize($this->name),'/posts/add'); ?>

<h1>Blog posts</h1>

<table>
	<tr>
		<th>Id</th>
		<th>Title</th>
		<th>Actions</th>
		<th>Created</th>
		<th>Modified</th>
	</tr>

	<!-- Here is where we loop through our $posts array, printing out post info -->
	<?php foreach ($posts as $post): ?>
	<tr>
		<td><?php echo $post['Post']['id']; ?></td>
		<td>
			<?php echo $html->link($post['Post']['title'], "/posts/view/".$post['Post']['id']); ?>
		</td>
		<td>
			<?php echo $html->link('Edit', "/posts/edit/{$post['Post']['id']}")?>
			<?php echo $html->link('Delete', "/posts/delete/{$post['Post']['id']}", null, 'Are you sure?' )?>
		</td>
		<td><?php echo $post['Post']['created']; ?></td>
		<td><?php echo $post['Post']['modified']; ?></td>
	</tr>
	<?php endforeach; ?>	
</table>
