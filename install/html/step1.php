<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PDO Installation step 1</title>
    <link rel="stylesheet" type="text/css" href="css/install.css">
</head>
<body>

    <h2>PDO Installation step 1</h2>
    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <fieldset>
        <br>
        <label>Name of your blog</label><br>
        <input name="blog_title" value="PHP Blog" required>
        <br><br>
        <label>Posts per page</label><br>
        <input name="perpage" type="number" value="10" requuired>
        <br><br>
        <label>Select Database</label><br>
        <select name="driver">
            <option value="sqlite" selected>SQLite</option>
            <option value="mysql">MySQL</option>          
        </select>
        <input type="hidden" name="step" value="1">
        <br><br>
        <input type="submit" value="SUBMIT">
        </fieldset>
    </form>

</body>
</html>
