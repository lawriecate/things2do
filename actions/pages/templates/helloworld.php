<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>My PEANUT Page</title>
 
    </head>
    <body>
        <!--
        See how we use native php here?
        echo out a variable, this is the same
        variable name that you use in your template
        set method ($template->('title', "hello")).
        -->
        <h1><?php echo $title ?></h1>
        <p><?php echo $message ?></p>
    </body>
</html>
