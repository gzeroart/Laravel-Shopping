<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<body>
	<table>
			@foreach($us as $key=>$question)
			<tr>
				<td>
					<label>{{ $question["id"] }}</label><br />
				</td>
<td>
					<label>{{ $question["name"] }}</label><br />
				</td>				
			</tr>
			@endforeach
	</table>

</body>
</html>
