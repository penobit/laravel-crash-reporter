<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Error Details</title>
	<style>
		body {
			background-color: #f7f7f7;
			width: 100%;
			padding: 0;
		}

		.truncate {
			text-overflow: ellipsis;
			white-space: wrap;
			overflow: hidden;
		}

		.container {
			max-width: 980px;
			margin: 0 auto;
			padding: 20px 20px;
		}

		.card {
			background: #FFF;
			border-radius: 10px;
			box-shadow: 0 0 5px #DDD;
		}

		.card:not(:last-child) {
			margin-bottom: 2rem;
		}

		.card-header {
			padding: 15px;
			border-bottom: 1px solid #DDD;
			font-weight: bold;
		}

		.card-body {
			padding: 20px;
		}

		.details {
			margin: 0;
			margin-bottom: 20px;
		}

		.whitespace-pre-wrap {
			white-space: pre-wrap;
		}

		code {
			display: block;
			white-space: pre;
			padding: 10px;
			background: #e888;
			border: 3px dashed #eaa;
			line-height: 20px;
			overflow: auto;
			border-radius: 10px;
		}

		.break-words {
			word-wrap: break-word;
		}

		@media only screen and (max-width: 980px) {
			.container {
				max-width: 100%;
				padding: 0!important;
			}
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="card">
			<div class="card-header">Request Details</div>
			<div class="card-body">
				<p class="details">
					There was a crash in your application ({{ $data->url }}) at {{ now()->toDateTimeString() }}.
				</p>
				<div class="card">
					<div class="card-body">
						<p class="truncate">URL: {{ $data->url }}</p>
						<p class="truncate">Method: {{ $data->method }}</p>
						<p class="truncate">IP: {{ $data->ip }}</p>
						<p class="truncate">Agent: {{ $data->agent }}</p>
						<div>
							<p>Body:</p>
							<code class="whitespace-pre-wrap">{{ $data->body }}</code>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">Exception Details</div>
			<div class="card-body">
				<div>
					<h3>Message:</h3>
					<p>
						{{ $data->message }}
					</p>
				</div>
				<div>
					<h3>File:</h3>
					<p class="break-words">
						{{ $data->file }}:{{ $data->line }}
					</p>
				</div>
				<p>
					<h3>Trace:</h3>
					<code>{{ $data->trace }}</code>
				</p>
			</div>
		</div>
	</div>
</body>
</html>