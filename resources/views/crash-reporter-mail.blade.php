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
			position: sticky;
			top: 0;
			background: #FFF;
			z-index: 10;
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
			background: #e8e8e8;
			border: 3px dashed #c7c7c7;
			line-height: 20px;
			overflow: auto;
			border-radius: 10px;
		}

		.break-words {
			word-wrap: break-word;
		}

		.logo {
			height: 30px;
			width: 30px;
		}

		.table-wrapper {
			max-width: 100%;
			overflow: auto;
		}

		th {
			text-align: left;
			min-width: 100px;
			padding: 5px;
			position: sticky;
			left: 0;
			background: #FFF;
			border-right: 1px solid #DDD;
		}

		td {
			display: table-cell;
			overflow: auto;
			max-width: 100%;
			padding: 5px;
		}

		td, th {
			border-bottom: 1px solid #DDD;
		}

		.image-with-label {
			display: flex;
			align-items: center;
			gap: 1rem;
		}

		p.line-clamp {
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			font-size: 12px;
			font-family: monospace;
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
		<h1>{{ config('app.name') }}</h1>
		<h2>Unexpected Error Occured</h2>
		<div class="card">
			<div class="card-header">Request Details</div>
			<div class="card-body">
				<p class="details">
					There was a crash in your application at {{ now()->toDateTimeString() }}.
				</p>
				<div class="card">
					<div class="card-body">
						<div class="table-wrapper">
							<table>
								<tbody>
									<tr>
										<th>
											<span>URL</span>
										</th>
										<td>{{ $data?->url }}</td>
									</tr>
									<tr>
										<th>
											<span>Referrer URL </span>
										</th>
										<td>
											{{ $data?->referer ?? 'None' }}
										</td>
									</tr>
									<tr>
										<th>
											<span>Method</span>
										</th>
										<td>
											{{ $data?->method }}
										</td>
									</tr>
									<tr>
										<th>
											<span>IP</span>
										</th>
										<td>
											{{ $data?->ip }}
										</td>
									</tr>
									<tr>
										<th>
											<span>Browser</span>
										</th>
										<td>
											<div class='image-with-label'>
												@if($data?->browser_logo) <img class="logo" src="{{ $data->browser_logo }}" /> @endif
												<span>{{ $data?->browser ?? 'Unknown' }}</span>
											</div>
										</td>
									</tr>
									<tr>
										<th>
											<span>OS</span>
										</th>
										<td>
											<div class='image-with-label'>
												@if($data?->os_logo) <img class="logo" src="{{ $data->os_logo }}" /> @endif
												<span>{{ $data?->os ?? 'Unknown' }}</span>
											</div>
										</td>
									</tr>
									<tr>
										<th>
											<span>User Agent</span>
										</th>
										<td>
											<p class="line-clamp">{{ $data?->userAgent }}</p>
										</td>
									</tr>
									<tr>
										<th>
											<span>User</span>
										</th>
										<td>
											<p class="line-clamp">{{ $data?->user }}</p>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div>
							<p>Body</p>
							<code class="whitespace-pre-wrap">{{ $data?->body }}</code>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">Exception Details</div>
			<div class="card-body">
				<div>
					<h3>Message</h3>
					<p>
						{{ $data?->message }}
					</p>
				</div>
				<div>
					<h3>File</h3>
					<p class="break-words">
						{{ $data?->file }}:{{ $data?->line }}
					</p>
				</div>
				<p>
					<h3>Trace</h3>
					<code>{{ $data?->trace }}</code>
				</p>
			</div>
		</div>
	</div>
</body>
</html>