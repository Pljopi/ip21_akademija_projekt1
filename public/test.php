!--->Work in progress ampak deluje <----!

<!DOCTYPE html>
<html>

<head>
	<title>
		How to call PHP function
		on the click of a Button ?
	</title>
</head>

<body style="text-align:center;">

	<h1 style="color:green;">
		GeeksforGeeks
	</h1>

	<h4>
		How to call PHP function
		on the click of a Button ?
	</h4>

	<?php

if (array_key_exists('button1', $_GET)) {
    button1();
} else if (array_key_exists('button2', $_GET)) {
    button2();
}
function button1()
{
    echo "This is Button1 that is selected";
}
function button2()
{require_once '../lib/bootstrap.php';
    $model = new Model();
    $list = $model->getList();
    $favouriteCurrency = $_GET;
    foreach ($favouriteCurrency as $value) {
        if (array_key_exists($value, $list)) {

            $favs[] = $value;
        } else {
            $fail[] = $value;
        }
    }
    if (!empty($fail)) {
        echo "The following currencie codes do not exist:\n";
        foreach ($fail as $value) {
            echo $value . "\n";
        }
    }
    if (!empty($favs)) {
        foreach ($favs as $value) {
            $model->printOrInsertFavourite($value, $list[$value]);
            $favouriteTags[] = $list[$value];

        }
        return $favouriteTags;
    } else {
        echo "No currencies were added to favourites\n";
        exit;
    }
}
?>

	<form method="get">
		<input type="submit" name="button1"
				class="18" value="18" />

		<input type="submit" name="button2"
				class="15" value="15" />
	</form>
</head>

</html>
