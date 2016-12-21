<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Chatty</title>
		<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
	</head>
	<body>
		 @include('templates.partials.navigation')
		<div class="container">
			@include('templates.partials.alerts')
			@yield('content')
		</div>
		<script src="{{asset('js/bootstrap.min.js') }}"></script>
	</body>
</html>