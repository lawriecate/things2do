<? if(isset($error)) {
    ?>
    <p><?=$error?></p>
    <?
}
?>
<form name="login" method="post" action="">
  <p>
    <label for="email">Email:</label>
    <input type="text" name="email" id="email">
  </p>
  <p>
    <label for="pass">Password:</label>
    <input type="password" name="pass" id="pass">
  </p>
  <p>
    <input type="submit" name="login" id="login" value="Log in">
  </p>
</form>
