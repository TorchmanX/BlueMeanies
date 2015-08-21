<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?=asset('style.css')?>">

		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<script src="<?=asset('script.js')?>"></script>
	</head>
	<body>

		<div class="header">
			<h1>Pepperland</h1>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-4 col-input">
					<div class="conversation">
						<div class="chat user user-template">
							<span class="name">陳大文</span>
							<span class="content"></span>
							<img class="mic" src="<?= asset('img/mic.png') ?>" />
						</div>

						<div class="chat cs cs-template">
							<span class="name">客服陳小姐</span>
							<span class="content"></span>
						</div>
					</div>
					<div class="remark">

						<textarea id="remark" placeholder="備注"></textarea>

					</div>
				</div>
				<div class="col-md-4">
				</div>
			</div>
		</div>

	</body>
</html>