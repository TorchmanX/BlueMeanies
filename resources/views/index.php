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
				<div class="col-md-3 col-input">
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

						<div class="chat voice voice-template">
							<span class="name">語音</span>
							<span class="content"></span>
						</div>
					</div>
					<div class="remark">

						<textarea id="remark" placeholder="備注"></textarea>

						<button type="button" class="btn btn-primary btn-save">儲存</button>

					</div>
				</div>
				<div class="col-md-9 col-helps">

					<div class="categories">
						<h2>分類</h2>

						<ul>
							<?php foreach ($categories AS $category): ?>
								<li data-id="<?=$category->id?>"><?=$category->name?></li>
							<?php endforeach; ?>
						</ul>
					</div>

					<div class="row row-suggestions">
						<div class="col-md-8 suggestions">
							<h2>建議</h2>

							<ul>
								<?php foreach ($departments AS $department): ?>
									<li data-id="<?=$department->id?>"><?=$department->name?></li>
								<?php endforeach; ?>
							</ul>
						</div>

						<div class="col-md-4 actions">
							<h4>部門</h4>

							<div>
								<a href="#">連繫</a>
							</div>
							<div>
								<a class="btn-chart" target="_blank" href="datamaps/node_modules/datamaps/src/examples/bubble-in-center.html">查看圖表</a>
							</div>
							<div>
								<a href="#">相關資料</a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div id="dlgChart" class="modal fade">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">即時圖表</h4>
					</div>
					<div class="modal-body">

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</body>
</html>