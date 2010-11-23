<?php $this->load->helper('form'); $this->load->helper('url'); ?>
<html>
<head>
<title>Books</title>
</head>
<body>
	<h1>My Favorite Books</h1>

<ul>
<?php foreach($books as $book): ?>
<li><?php echo "<b>{$book->title}</b> by {$book->author}"; ?>
	(<?php echo anchor(array('books', 'delete', $book->id), 'Delete'); ?>)</li>
<?php endforeach; ?>
</ul>

<h2>Add a book:</h2>
<?php

echo form_open('books/add');

echo form_label('Title', 'title');
echo form_input('title');
echo form_label('Author', 'author');
echo form_input('author');
echo form_submit('addbook', 'Add a book!');

echo form_close();
?>

</body>
</html>
