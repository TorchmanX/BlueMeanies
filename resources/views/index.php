<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?=asset('style.css')?>">

		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<script src="<?=asset('script.js')?>"></script>

		<title>Pepperland</title>
	</head>
	<body>

		<div class="header">
			<h1>Pepperland</h1>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-5 col-input">
					<div class="conversation" data-session-id="<?=$sessionId?>">
						<div class="chat user user-template active">
							<span class="name">陳大文</span>
							<span class="content"></span>
							<img class="mic" src="<?= asset('img/mic.png') ?>" />
						</div>

						<div class="chat cs cs-template">
							<span class="name">客服李小姐</span>
							<span class="content"><img class="loading" src="<?= asset('img/ajax-loader.gif') ?>" /></span>
						</div>
					</div>
					<div class="remark">

						<textarea id="remark" placeholder="備注"></textarea>

						<button type="button" class="btn btn-primary btn-save">儲存</button>

					</div>
				</div>
				<div class="col-md-7 col-helps">

					<div class="categories">
						<h2>分類</h2>

						<ul>
							<?php foreach ($departments AS $department): ?>
							<li data-id="<?=$department->id?>"><?=$department->name?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="suggestions">
						<h2>建議</h2>

					</div>

				</div>
			</div>
		</div>

	</body>
</html>